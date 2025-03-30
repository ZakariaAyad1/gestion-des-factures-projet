<?php
require_once 'DBConnection.php';

class ConsommationAnnuelleModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function importerConsommation($client_id, $agent_id, $consommation, $annee, $date_saisie) {
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
    }

    public function getHistoriqueByAgent($agent_id) {
        $stmt = $this->db->prepare("SELECT c.*, cl.nom, cl.prenom 
                                 FROM consommation_annuelle c
                                 JOIN clients cl ON c.client_id = cl.client_id
                                 WHERE c.agent_id = :agent_id
                                 ORDER BY c.date_saisie DESC");
        $stmt->execute([':agent_id' => $agent_id]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}