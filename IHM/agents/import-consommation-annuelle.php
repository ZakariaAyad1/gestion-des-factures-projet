<?php include '../includes/header-agent.php'; ?>
<link rel="stylesheet" href="../../assets/css/style_agent.css">

<div class="import-form">
    <h2>Importation des Consommations Annuelles</h2>
    
    <div class="instructions">
        <h3>Instructions :</h3>
        <ol>
            <li>Préparer un fichier texte (.txt) avec les données</li>
            <li>Format attendu : ID_Client,ID_agent,Consommation,Année,Date_Saisie (YYYY-MM-DD)</li>
            <li>Un enregistrement par ligne</li>
        </ol>
    </div>

    <form id="importForm" enctype="multipart/form-data">
        <div class="form-group">
            <label for="fileInput">Fichier à importer :</label>
            <input type="file" id="fileInput" name="file" accept=".txt" required>
        </div>
        
        <button type="submit" class="btn-import">
            <i class="fas fa-upload"></i> Importer
        </button>
    </form>

    <div id="importResults"></div>
</div>

<script src="../../assets/js/agent_import.js"></script>

<?php include '../includes/footer-agent.php'; ?>