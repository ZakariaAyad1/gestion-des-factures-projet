<?php include '../includes/header-agent.php'; ?>
<link rel="stylesheet" href="../../assets/css/style_agent.css">

<div class="history-container">
    <h2>Historique des Importations</h2>
    
    <div class="filters">
        <div class="form-group">
            <label for="yearFilter">Année :</label>
            <select id="yearFilter">
                <option value="">Toutes</option>
            </select>
        </div>
    </div>

    <table id="historyTable">
        <thead>
            <tr>
                <th>Client</th>
                <th>Année</th>
                <th>Consommation (kWh)</th>
                <th>Date Import</th>
            </tr>
        </thead>
        <tbody>
            <!-- Rempli dynamiquement -->
        </tbody>
    </table>

    <div class="pagination">
        <button id="prevBtn" disabled><i class="fas fa-chevron-left"></i></button>
        <span id="pageInfo">Page 1</span>
        <button id="nextBtn" disabled><i class="fas fa-chevron-right"></i></button>
    </div>
</div>

<script src="../../assets/js/agent_history.js"></script>

<?php include '../includes/footer-agent.php'; ?>