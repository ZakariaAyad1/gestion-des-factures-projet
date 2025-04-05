<?php
require_once 'DBConnection.php';

class LoginModel {
    private $db;
    
    public function __construct() {
        $this->db = DbConnection::getInstance()->getConnection();
    }

    public function authenticate($email, $password) {
        $stmt = $this->db->prepare("SELECT client_id, nom, prenom, email 
                                    FROM clients 
                                    WHERE email = ? AND mot_de_passe = ?");
        $stmt->execute([$email, $password]);

        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }
}
