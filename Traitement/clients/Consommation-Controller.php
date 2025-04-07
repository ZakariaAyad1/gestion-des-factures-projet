<?php
/**
 * Contrôleur pour la gestion des consommations client
 */
require_once '../../BD/Consommation-Model.php';


class ConsommationController {
    private $consommationModel;

    public function __construct() {
        $this->consommationModel = new ConsommationModel();
    }

    public function saisirConsommation($client_id, $valeur, $file = null) {
        if (!is_numeric($valeur) || $valeur <= 0) {
            return [
                'success' => false,
                'message' => 'Veuillez entrer une valeur de consommation valide.',
                'class' => 'error'
            ];
        }

        $depassementSeuil = $this->consommationModel->verifierDepassementSeuil($client_id, $valeur);

        $photoPath = '';
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../Assets/Uploads/Compteurs/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = uniqid('compteur_') . '_' . $client_id . '_' . date('Ymd');
            $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
            $photoPath = 'Assets/Uploads/Compteurs/' . $fileName . '.' . $fileExt;
            $fullPath = $uploadDir . $fileName . '.' . $fileExt;

            if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
                return [
                    'success' => false,
                    'message' => 'Erreur lors du téléchargement de la photo.',
                    'class' => 'error'
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Veuillez télécharger une photo de votre compteur.',
                'class' => 'error'
            ];
        }

        $result = $this->consommationModel->ajouterConsommation($client_id, $valeur, $photoPath, $depassementSeuil);

        if ($result) {
            $message = 'Votre relevé de consommation a bien été enregistré.';
            $alertClass = $depassementSeuil ? 'warning' : 'success';
            if ($depassementSeuil) {
                $message .= ' Une vérification sera effectuée par nos services.';
            }

            return [
                'success' => true,
                'message' => $message,
                'class' => $alertClass,
                'consommation_id' => $result,
                'depassement_seuil' => $depassementSeuil
            ];
        }

        return [
            'success' => false,
            'message' => 'Erreur lors de l\'enregistrement de la consommation.',
            'class' => 'error'
        ];
    }

    public function getHistoriqueConsommations($client_id, $limit = 12) {
        return $this->consommationModel->getHistoriqueConsommations($client_id, $limit);
    }

    public function getDerniereConsommation($client_id) {
        return $this->consommationModel->getDerniereConsommation($client_id);
    }

    public function getConsommationMoyenne($client_id, $mois = 6) {
        return $this->consommationModel->getConsommationMoyenne($client_id, $mois);
    }

    public function getEvolutionConsommation($client_id) {
        return $this->consommationModel->getEvolutionConsommation($client_id);
    }

    public function genererCsrfToken() {
        if (!isset($_SESSION)) session_start();
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        return $token;
    }

    public function verifierCsrfToken($token) {
        if (!isset($_SESSION)) session_start();
        return isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token;
    }

    public function getClientId() {
        if (!isset($_SESSION)) session_start();
        return $_SESSION['client_id'] ?? 0;
    }
    
    public function saisieAutoriseeCeJour() {
        $aujourdhui = date('j'); // jour sans le 0 devant
        return $aujourdhui == 18;
    }
    
}
