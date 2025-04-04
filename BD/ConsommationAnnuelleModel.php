<?php
require_once 'DBConnection.php';

class ConsommationAnnuelleModel {
    private $db;

    public function __construct() {
        // Utilisation de DBConnection pour obtenir une connexion unique
        $this->db = DBConnection::getInstance()->getConnection();
    }

    /**
     * Insère une nouvelle consommation annuelle dans la base de données
     * 
     * @param int $client_id
     * @param int $agent_id
     * @param float $consommation
     * @param int $annee
     * @param string $date_saisie
     * @return bool Retourne true si l'insertion réussit, false sinon
     */
    public function importerConsommation($client_id, $agent_id, $consommation, $annee, $date_saisie) {
        try {
            $stmt = $this->db->prepare("INSERT INTO consommation_annuelle 
                                        (client_id, agent_id, consommation, annee, date_saisie) 
                                        VALUES (:client_id, :agent_id, :consommation, :annee, :date_saisie)");
            
            return $stmt->execute([
                ':client_id' => $client_id,
                ':agent_id' => $agent_id,
                ':consommation' => $consommation,
                ':annee' => $annee,
                ':date_saisie' => $date_saisie
            ]);
        } catch (PDOException $e) {
            // Gestion des erreurs
            echo "Erreur d'insertion: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Récupère l'historique de consommation par agent
     * 
     * @param int $agent_id
     * @return array Tableau des consommations ou un tableau vide en cas d'absence de données
     */
    public function getHistoriqueByAgent($agent_id) {
        try {
            $stmt = $this->db->prepare("SELECT c.*, cl.nom, cl.prenom 
                                        FROM consommation_annuelle c
                                        JOIN clients cl ON c.client_id = cl.client_id
                                        WHERE c.agent_id = :agent_id
                                        ORDER BY c.date_saisie DESC");
            $stmt->execute([':agent_id' => $agent_id]);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            // Gestion des erreurs
            echo "Erreur de récupération des données: " . $e->getMessage();
            return [];
        }
    }
}
?>
