<?php
/**
 * Modèle de Consommation
 *
 * Cette classe gère les opérations liées aux consommations d'électricité
 * dans la base de données.
 */
class ConsommationModel {
    private $db;

    /**
     * Constructeur
     *
     * Initialise la connexion à la base de données
     */
    public function __construct() {
        require_once __DIR__ . '/Db-Connection.php';
        $this->db = DBConnection::getInstance();
    }

    /**
     * Récupère la dernière consommation d'un client
     *
     * @param int $client_id Identifiant du client
     * @return array|false Données de la dernière consommation ou false si aucune
     */
    public function getDerniereConsommation($client_id) {
        $sql = "SELECT
            consommation_id as id,
            client_id,
            consommation as valeur,
            CONCAT(annee, '-', mois, '-01') as date_releve,
            photo_compteur
        FROM consommations_mensuelles
        WHERE client_id = ?
        ORDER BY annee DESC, mois DESC
        LIMIT 1";

$stmt = $this->db->prepare($sql);  // Utilise la variable $sql ici
$stmt->execute([$client_id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($result) {
            // Formatage de la date pour l'affichage
            $result['date_releve_formattee'] = date('d/m/Y', strtotime($result['date_releve']));
            return $result;
        }

        return false;
    }

    /**
     * Ajoute une nouvelle consommation
     *
     * @param int $client_id Identifiant du client
     * @param float $valeur Valeur de la consommation en kWh
     * @param string $photoPath Chemin de la photo du compteur
     * @param bool $depassementSeuil Indique si la consommation dépasse le seuil normal
     * @return int|false ID de la consommation ajoutée ou false en cas d'échec
     */
    public function ajouterConsommation($client_id, $valeur, $photoPath, $depassementSeuil = false) {
        // Obtenir le mois et l'année actuels
        $mois = date('n'); // 1-12
        $annee = date('Y');

        // Vérifier si une consommation existe déjà pour ce mois et cette année
        $sql_check = "SELECT consommation_id FROM consommations_mensuelles
        WHERE client_id = ? AND mois = ? AND annee = ?";
$stmt = $this->db->prepare($sql_check);
$stmt->execute([$client_id, $mois, $annee]);
$existing = $stmt->fetch(PDO::FETCH_ASSOC);



        if ($existing) {
            // Mise à jour de la consommation existante
            $sql = "UPDATE consommations_mensuelles
            SET consommation = ?, photo_compteur = ?, updated_at = NOW()
            WHERE consommation_id = ?";
    
    $stmt = $this->db->prepare($sql); // Prépare la requête
    $result = $stmt->execute([$valeur, $photoPath, $existing['consommation_id']]); // Exécute avec les paramètres
    
    return $result ? $existing['consommation_id'] : false;
    
        } else {
            // Insertion d'une nouvelle consommation
            $sql = "INSERT INTO consommations_mensuelles
        (client_id, mois, annee, consommation, photo_compteur)
        VALUES (?, ?, ?, ?, ?)";
        
$stmt = $this->db->prepare($sql); // Prépare la requête
$stmt->execute([$client_id, $mois, $annee, $valeur, $photoPath]); // Exécute avec les paramètres

        }
    }

    /**
     * Récupère l'historique des consommations d'un client
     *
     * @param int $client_id Identifiant du client
     * @param int $limit Nombre maximum de résultats à retourner
     * @return array Liste des consommations
     */
    public function getHistoriqueConsommations($client_id, $limit = 12) {
        $sql = "SELECT
                    consommation_id as id,
                    client_id,
                    mois,
                    annee,
                    consommation as valeur,
                    CONCAT(annee, '-', mois, '-01') as date_releve,
                    photo_compteur
                FROM consommations_mensuelles
                WHERE client_id = ?
                ORDER BY annee DESC, mois DESC
                LIMIT ?";

        $historique = $this->db->fetchAll($sql, [$client_id, $limit]);

        // Formatage des dates pour l'affichage
        foreach ($historique as &$item) {
            $item['date_releve_formattee'] = date('d/m/Y', strtotime($item['date_releve']));
        }

        return $historique;
    }

    /**
     * Vérifie si la consommation dépasse le seuil anormal
     *
     * @param int $client_id Identifiant du client
     * @param float $nouvelle_consommation Nouvelle valeur de consommation
     * @return bool True si la consommation dépasse le seuil, false sinon
     */
    public function verifierDepassementSeuil($client_id, $nouvelle_consommation) {
        $derniere_consommation = $this->getDerniereConsommation($client_id);

        if ($derniere_consommation && isset($derniere_consommation['valeur'])) {
            $difference = $nouvelle_consommation - $derniere_consommation['valeur'];
            return $difference > 50;
        }

        return false;
    }

    /**
     * Calcule la consommation moyenne sur une période
     *
     * @param int $client_id Identifiant du client
     * @param int $mois Nombre de mois à considérer
     * @return float Consommation moyenne en kWh
     */
    public function getConsommationMoyenne($client_id, $mois = 6) {
        $historique = $this->getHistoriqueConsommations($client_id, $mois);

        if (empty($historique)) {
            return 0;
        }

        $total = 0;
        foreach ($historique as $consommation) {
            $total += $consommation['valeur'];
        }

        return $total / count($historique);
    }

    /**
     * Calcule l'évolution de la consommation par rapport au mois précédent
     *
     * @param int $client_id Identifiant du client
     * @return array Données d'évolution (pourcentage, différence)
     */
    public function getEvolutionConsommation($client_id) {
        $historique = $this->getHistoriqueConsommations($client_id, 2);

        if (count($historique) < 2) {
            return [
                'pourcentage' => 0,
                'difference' => 0,
                'tendance' => 'stable'
            ];
        }

        $actuelle = $historique[0]['valeur'];
        $precedente = $historique[1]['valeur'];

        $difference = $actuelle - $precedente;

        if ($precedente == 0) {
            $pourcentage = 100; // Pour éviter division par zéro
        } else {
            $pourcentage = ($difference / $precedente) * 100;
        }

        $tendance = 'stable';
        if ($pourcentage > 5) {
            $tendance = 'hausse';
        } elseif ($pourcentage < -5) {
            $tendance = 'baisse';
        }

        return [
            'pourcentage' => round($pourcentage, 2),
            'difference' => round($difference, 2),
            'tendance' => $tendance
        ];
    }
}