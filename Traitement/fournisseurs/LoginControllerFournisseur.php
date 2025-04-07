<?php
session_start();
require_once'../../BD/DBConnection.php';
require_once '../../BD/LoginModelFournisseur.php';

class LoginControllerFournisseur {
    private $loginModel;

    public function __construct() {
        $this->loginModel = new LoginModelFournisseur();
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
            $fournisseur = $this->loginModel->authenticate($email, $password);

            if (!$fournisseur) {
                throw new Exception("Email ou mot de passe incorrect");
            }

            // 5. Création de la session
            $_SESSION['fournisseur_id'] = $fournisseur['fournisseur_id'];
            $_SESSION['fournisseur_email'] = $fournisseur['contact_email'];
            $_SESSION['fournisseur_nom'] = $fournisseur['nom'];
            $_SESSION['fournisseur_adresse'] = $fournisseur['adresse'];

            // 6. Redirection après connexion réussie
            header("Location: ../../IHM/fournisseur/dashboard-fournisseur.php");
            exit();

        } catch (Exception $e) {
            // Gestion des erreurs
            header("Location: ../../IHM/login_fournisseur.php?error=" . urlencode($e->getMessage()));
            exit();
        }
    }
}

// Exécution
$loginController = new LoginControllerFournisseur();
$loginController->handleLogin();