<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['agent'])) {
    header('Location: ../agents/login_agent.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Agent</title>
    <link rel="stylesheet" href="../../assets/css/header_agent.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<header class="agent-header">
    <div class="header-main">
    <div class="logo">
            <img src="../../assets/images/image.png" alt="Logo Gestion Factures">
        </div>
       

    <nav class="agent-nav">
        <ul>
            <li>
                <a href="dashboard-agent.php" class="<?= basename($_SERVER['PHP_SELF']) === 'dashboard-agent.php' ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="import-consommation-annuelle.php" class="<?= basename($_SERVER['PHP_SELF']) === 'import-consommation-annuelle.php' ? 'active' : '' ?>">
                    <i class="fas fa-file-import"></i> Importer
                </a>
            </li>
            <li>
                <a href="historique-consommations.php" class="<?= basename($_SERVER['PHP_SELF']) === 'historique-consommations.php' ? 'active' : '' ?>">
                    <i class="fas fa-history"></i> Historique
                </a>
            </li>
            <li>
                <a href="../../Traitement/agents/agent_controller.php?action=logout" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </li>
        </ul>
    </nav>
</header>
<div class="user-info-container">
    <div class="user-info">
        <span class="user-name">
            <i class="fas fa-user-circle"></i>
            <?= htmlspecialchars($_SESSION['agent']['prenom'] . ' ' . htmlspecialchars($_SESSION['agent']['nom'])) ?>
        </span>
    </div>
</div>

<main class="container">
   
    
  
