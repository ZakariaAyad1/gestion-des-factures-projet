<?php
require_once 'DBConnection.php';

class ConsommationMensuelleModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = DbConnection::getInstance()->getConnection();
    }

    public function getConsommations()
    {
        $stmt = $this->pdo->query("
            SELECT c.consommation_id, c.client_id, cl.nom, cl.prenom, c.mois, c.annee, c.consommation, c.photo_compteur
            FROM consommations_mensuelles c
            JOIN clients cl ON c.client_id = cl.client_id
            ORDER BY c.annee DESC, c.mois DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mettre à jour une consommation
    public function updateConsommation($consommation_id, $data)
    {
        $sql = "UPDATE consommations_mensuelles 
                SET mois = :mois, annee = :annee, 
                    consommation = :consommation, photo_compteur = :photo_compteur,
                    updated_at = CURRENT_TIMESTAMP 
                WHERE consommation_id = :consommation_id";
        $stmt = $this->pdo->prepare($sql);
        $data['consommation_id'] = $consommation_id;
        return $stmt->execute($data);
    }


    public function getConsommationById($consommation_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM consommations_mensuelles WHERE consommation_id = :consommation_id");
        $stmt->bindParam(':consommation_id', $consommation_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
