<?php
class Db_Connection {
    private static $instance = null;
    private $pdo;

    // Constructeur privé pour le singleton
    private function __construct() {
        $dsn = 'mysql:host=localhost;dbname=gestion_factures;charset=utf8mb4';
        $username = 'votre_username';
        $password = 'votre_motdepasse';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_PERSISTENT => true,
        ];
        try {
            $this->pdo = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    // Retourne l'instance unique de la connexion
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Db_Connection();
        }
        return self::$instance;
    }

    // Retourne l'objet PDO
    public function getConnection() {
        return $this->pdo;
    }
}
?>
