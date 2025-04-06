<?php

// BD/ClientModel.php
require_once 'DBConnection.php';

class ClientModel {
    private $conn;
    private $table = 'clients';

    // Constructeur prenant la connexion à la base de données comme argument
    public function __construct($db) {
        $this->conn = $db;
    }

    public function getClientById($client_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE client_id = :client_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':client_id', $client_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Méthode pour ajouter un client
    public function ajouterClient($nom, $prenom, $email, $adresse, $mot_de_passe) {
        $stmt = $this->conn->prepare("INSERT INTO clients (nom, prenom, email, adresse, mot_de_passe) VALUES (?, ?, ?, ?, ?)");
        $mot_de_passe = password_hash($mot_de_passe, PASSWORD_DEFAULT); // hashage
        return $stmt->execute([$nom, $prenom, $email, $adresse, $mot_de_passe]);
    }

    // Méthode pour supprimer un client
    public static function supprimerClient($id) {
        $db = DBConnection::getInstance()->getConnection();
        
        // Ajouter un log pour déboguer la suppression
        file_put_contents("debug.log", "Suppression du client avec l'ID : " . $id . "\n", FILE_APPEND);

        $stmt = $db->prepare("DELETE FROM clients WHERE client_id = ?");
        return $stmt->execute([$id]);
    }
}

?>
