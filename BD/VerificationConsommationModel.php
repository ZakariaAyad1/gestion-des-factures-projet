<?php
require_once __DIR__.'/DBConnection.php';

class VerificationConsommationModel {
    private $db;

    public function __construct() {
        $this->db = DBConnection::getInstance()->getConnection();
    }

    /**
     * Récupère les consommations mensuelles par client
     */
    public function getConsommationsMensuelles($client_id, $annee) {
        $sql = "SELECT mois, consommation 
                FROM consommations_mensuelles 
                WHERE client_id = ? AND annee = ? 
                ORDER BY mois";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$client_id, $annee]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Compare les consommations annuelles
     */
    public function anneeAvecDonnees($annee) {
        // Vérifie s'il y a des consommations mensuelles ou annuelles pour cette année
        $sql = "SELECT EXISTS(
                SELECT 1 FROM consommations_mensuelles WHERE annee = ? AND consommation > 0
                UNION
                SELECT 1 FROM consommation_annuelle WHERE annee = ? AND consommation > 0
                )";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$annee, $annee]);
        return (bool)$stmt->fetchColumn();
    }

    /**
     * Compare les consommations annuelles (retourne vide si année inexistante)
     */
    public function comparerConsommationsAnnuelle($annee) {
        if (!$this->anneeAvecDonnees($annee)) {
            return [];
        }

        $sql = "SELECT 
                c.client_id, 
                c.nom, 
                c.prenom,
                IFNULL(SUM(cm.consommation), 0) AS conso_client,
                IFNULL(ca.consommation, 0) AS conso_agent,
                ABS(IFNULL(SUM(cm.consommation), 0) - IFNULL(ca.consommation, 0)) AS difference
              FROM clients c
              LEFT JOIN consommations_mensuelles cm ON c.client_id = cm.client_id AND cm.annee = ?
              LEFT JOIN consommation_annuelle ca ON c.client_id = ca.client_id AND ca.annee = ?
              GROUP BY c.client_id
              HAVING conso_client > 0 OR conso_agent > 0"; // Exclut les clients sans données

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$annee, $annee]);
        
        $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach($resultats as &$client) {
            $client['mois'] = $this->getConsommationsMensuelles($client['client_id'], $annee);
        }
        
        return $resultats;
    }

    /**
     * Met à jour le statut de validation
     */
    public function marquerStatutValidation($client_id, $annee, $statut) {
        // Récupérer un agent_id valide (par exemple le premier agent)
        $agent_id = $this->db->query("SELECT agent_id FROM agents LIMIT 1")->fetchColumn();
        
        if (!$agent_id) {
            throw new Exception("Aucun agent trouvé dans la base de données");
        }
    
        // Récupérer consommation mensuelle (somme)
        $sqlMensuelle = "SELECT SUM(consommation) FROM consommations_mensuelles WHERE client_id = ? AND annee = ?";
        $stmt = $this->db->prepare($sqlMensuelle);
        $stmt->execute([$client_id, $annee]);
        $conso_mensuelle = (float) $stmt->fetchColumn();
    
        // Récupérer consommation annuelle (si elle existe)
        $sqlAnnuelle = "SELECT consommation FROM consommation_annuelle WHERE client_id = ? AND annee = ?";
        $stmt = $this->db->prepare($sqlAnnuelle);
        $stmt->execute([$client_id, $annee]);
        $conso_annuelle = (float) $stmt->fetchColumn();
    
        // Calculer la différence
        $difference = abs($conso_mensuelle - $conso_annuelle);
    
        // Insertion ou mise à jour dans consommation_annuelle
        $sql = "INSERT INTO consommation_annuelle 
                (client_id, annee, statut_validation, agent_id, difference, date_verification)
                VALUES (?, ?, ?, ?, ?, NOW())
                ON DUPLICATE KEY UPDATE 
                    statut_validation = ?,
                    agent_id = ?,
                    difference = ?,
                    date_verification = NOW()";
    
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $client_id, 
            $annee, 
            $statut, 
            $agent_id, 
            $difference,
            $statut, 
            $agent_id, 
            $difference
        ]);
    }
    
    



    
}
?>