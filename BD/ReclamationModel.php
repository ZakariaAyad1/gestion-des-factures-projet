<?php

require_once 'Db-Connection.php';
class ReclamationModel {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function getAllReclamations() {
        $stmt = $this->db->prepare("SELECT * FROM reclamations ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
