<?php
require_once '../../BD/AgentModel.php';

class AgentController {
    private $model;

    public function __construct() {
        $this->model = new AgentModel();
    }

    /**
     * Gère le processus de connexion
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Nettoyage des entrées
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            // Validation
            if (empty($email) || empty($password)) {
                $this->redirectWithError("L'email et le mot de passe sont requis");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->redirectWithError("Format d'email invalide");
            }

            // Authentification
            $agent = $this->model->authenticate($email, $password);

            if ($agent) {
                $this->createUserSession($agent);
                header('Location: ../../IHM/agents/dashboard-agent.php');
                exit();
            } else {
                $this->redirectWithError("Email ou mot de passe incorrect");
            }
        }
    }

    /**
     * Crée la session utilisateur
     * @param object $agent
     */
    private function createUserSession($agent) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Régénération de l'ID de session pour la sécurité
        session_regenerate_id(true);
        
        $_SESSION['agent'] = [
            'id' => $agent->agent_id,
            'nom' => $agent->nom,
            'prenom' => $agent->prenom,
            'email' => $agent->email,
            'last_login' => time(),
            'ip' => $_SERVER['REMOTE_ADDR']
        ];
    }

    /**
     * Redirige avec un message d'erreur
     * @param string $message
     */
    private function redirectWithError($message) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['login_error'] = $message;
        header('Location: ../../IHM/agents/login_agent.php');
        exit();
    }

    /**
     * Gère la déconnexion
     */
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Efface toutes les données de session
        $_SESSION = [];
        
        // Supprime le cookie de session
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        
        // Détruit la session
        session_destroy();
        
        header('Location: ../../IHM/agents/login_agent.php');
        exit();
    }
}

// Gestion des routes
$action = $_GET['action'] ?? '';
$controller = new AgentController();

switch ($action) {
    case 'login':
        $controller->login();
        break;
    case 'logout':
        $controller->logout();
        break;
    default:
        header('Location: ../../IHM/agents/login_agent.php');
        exit();
}