<?php
// IHM/login_fournisseur.php
$error = $_GET['error'] ?? null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Fournisseur</title>
    <link href="https://fonts.googleapis.com/css2?family=Asap:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6c5ce7;  /* Violet/bleu moderne */
            --secondary-color: #a29bfe; /* Violet clair */
            --error-color: #d63031;
            --border-color: #dfe6f9;
            --shadow-color: rgba(108, 92, 231, 0.1);
            --gradient-start: #f8f9fe;
            --gradient-end: #e8eafc;
        }
        body {
            background-color: #f8f9fe;
            font-family: "Asap", sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
        }
        .login-container {
            background-color: white;
            padding: 2.5rem;
            border-radius: 12px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 8px 25px var(--shadow-color);
            border-top: 5px solid var(--primary-color);
            transform: translateY(0);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(108, 92, 231, 0.15);
        }
        .login-title {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 1.8rem;
            font-weight: 600;
            font-size: 1.8rem;
            letter-spacing: 0.5px;
        }
        .form-group {
            margin-bottom: 1.8rem;
            position: relative;
        }
        .form-control {
            font-family: "Asap", sans-serif;
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            box-sizing: border-box;
            transition: all 0.3s;
            background-color: #fcfdff;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.1);
        }
        .form-control::placeholder {
            color: #b8b8b8;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 1rem;
            background-color: var(--primary-color);
            background-image: linear-gradient(to right, var(--primary-color), #7b6cf0);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            box-shadow: 0 4px 10px rgba(108, 92, 231, 0.3);
        }
        .btn:hover {
            background-image: linear-gradient(to right, #5f4fdb, var(--primary-color));
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(108, 92, 231, 0.4);
        }
        .error-message {
            color: var(--error-color);
            text-align: center;
            margin-bottom: 1.8rem;
            padding: 0.9rem;
            background-color: #ffebee;
            border-radius: 8px;
            border-left: 4px solid var(--error-color);
            font-weight: 500;
        }
        .supplier-icon {
            text-align: center;
            margin-bottom: 1.5rem;
            color: var(--primary-color);
            font-size: 3rem;
            filter: drop-shadow(0 3px 5px rgba(108, 92, 231, 0.2));
        }
        .forgot-password {
            text-align: center;
            margin-top: 1.5rem;
        }
        .forgot-password a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        .forgot-password a:hover {
            color: #5f4fdb;
            text-decoration: underline;
        }
        @media (max-width: 480px) {
            .login-container {
                padding: 2rem;
                margin: 0 1.2rem;
                border-radius: 10px;
            }
            .login-title {
                font-size: 1.6rem;
            }
            .supplier-icon {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="supplier-icon">
            <i class="fas fa-boxes"></i> <!-- Icône de colis -->
        </div>
        <h1 class="login-title">Connexion Fournisseur</h1>
        
        <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error, ENT_QUOTES) ?></div>
        <?php endif; ?>
        
        <form method="POST" action="../../Traitement/fournisseurs/LoginControllerFournisseur.php">
            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Email professionnel" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
            </div>
            <button type="submit" class="btn">Se Connecter </button>
            
            
        </form>
    </div>

    <!-- Ajout de Font Awesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>