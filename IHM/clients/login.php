<?php
// IHM/login.php
$error = $_GET['error'] ?? null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Client</title>
    <link href="https://fonts.googleapis.com/css2?family=Asap:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4dabf7;
            --error-color: #e74c3c;
            --border-color: #cce0ff;
            --shadow-color: rgba(0, 98, 204, 0.1);
        }
        body {
            background-color: #e6f2ff;
            font-family: "Asap", sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            background-color: white;
            padding: 2.5rem;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 12px var(--shadow-color);
        }
        .login-title {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-control {
            font-family: "Asap", sans-serif;
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            font-size: 1rem;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        .btn {
            display: block;
            width: 100%;
            padding: 0.75rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #3a8bd6;
        }
        .error-message {
            color: var(--error-color);
            text-align: center;
            margin-bottom: 1rem;
            padding: 0.5rem;
            background-color: #fdeded;
            border-radius: 5px;
        }
        @media (max-width: 480px) {
            .login-container {
                padding: 1.5rem;
                margin: 0 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1 class="login-title">Connexion Client</h1>
        
        <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error, ENT_QUOTES) ?></div>
        <?php endif; ?>
        
        <form method="POST" action="../../Traitement/clients/LoginController.php">
            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
            </div>
            <button type="submit" class="btn">Se connecter</button>
        </form>
    </div>
</body>
</html>