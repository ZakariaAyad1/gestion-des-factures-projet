// Fournisseur-Model.php
<?php
require_once 'Db-Connection.php';

class Fournisseur_Model {
    private $pdo;

    public function __construct() {
        $this->pdo = Db_Connection::getInstance()->getConnection();
    }

    // Récupérer un fournisseur par email
    public function getFournisseurByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM fournisseurs WHERE contact_email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    // ... Autres méthodes CRUD pour les fournisseurs ...
}
?>
