<?php
/**
 * Contrôleur pour la gestion des consommations client
 *
 * Ce fichier contient les fonctions de traitement des consommations
 * saisies par les clients.
 */

require_once __DIR__ . '/../../BD/Consommation-Model.php';

class ConsommationController {
    private $consommationModel;

    /**
     * Constructeur
     */
    public function __construct() {
        $this->consommationModel = new ConsommationModel();
    }

    /**
     * Traiter la saisie d'une nouvelle consommation
     *
     * @param int $client_id ID du client
     * @param float $valeur Valeur de la consommation en kWh
     * @param array $file Fichier téléchargé (photo du compteur)
     * @return array Résultat du traitement avec message et statut
     */
    public function saisirConsommation($client_id, $valeur, $file = null) {
        // Validation des données
        if (!is_numeric($valeur) || $valeur <= 0) {
            return [
                'success' => false,
                'message' => 'Veuillez entrer une valeur de consommation valide.',
                'class' => 'error'
            ];
        }

        // Vérification du seuil anormal
        $depassementSeuil = $this->consommationModel->verifierDepassementSeuil($client_id, $valeur);

        // Traitement de la photo du compteur
        $photoPath = '';
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../Assets/Uploads/Compteurs/';

            // Création du répertoire s'il n'existe pas
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Génération d'un nom de fichier unique
            $fileName = uniqid('compteur_') . '_' . $client_id . '_' . date('Ymd');
            $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
            $photoPath = 'Assets/Uploads/Compteurs/' . $fileName . '.' . $fileExt;
            $fullPath = $uploadDir . $fileName . '.' . $fileExt;

            // Déplacement du fichier téléchargé
            if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
                return [
                    'success' => false,
                    'message' => 'Une erreur est survenue lors du téléchargement de la photo.',
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

        // Enregistrement de la consommation
        $result = $this->consommationModel->ajouterConsommation(
            $client_id,
            $valeur,
            $photoPath,
            $depassementSeuil
        );

        if ($result) {
            $message = 'Votre relevé de consommation a bien été enregistré.';
            $alertClass = 'success';

            if ($depassementSeuil) {
                $message .= ' Une vérification sera effectuée par nos services en raison d\'une augmentation significative de votre consommation.';
                $alertClass = 'warning';
            }

            return [
                'success' => true,
                'message' => $message,
                'class' => $alertClass,
                'consommation_id' => $result,
                'depassement_seuil' => $depassementSeuil
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'enregistrement de votre consommation.',
                'class' => 'error'
            ];
        }
    }

    /**
     * Récupérer l'historique des consommations d'un client
     *
     * @param int $client_id ID du client
     * @param int $limit Nombre maximum de résultats à retourner
     * @return array Liste des consommations
     */
    public function getHistoriqueConsommations($client_id, $limit = 12) {
        return $this->consommationModel->getHistoriqueConsommations($client_id, $limit);
    }

    /**
     * Récupérer la dernière consommation d'un client
     *
     * @param int $client_id ID du client
     * @return array|false Données de la dernière consommation ou false si aucune
     */
    public function getDerniereConsommation($client_id) {
        return $this->consommationModel->getDerniereConsommation($client_id);
    }

    /**
     * Calculer la consommation moyenne sur une période
     *
     * @param int $client_id ID du client
     * @param int $mois Nombre de mois à considérer
     * @return float Consommation moyenne en kWh
     */
    public function getConsommationMoyenne($client_id, $mois = 6) {
        return $this->consommationModel->getConsommationMoyenne($client_id, $mois);
    }

    /**
     * Calculer l'évolution de la consommation par rapport au mois précédent
     *
     * @param int $client_id ID du client
     * @return array Données d'évolution (pourcentage, différence)
     */
    public function getEvolutionConsommation($client_id) {
        return $this->consommationModel->getEvolutionConsommation($client_id);
    }

    /**
     * Générer un token CSRF
     *
     * @return string Token CSRF
     */
    public function genererCsrfToken() {
        if (!isset($_SESSION)) {
            session_start();
        }

        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;

        return $token;
    }

    /**
     * Vérifier un token CSRF
     *
     * @param string $token Token à vérifier
     * @return bool True si le token est valide, false sinon
     */
    public function verifierCsrfToken($token) {
        if (!isset($_SESSION)) {
            session_start();
        }

        return isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token;
    }

    /**
     * Obtenir l'ID du client connecté
     *
     * @return int ID du client ou 0 si non connecté
     */
    public function getClientId() {
        if (!isset($_SESSION)) {
            session_start();
        }

        return isset($_SESSION['client_id']) ? $_SESSION['client_id'] : 0;
    }
}