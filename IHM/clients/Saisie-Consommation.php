<?php
// Démarrer la session (doit être la première chose à faire)
session_start();

// Inclusion du contrôleur
require_once '../../Traitement/Clients/Consommation-Controller.php';

// Instanciation du contrôleur
$consommationController = new ConsommationController();

// Récupération de l'ID du client (à adapter selon votre système d'authentification)
// Pour la démonstration, on utilise un ID fixe
$client_id = isset($_SESSION['client_id']) ? $_SESSION['client_id'] : 1;

// Génération d'un token CSRF
// Utiliser directement la méthode de la classe ou créer un token manuellement
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// Récupération de la dernière consommation
$dernierReleve = $consommationController->getDerniereConsommation($client_id);
$consommationPrecedente = $dernierReleve ? $dernierReleve['valeur'] : 0;
$datePrecedente = $dernierReleve ? $dernierReleve['date_releve_formattee'] : date('d/m/Y', strtotime('-1 month'));

// Traitement du formulaire
$message = '';
$alertClass = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message = 'Erreur de sécurité. Veuillez réessayer.';
        $alertClass = 'error';
    } else {
        $consommation = isset($_POST['consommation']) ? filter_var($_POST['consommation'], FILTER_VALIDATE_FLOAT) : 0;

        // Traitement de la saisie
        $result = $consommationController->saisirConsommation(
            $client_id,
            $consommation,
            $_FILES['photo_compteur'] ?? null
        );

        $message = $result['message'];
        $alertClass = $result['class'];

        // Régénérer un nouveau token CSRF après soumission réussie
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $csrf_token = $_SESSION['csrf_token'];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saisie de la Consommation - Gestion des Factures d'Électricité</title>
    <link rel="stylesheet" href="../../Assets/Css/style.css">
    <script src="../../Assets/Js/common.js"></script>
    <script src="../../Assets/Js/saisie-consommation.js"></script>
    <link rel="stylesheet" href="../../assets/css/style4.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
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
        <a href="dashboard-client.php" class="nav-item">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
        <a href="factures.php" class="nav-item">
          <i class="fas fa-file-invoice-dollar"></i>
          <span>Factures</span>
        </a>
        <a href="Saisie-Consommation.php" class="nav-item active">
          <i class="fas fa-tachometer"></i>
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

    <div class="container">
        <h2 class="form-title">Saisie de votre consommation mensuelle</h2>

        <div id="message" class="message <?php echo $alertClass; ?>" <?php echo empty($message) ? 'style="display: none;"' : ''; ?>>
            <?php echo $message; ?>
        </div>

        <form id="consommationForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

            <div class="form-group">
                <label for="consommation"><strong>Consommation (kWh)</strong></label>
                <input type="number" id="consommation" name="consommation" step="0.01" min="0" required>
                <small>Veuillez saisir votre consommation en kilowattheures.</small>
            </div>

            <div class="form-group">
                <label><strong>Consommation précédente</strong></label>
                <div>
                    <span id="previous-consommation"><?php echo $consommationPrecedente; ?> kWh</span>
                    <span id="previous-date">Date: <?php echo $datePrecedente; ?></span>
                </div>
            </div>

            <div class="form-group">
                <label for="photo_compteur"><strong>Photo du compteur</strong></label>
                <input type="file" id="photo_compteur" name="photo_compteur" accept="image/*" required>
                <small>Prenez une photo claire de votre compteur montrant les chiffres.</small>
                <div id="preview" style="display: none;">
                    <img id="imagePreview" src="#" alt="Aperçu de l'image">
                </div>
            </div>

            <div class="form-group">
                <button type="submit">Envoyer</button>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Affichage de l'aperçu de l'image
        document.getElementById('photo_compteur').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                    document.getElementById('preview').style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });

        // Validation de la consommation
        document.getElementById('consommation').addEventListener('change', function() {
            const consommation = parseFloat(this.value);
            const consommationPrecedente = <?php echo $consommationPrecedente ?: 0; ?>;

            if (consommation > 0 && consommationPrecedente > 0) {
                if ((consommation - consommationPrecedente) > 50) {
                    // Affichage d'une alerte si la consommation dépasse le seuil
                    if (!document.getElementById('alerteSeuil')) {
                        const alerte = document.createElement('div');
                        alerte.id = 'alerteSeuil';
                        alerte.className = 'message warning';
                        alerte.innerHTML = 'Attention : La consommation saisie dépasse de plus de 50 kWh votre dernier relevé. ' +
                            'Veuillez vérifier votre saisie ou confirmer cette augmentation inhabituelle.';
                        this.parentNode.appendChild(alerte);
                    }
                } else {
                    // Suppression de l'alerte si elle existe
                    const alerteExistante = document.getElementById('alerteSeuil');
                    if (alerteExistante) {
                        alerteExistante.remove();
                    }
                }
            }
        });

        // Affichage du message
        const messageElement = document.getElementById('message');
        if (messageElement.textContent.trim() !== '') {
            messageElement.style.display = 'block';
        }
    });
    </script>
</body>
</html>