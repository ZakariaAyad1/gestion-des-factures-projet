<?php
require_once 'DbConnection.php';

class ReclamationModel {
    private $db;
    private $uploadDir = '../../assets/uploads/reclamations/';

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
            $tempFileName = uniqid() . '.' . $ext;  // Nom temporaire unique
            move_uploaded_file($data['piece_jointe']['tmp_name'], $this->uploadDir . $tempFileName);
    
            // Insertion dans la base de données (sans nom de fichier spécifique encore)
            $sql = "INSERT INTO reclamations 
                    (client_id, type, description, piece_jointe)
                    VALUES (?, ?, ?, ?)";
            
            $params = [
                $data['client_id'],
                $data['type'],
                $data['description'],
                $tempFileName
            ];
    
            // Exécution de la requête pour insérer les données
            $this->db->query($sql, $params);
            
            // Récupération du dernier ID inséré
            $reclamationId = $this->db->getConnection()->lastInsertId();
            
            // Renommer le fichier avec l'ID de réclamation
            $finalFileName = "reclamation_" . $reclamationId . '.' . $ext;
            rename($this->uploadDir . $tempFileName, $this->uploadDir . $finalFileName);
            
            // Retourner l'ID de réclamation
            return $reclamationId;
        }
    
        return null;  // Si pas de fichier joint
    }

    public function getAllReclamations() {
        $sql = "SELECT * FROM reclamations";
        return $this->db->query($sql)->fetchAll();
    }

    // Méthode pour récupérer une réclamation spécifique par ID
    public function getReclamationById($id) {
        $sql = "SELECT * FROM reclamations WHERE id = ?";
        return $this->db->query($sql, [$id])->fetch();
    }
}