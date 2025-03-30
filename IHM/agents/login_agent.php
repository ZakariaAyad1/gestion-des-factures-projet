<?php
// Démarrage de session pour les messages d'erreur
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirection si déjà connecté
if (isset($_SESSION['agent'])) {
    header('Location: dashboard-agent.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Agent</title>
    <link rel="stylesheet" href="../../assets/css/style_agent.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                
                <h1>Connexion Agent</h1>
            </div>

            <?php if (isset($_SESSION['login_error'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($_SESSION['login_error']) ?>
                </div>
                <?php unset($_SESSION['login_error']); ?>
            <?php endif; ?>

            <form action="../../Traitement/agents/agent_controller.php?action=login" method="post" class="login-form">
                <div class="form-group">
                    <label for="email">Adresse Email</label>
                    <input type="email" id="email" name="email" 
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" 
                           required
                           class="form-control">
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" 
                           required
                           class="form-control">
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i> Se connecter
                </button>
            </form>
        </div>
    </div>

    <script src="../../assets/js/agent_auth.js"></script>
</body>
</html>