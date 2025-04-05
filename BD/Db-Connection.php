<?php
/**
 * Classe de connexion à la base de données
 *
 * Cette classe utilise le pattern Singleton pour assurer une seule instance
 * de connexion à la base de données pendant toute l'exécution du script.
 */
class DBConnection {
    // Instance unique de la classe
    private static $instance = null;

    // Objet PDO de connexion
    private $conn;

    // Paramètres de connexion à la base de données
    private $host = 'localhost';
    private $dbname = 'gestion_factures';
    private $username = 'root';
    private $password = 'chaymae2002'; // Modifiez cette valeur selon votre configuration
    private $charset = 'utf8mb4';
    private $port = 3308; // Définir le port

    /**
     * Constructeur privé pour empêcher l'instanciation directe
     */
    private function __construct() {
        try {
            // Ajouter le port dans le DSN
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset};port={$this->port}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            // Connexion à la base de données
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            // En production, il serait préférable de logger l'erreur plutôt que de l'afficher
            die("Erreur de connexion à la base de données: " . $e->getMessage());
        }
    }

    /**
     * Méthode pour obtenir l'instance unique de la classe
     *
     * @return DBConnection Instance unique de la classe
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Obtenir l'objet PDO de connexion
     *
     * @return PDO Objet PDO de connexion
     */
    public function getConnection() {
        return $this->conn;
    }

    /**
     * Exécuter une requête SQL avec des paramètres
     *
     * @param string $sql Requête SQL à exécuter
     * @param array $params Paramètres pour la requête préparée
     * @return PDOStatement Résultat de l'exécution
     */
    public function query($sql, $params = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            // En production, il serait préférable de logger l'erreur plutôt que de l'afficher
            die("Erreur d'exécution de la requête: " . $e->getMessage());
        }
    }

    /**
     * Récupérer tous les résultats d'une requête
     *
     * @param string $sql Requête SQL à exécuter
     * @param array $params Paramètres pour la requête préparée
     * @return array Tableau des résultats
     */
    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll();
    }

    /**
     * Récupérer une seule ligne de résultat
     *
     * @param string $sql Requête SQL à exécuter
     * @param array $params Paramètres pour la requête préparée
     * @return array|false Une ligne de résultat ou false si aucun résultat
     */
    public function fetchOne($sql, $params = []) {
        $result = $this->query($sql, $params)->fetch();
        return $result !== false ? $result : false;
    }

    /**
     * Insérer des données dans une table
     *
     * @param string $sql Requête SQL d'insertion
     * @param array $params Paramètres pour la requête préparée
     * @return int|false ID de la dernière insertion ou false en cas d'échec
     */
    public function insert($sql, $params = []) {
        $this->query($sql, $params);
        return $this->conn->lastInsertId();
    }

    /**
     * Mettre à jour des données dans une table
     *
     * @param string $sql Requête SQL de mise à jour
     * @param array $params Paramètres pour la requête préparée
     * @return int Nombre de lignes affectées
     */
    public function update($sql, $params = []) {
        return $this->query($sql, $params)->rowCount();
    }

    /**
     * Supprimer des données d'une table
     *
     * @param string $sql Requête SQL de suppression
     * @param array $params Paramètres pour la requête préparée
     * @return int Nombre de lignes affectées
     */
    public function delete($sql, $params = []) {
        return $this->query($sql, $params)->rowCount();
    }

    /**
     * Empêcher le clonage de l'instance
     */
    private function __clone() {}

    /**
     * Empêcher la désérialisation de l'instance
     * Cette méthode doit être publique pour éviter les erreurs
     */
    public function __wakeup() {}
}
?>
