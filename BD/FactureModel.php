<?php
require_once 'DBConnection.php';

class FactureModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = DBConnection::getInstance()->getConnection();
    }

    
    public function getFactureById($factureId) {
        $sql = "SELECT f.facture_id, f.client_id, f.consommation_id, f.prix_ht, f.tva, f.prix_ttc, 
                       f.etat, f.created_at, f.updated_at,
                       cl.nom, cl.prenom, cl.email, cl.adresse,
                       cm.mois, cm.annee, cm.consommation, cm.photo_compteur
                FROM factures f
                JOIN clients cl ON f.client_id = cl.client_id
                JOIN consommations_mensuelles cm ON f.consommation_id = cm.consommation_id
                WHERE f.facture_id = :facture_id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['facture_id' => $factureId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    // Insérer une nouvelle facture
    // On insère client_id et consommation_id (les triggers en BD calculent prix_ht et prix_ttc)
    public function insertFacture($data)
    {
        $sql = "INSERT INTO factures (client_id, consommation_id) 
                VALUES (:client_id, :consommation_id)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'client_id' => $data['client_id'],
            'consommation_id' => $data['consommation_id'],
        ]);
    }


    // Récupérer toutes les factures avec toutes les informations possibles
    public function getAllFactures()
    {
        $sql = "SELECT f.facture_id, f.client_id, f.consommation_id, f.prix_ht, f.tva, f.prix_ttc, 
                           f.etat, f.created_at, f.updated_at,
                           cl.nom, cl.prenom, cl.email,
                           cm.mois, cm.annee, cm.consommation, cm.photo_compteur
                    FROM factures f
                    JOIN clients cl ON f.client_id = cl.client_id
                    JOIN consommations_mensuelles cm ON f.consommation_id = cm.consommation_id
                    ORDER BY f.created_at DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mettre à jour le statut de la facture (ex. 'payee' ou 'non_payee')
    public function updateFactureStatus($facture_id, $newStatus)
    {
        $sql = "UPDATE factures SET etat = :etat, updated_at = CURRENT_TIMESTAMP 
                    WHERE facture_id = :facture_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'etat' => $newStatus,
            'facture_id' => $facture_id
        ]);
    }
}
