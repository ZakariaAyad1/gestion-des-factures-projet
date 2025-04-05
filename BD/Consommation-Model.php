<?php
/**
 * Modèle de Consommation
 */
require_once __DIR__ . '/../BD/DBConnection.php';

class ConsommationModel {
    private $db;

    public function __construct() {
        $this->db = DBConnection::getInstance();
    }

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

        $result = $this->db->fetchOne($sql, [$client_id]);

        if ($result) {
            $result['date_releve_formattee'] = date('d/m/Y', strtotime($result['date_releve']));
            return $result;
        }

        return false;
    }

    public function ajouterConsommation($client_id, $valeur, $photoPath, $depassementSeuil = false) {
        $mois = date('n');
        $annee = date('Y');

        $sql_check = "SELECT consommation_id FROM consommations_mensuelles WHERE client_id = ? AND mois = ? AND annee = ?";
        $existing = $this->db->fetchOne($sql_check, [$client_id, $mois, $annee]);

        if ($existing) {
            $sql = "UPDATE consommations_mensuelles
                    SET consommation = ?, photo_compteur = ?, updated_at = NOW()
                    WHERE consommation_id = ?";
            $updated = $this->db->update($sql, [$valeur, $photoPath, $existing['consommation_id']]);
            return $updated ? $existing['consommation_id'] : false;
        } else {
            $sql = "INSERT INTO consommations_mensuelles
                    (client_id, mois, annee, consommation, photo_compteur)
                    VALUES (?, ?, ?, ?, ?)";
            return $this->db->insert($sql, [$client_id, $mois, $annee, $valeur, $photoPath]);
        }
    }

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

        foreach ($historique as &$item) {
            $item['date_releve_formattee'] = date('d/m/Y', strtotime($item['date_releve']));
        }

        return $historique;
    }

    public function verifierDepassementSeuil($client_id, $nouvelle_consommation) {
        $derniere = $this->getDerniereConsommation($client_id);

        if ($derniere && isset($derniere['valeur'])) {
            return ($nouvelle_consommation - $derniere['valeur']) > 50;
        }

        return false;
    }

    public function getConsommationMoyenne($client_id, $mois = 6) {
        $historique = $this->getHistoriqueConsommations($client_id, $mois);
        if (empty($historique)) return 0;

        $total = array_sum(array_column($historique, 'valeur'));
        return $total / count($historique);
    }

    public function getEvolutionConsommation($client_id) {
        $historique = $this->getHistoriqueConsommations($client_id, 2);

        if (count($historique) < 2) {
            return ['pourcentage' => 0, 'difference' => 0, 'tendance' => 'stable'];
        }

        $actuelle = $historique[0]['valeur'];
        $precedente = $historique[1]['valeur'];
        $difference = $actuelle - $precedente;

        $pourcentage = $precedente == 0 ? 100 : ($difference / $precedente) * 100;
        $tendance = $pourcentage > 5 ? 'hausse' : ($pourcentage < -5 ? 'baisse' : 'stable');

        return [
            'pourcentage' => round($pourcentage, 2),
            'difference' => round($difference, 2),
            'tendance' => $tendance
        ];
    }
}
