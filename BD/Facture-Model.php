<?php
require_once 'DBConnection.php';

class FactureModel {
    private $db;

    public function __construct() {
        $this->db = DbConnection::getInstance();
    }

    public function getFacturesByClient($clientId) {
        $sql = "SELECT 
                    f.facture_id,
                    f.client_id,
                    f.consommation_id,
                    f.prix_ht,
                    f.tva,
                    f.prix_ttc,
                    f.etat,
                    c.mois,
                    c.annee,
                    c.consommation,
                    c.photo_compteur,
                    CONCAT(c.annee, '-', LPAD(c.mois, 2, '0'), '-01') AS date_consommation
                FROM 
                    factures f
                JOIN 
                    consommations_mensuelles c ON f.consommation_id = c.consommation_id
                WHERE 
                    f.client_id = :clientId
                ORDER BY 
                    f.created_at DESC";
        
        return $this->db->query($sql, [':clientId' => $clientId])->fetchAll();
    }
}