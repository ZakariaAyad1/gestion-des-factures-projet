<?php

include_once __DIR__ . '/../../BD/DBConnection.php';
include_once __DIR__ . '/../../BD/Reclamation.php';
include_once __DIR__ . '/../../BD/ClientModel.php';

class ReclamationController {
    private $db;
    private $reclamationModel;
    private $clientModel;

    public function __construct() {
        $this->db = new Database();
        $conn = $this->db->connect();
        $this->reclamationModel = new Reclamation($conn);
        $this->clientModel = new ClientModel($conn);
    }

    public function afficherReclamations($statutFilter = 'all', $typeFilter = 'all') {
        // Convertir 'Résolue' en 'Resolu' pour correspondre à la base
        if ($statutFilter === 'Résolue') $statutFilter = 'Resolu';
        if ($statutFilter === 'En cours') $statutFilter = 'En cours';
        
        $reclamations = $this->reclamationModel->getAllReclamations($statutFilter, $typeFilter);
        
        $result = [];
        while ($row = $reclamations->fetch(PDO::FETCH_ASSOC)) {
            // Convertir 'Resolu' en 'Résolue' pour l'affichage
            if ($row['statut'] === 'Resolu') $row['statut'] = 'Résolue';
            $result[] = $row;
        }
        return $result;
    }

    public function afficherDetailReclamation($id) {
        $reclamation = $this->reclamationModel->getReclamationById($id);
        
        if (!$reclamation) {
            return null;
        }
        
        // Conversion du statut pour l'affichage
        if ($reclamation['statut'] === 'Resolu') {
            $reclamation['statut'] = 'Résolue';
        }
        
        return $reclamation;
    }

    

    public function traiterReclamation($id, $statut, $reponse) {
        // Convertir 'Résolue' en 'Resolu' pour la base
        if ($statut === 'Résolue') $statut = 'Resolu';
        return $this->reclamationModel->updateReclamation($id, $statut, $reponse);
    }

    public function ajouterReclamation($client_id, $type, $description, $piece_jointe = null) {
        return $this->reclamationModel->createReclamation($client_id, $type, $description, $piece_jointe);
    }

    public function getClientInfo($client_id) {
        return $this->clientModel->getClientById($client_id);
    }


    public function getDetailData() {
        $data = [
            'should_redirect' => false,
            'show_confirmation' => false,
            'has_success' => false,
            'has_error' => false,
            'error_message' => '',
            'reclamation' => null,
            'statutAffichage' => 'Inconnu', // Valeur par défaut
            'confirmation_date' => '',
            'has_attached_file' => false,
            'file_url' => ''
        ];
    
        if (!isset($_GET['id'])) {
            $data['should_redirect'] = true;
            return $data;
        }
    
        $reclamation = $this->afficherDetailReclamation($_GET['id']);
        if (!$reclamation) {
            $data['should_redirect'] = true;
            return $data;
        }
    
        $data['reclamation'] = $reclamation;
        $data['statutAffichage'] = ($reclamation['statut'] === 'Resolu') ? 'Résolue' : ($reclamation['statut'] ?? 'Inconnu');
    
        if (isset($_SESSION['response_confirmation']) && $_SESSION['response_confirmation']['id'] == $_GET['id']) {
            $data['show_confirmation'] = true;
            $data['confirmation_date'] = date('d/m/Y à H:i', $_SESSION['response_confirmation']['time'] ?? time());
            
            // Modification ici pour gérer le cas où filename serait null
            $filename = $_SESSION['response_confirmation']['filename'] ?? '';
            $data['has_attached_file'] = $_SESSION['response_confirmation']['has_file'] ?? false;
            $data['file_url'] = "../../assets/uploads/".($filename ? htmlspecialchars($filename) : '');
            
            unset($_SESSION['response_confirmation']);
        }
    
        return $data;
    }
    
    
}


?>