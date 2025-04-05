<?php
session_start();
require_once __DIR__ . '/../../BD/DBConnection.php';
require_once __DIR__ . '/../../BD/LoginModel.php';

class LoginController {
    private $loginModel;

    public function __construct() {
        $this->loginModel = new LoginModel();
    }

    public function handleLogin() {
        try {
            // 1. Vérification de la méthode HTTP
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Méthode non autorisée");
            }

            // 2. Nettoyage des entrées
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];

            // 3. Validation des données
            if (empty($email) || empty($password)) {
                throw new Exception("Tous les champs sont obligatoires");
            }

            // 4. Authentification
            $client = $this->loginModel->authenticate($email, $password);

            if (!$client) {
                throw new Exception("Email ou mot de passe incorrect");
            }

            // 5. Création de la session
            $_SESSION['client_id'] = $client['client_id'];
            $_SESSION['client_email'] = $client['email'];
            $_SESSION['client_nom'] = $client['nom'];
            $_SESSION['client_prenom'] = $client['prenom'];

            // 6. Redirection après connexion réussie
            header("Location: ../../IHM/clients/dashboard-client.php");
            exit();

        } catch (Exception $e) {
            // Gestion des erreurs
            header("Location: ../../IHM/login.php?error=" . urlencode($e->getMessage()));
            exit();
        }
    }
}

// Exécution
$loginController = new LoginController();
$loginController->handleLogin();