<?php
class Reclamation {
    private $conn;
    private $table = 'reclamations';

    // Propriétés de la réclamation
    public $reclamation_id;
    public $client_id;
    public $type;
    public $description;
    public $statut;
    public $reponse;
    public $piece_jointe;
    public $created_at;
    public $updated_at;

    // Constructeur
    public function __construct($db) {
        $this->conn = $db;
    }

    // Récupérer toutes les réclamations
    public function getAllReclamations($statutFilter = 'all', $typeFilter = 'all') {
        $query = "SELECT r.*, c.nom, c.prenom, c.adresse, c.email 
                  FROM reclamations r
                  INNER JOIN clients c ON r.client_id = c.client_id";
    
        // Appliquer les filtres
        $conditions = [];
        $params = [];
    
        if ($statutFilter != 'all') {
            $conditions[] = "r.statut = :statut";
            $params[':statut'] = $statutFilter;
        }
    
        if ($typeFilter != 'all') {
            $conditions[] = "r.type = :type";
            $params[':type'] = $typeFilter;
        }
    
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
    
        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
    
        $stmt->execute();
        return $stmt;
    }

    // Récupérer une réclamation par ID
    public function getReclamationById($id) {
        $query = "SELECT r.*, c.nom, c.prenom, c.adresse, c.email 
                  FROM reclamations r
                  JOIN clients c ON r.client_id = c.client_id
                  WHERE r.reclamation_id = :id 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mettre à jour le statut et la réponse d'une réclamation
    public function updateReclamation($id, $statut, $reponse) {
        $query = "UPDATE " . $this->table . " SET statut = :statut, reponse = :reponse, updated_at = NOW() WHERE reclamation_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':statut', $statut);
        $stmt->bindParam(':reponse', $reponse);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Ajouter une nouvelle réclamation
    public function createReclamation($client_id, $type, $description, $piece_jointe = null) {
        $query = "INSERT INTO " . $this->table . " (client_id, type, description, piece_jointe) VALUES (:client_id, :type, :description, :piece_jointe)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':client_id', $client_id);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':piece_jointe', $piece_jointe);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>