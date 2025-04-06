<?php
require_once 'DBConnection.php';

class ReclamationModel {
    private $db;
    private $uploadDir = _DIR_ . '/../../assets/uploads/reclamations/';

    public function __construct() {
        $this->db = DbConnection::getInstance();
        
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    public function create(array $data) {
        // Gestion du fichier joint
        $fileName = null;
        if (!empty($data['piece_jointe']) && $data['piece_jointe']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($data['piece_jointe']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid() . '.' . $ext;
            move_uploaded_file($data['piece_jointe']['tmp_name'], $this->uploadDir . $fileName);
        }

        // Utilisation de la méthode query() de DBConnection
        $sql = "INSERT INTO reclamations 
                (client_id, type, description, piece_jointe)
                VALUES (?, ?, ?, ?)";
        
        $params = [
            $data['client_id'],
            $data['type'],
            $data['description'],
            $fileName
        ];

        // Exécution de la requête
        $this->db->query($sql, $params);
        
        // Récupération du dernier ID inséré via getConnection()
        return $this->db->getConnection()->lastInsertId();
    }
}
