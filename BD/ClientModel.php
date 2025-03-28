<?php
class ClientModel {
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO("mysql:host=localhost;dbname=gestion_factures;charset=utf8mb4", "root", "");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    // Récupérer tous les clients
    public function getAllClients() {
        $stmt = $this->pdo->prepare("SELECT * FROM clients");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer un client par ID
    public function getClientById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM clients WHERE client_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Ajouter un client
    public function createClient($fournisseur_id, $nom, $prenom, $adresse, $email, $mot_de_passe) {
        $hashed_password = password_hash($mot_de_passe, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("INSERT INTO clients (fournisseur_id, nom, prenom, adresse, email, mot_de_passe) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$fournisseur_id, $nom, $prenom, $adresse, $email, $hashed_password]);
    }

    // Mettre à jour un client
    public function updateClient($id, $fournisseur_id, $nom, $prenom, $adresse, $email, $mot_de_passe) {
        $hashed_password = password_hash($mot_de_passe, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("UPDATE clients SET fournisseur_id = ?, nom = ?, prenom = ?, adresse = ?, email = ?, mot_de_passe = ?, updated_at = NOW() WHERE client_id = ?");
        return $stmt->execute([$fournisseur_id, $nom, $prenom, $adresse, $email, $hashed_password, $id]);
    }

    // Supprimer un client
    public function deleteClient($id) {
        $stmt = $this->pdo->prepare("DELETE FROM clients WHERE client_id = ?");
        return $stmt->execute([$id]);
    }

    // Récupérer un client par email
    public function getClientByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM clients WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Vérifier le mot de passe d'un client
    public function verifyPassword($email, $mot_de_passe) {
        $client = $this->getClientByEmail($email);
        if ($client && password_verify($mot_de_passe, $client['mot_de_passe'])) {
            return $client;
        }
        return false;
    }

    // Récupérer les clients d'un fournisseur
    public function getClientsByFournisseur($fournisseur_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM clients WHERE fournisseur_id = ?");
        $stmt->execute([$fournisseur_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Compter le nombre total de clients
    public function countClients() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM clients");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
?>
