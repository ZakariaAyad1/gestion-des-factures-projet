<?php include '../includes/header-agent.php'; ?>
<link rel="stylesheet" href="../../assets/css/style_agent.css">

<div class="dashboard">
    <h2>Tableau de Bord</h2>
    
    <div class="stats">
        <div class="stat-card">
            <h3>Importations ce mois</h3>
            <p id="month-imports">0</p>
        </div>
        <div class="stat-card">
            <h3>Dernière année</h3>
            <p id="last-year">-</p>
        </div>
    </div>

    <div class="actions">
        <a href="import-consommation-annuelle.php" class="btn primary">
            <i class="fas fa-file-import"></i> Nouvel Import
        </a>
        <a href="historique-consommations.php" class="btn secondary">
            <i class="fas fa-history"></i> Historique
        </a>
    </div>
</div>

<?php include '../includes/footer-agent.php'; ?>

<script src="../../assets/js/agent_dashboard.js"></script>
