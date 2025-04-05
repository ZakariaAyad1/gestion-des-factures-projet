<?php
class DashboardClientModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getFactureStats($client_id) {
        $sql = "SELECT 
                SUM(CASE WHEN etat = 'payee' THEN 1 ELSE 0 END) as payee,
                SUM(CASE WHEN etat = 'non_payee' THEN 1 ELSE 0 END) as non_payee,
                SUM(CASE WHEN etat = 'en_attente' THEN 1 ELSE 0 END) as en_attente,
                COUNT(*) as total
              FROM factures 
              WHERE client_id = :client_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':client_id', $client_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getReclamationStats($client_id) {
        $sql = "SELECT COUNT(*) as total FROM reclamations WHERE client_id = :client_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':client_id', $client_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function getConsommationsMensuelles($client_id, $limit = 12) {
        $sql = "SELECT mois, annee, consommation 
                FROM consommations_mensuelles 
                WHERE client_id = :client_id 
                ORDER BY annee DESC, mois DESC 
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':client_id', $client_id, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return array_reverse($stmt->fetchAll(PDO::FETCH_ASSOC)); // Pour avoir du plus ancien au plus récent
    }
}
?>