<?php
require_once 'DBConnection.php';

class ReclamationModel {
    private $db;

    public function __construct() {
        // Récupérer l'objet PDO via la méthode getConnection() de DbConnection
        $this->db = DbConnection::getInstance()->getConnection();
    }

    public function getAllReclamations() {
        // Préparer la requête avec l'objet PDO
        $stmt = $this->db->prepare("SELECT * FROM reclamations ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
