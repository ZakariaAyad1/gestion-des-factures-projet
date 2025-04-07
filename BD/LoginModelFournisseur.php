<?php
require_once 'DBConnection.php';

class LoginModelFournisseur {
    private $db;
    
    public function __construct() {
        $this->db = DbConnection::getInstance()->getConnection();
    }

    public function authenticate($email, $password) {
        $stmt = $this->db->prepare("SELECT fournisseur_id, nom, adresse, contact_email 
                                    FROM fournisseurs 
                                    WHERE contact_email = ? AND password = ?");
        $stmt->execute([$email, $password]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}