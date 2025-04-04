<?php
require_once 'DBConnection.php';

class AgentModel {
    private $db;

    public function __construct() {
        // Remplacer la connexion avec la méthode Singleton de DBConnection
        $this->db = DBConnection::getInstance()->getConnection();
    }

    /**
     * Authentifie un agent
     * @param string $email
     * @param string $password
     * @return object|false Retourne les infos de l'agent sans le mot de passe ou false
     */
    public function authenticate($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM agents WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $agent = $stmt->fetch(PDO::FETCH_OBJ);
        
        if ($agent) {
            // Si mot de passe en clair (pour test seulement)
            if ($agent->mot_de_passe === $password) {
                return $agent;
            }
            // Si hash BCrypt
            if (password_verify($password, $agent->mot_de_passe)) {
                return $agent;
            }
        }
        
        return false;
    }

    /**
     * Vérifie si un email existe déjà
     * @param string $email
     * @return bool
     */
    public function emailExists($email) {
        $stmt = $this->db->prepare("SELECT 1 FROM agents WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return (bool)$stmt->fetchColumn();
    }
}
?>
