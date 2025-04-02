<?php
class ReclamationResponseProcessor {
    private $controller;
    private $uploadDir = '../../assets/uploads/';
    
    public function __construct(ReclamationController $controller) {
        $this->controller = $controller;
    }
    
    public function process() {
        $this->startSession();
        $this->validateRequest();
        
        $reclamationId = $this->validateReclamationId();
        $statut = $this->sanitizeInput($_POST['statut']);
        $reponse = $this->sanitizeInput($_POST['reponse']);
        $fichier = $this->handleFileUpload();
        
        $this->handleResponse($reclamationId, $statut, $reponse, $fichier);
    }
    
    private function startSession() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
    
    private function validateRequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = "Requête invalide";
            $this->redirect('traitement-reclamations.php');
        }
    }
    
    private function validateReclamationId() {
        if (empty($_POST['reclamation_id'])) {
            $_SESSION['error'] = "ID de réclamation manquant";
            $this->redirect('traitement-reclamations.php');
        }
        
        $id = filter_var($_POST['reclamation_id'], FILTER_VALIDATE_INT);
        if ($id === false || $id <= 0) {
            $_SESSION['error'] = "ID de réclamation invalide";
            $this->redirect('traitement-reclamations.php');
        }
        return $id;
    }
    
    private function sanitizeInput($input) {
        return !empty($input) ? htmlspecialchars(trim($input)) : null;
    }
    
    private function handleFileUpload() {
        if (empty($_FILES['fichier']['name'])) {
            return null;
        }

        // Vérification erreur upload
        if ($_FILES['fichier']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = "Erreur lors du téléchargement du fichier";
            $this->redirect('traitement-reclamations.php');
        }

        // Vérification extension
        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
        $fileExtension = strtolower(pathinfo($_FILES['fichier']['name'], PATHINFO_EXTENSION));
        
        if (!in_array($fileExtension, $allowedExtensions)) {
            $_SESSION['error'] = "Type de fichier non autorisé";
            $this->redirect('traitement-reclamations.php');
        }

        // Création nom unique
        $newFilename = uniqid().'.'.$fileExtension;
        $destination = $this->uploadDir.$newFilename;

        if (!move_uploaded_file($_FILES['fichier']['tmp_name'], $destination)) {
            $_SESSION['error'] = "Échec de l'enregistrement du fichier";
            $this->redirect('traitement-reclamations.php');
        }

        return $newFilename;
    }
    
    private function handleResponse($reclamationId, $statut, $reponse, $fichier) {
        try {
            $success = $this->controller->traiterReclamation($reclamationId, $statut, $reponse, $fichier);
            
            if ($success) {
                $_SESSION['response_confirmation'] = [
                    'id' => $reclamationId,
                    'time' => time(),
                    'has_file' => !empty($fichier),
                    'filename' => $fichier
                ];
            } else {
                throw new Exception("Échec de l'enregistrement");
            }
        } catch (Exception $e) {
            error_log("Erreur: ".$e->getMessage());
            $_SESSION['error'] = "Erreur technique lors de l'enregistrement";
        }
        
        $this->redirect("../../IHM/fournisseur/detailReclamation.php?id=".$reclamationId);
    }
    
    private function redirect($location) {
        header("Location: ".$location);
        exit();
    }
}

// Initialisation
require_once __DIR__.'/../../BD/DBConnection.php';
require_once __DIR__.'/../../BD/Reclamation.php';
require_once __DIR__.'/ReclamationsController.php';

$db = new Database();
$reclamationModel = new Reclamation($db->connect());
$controller = new ReclamationController($reclamationModel);
$processor = new ReclamationResponseProcessor($controller);

$processor->process();
?>