<?php
class ClientModel {
    private $conn;
    private $table = 'clients';

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
}
?>