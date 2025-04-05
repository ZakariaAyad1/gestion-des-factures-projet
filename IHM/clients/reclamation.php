<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soumettre une réclamation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
   <link rel="stylesheet" href="../../assets/css/style3.css">
</head>
<body>

<div class="sidebar">
    <div class="logo">
        <svg width="100" height="40" viewBox="0 0 100 40">
            <path d="M12,15 L30,15 L24,27 L12,27 Z" fill="#4270F4" />
            <text x="38" y="27" fill="#262A39" font-size="20" font-weight="bold">Client</text>
        </svg>
    </div>
    <div class="nav-menu">
        <a href="dashboard-client.php" class="nav-item active">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
        <a href="factures.php" class="nav-item">
          <i class="fas fa-file-invoice-dollar"></i>
          <span>Factures</span>
        </a>
        <a href="Saisie-Consommation.php" class="nav-item">
        <i class="fas fa-tachometer-alt"></i>
          <span>Saisie Consommation</span>
        </a>
        <a href="Historique-Reclamations.php" class="nav-item">
          <i class="fas fa-exclamation-circle"></i>
          <span>Réclamations</span>
        </a>
        <a href="#" class="nav-item" style="margin-top: auto;">
          <i class="fas fa-sign-out-alt"></i>
          <span>Déconnexion</span>
        </a>
      </div>
</div>
    <div class="login-box">
        <i class="fas fa-times close-icon" onclick="window.location.href='Historique-Reclamations.php';"></i>
        <h2>Soumettre une réclamation</h2>
        <form action="../../Traitement/clients/reclamation_controller.php" method="POST" enctype="multipart/form-data">
            <div class="user-box">
                <label for="type_reclamation">Type de réclamation</label>
                <select id="type_reclamation" name="type_reclamation" required>
                    <option value="">Sélectionnez un type</option>
                    <option value="Fuite externe">Fuite externe</option>
                    <option value="Fuite interne">Fuite interne</option>
                    <option value="Facture erronée">Facture erronée</option>
                    <option value="Autre">Autre</option>
                </select>
            </div>

            <div class="user-box" id="autre_type_container" style="display: none;">
                <label for="autre_type">Précisez le type de réclamation</label>
                <input type="text" id="autre_type" name="autre_type">
            </div>

            <div class="user-box">
                <label for="description">Description de la réclamation</label>
                <textarea id="description" name="description" rows="5" required></textarea>
            </div>

            <div class="user-box">
                <label for="piece_jointe">Pièce jointe (optionnelle)</label>
                <input type="file" id="piece_jointe" name="piece_jointe" accept="image/*,application/pdf">
            </div>

            <button type="submit">
                Soumettre
            </button>
        </form>
    </div>

    <script>
   
    </script>
    <script src="../../assets/js/script2.js"></script>
</body>
</html>
