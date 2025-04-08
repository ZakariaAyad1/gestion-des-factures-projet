<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Fournisseur</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navigation Avancée -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-gradient shadow">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold fs-3 d-flex align-items-center" href="#">
            <i class="material-icons-round me-2">bolt</i>
            <span>EnergeX Pro</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav w-100 justify-content-between">
                <div class="d-flex flex-column flex-lg-row">
                    <li class="nav-item me-lg-2 mb-2 mb-lg-0">
                        <a class="nav-link active rounded-pill px-3 d-flex align-items-center" 
                           href="Dashboard-Fournisseur.php">
                            <i class="material-icons-round me-2">dashboard</i>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                    <li class="nav-item me-lg-2 mb-2 mb-lg-0">
                        <a class="nav-link rounded-pill px-3 d-flex align-items-center" 
                           href="Gestion-Clients.php">
                            <i class="material-icons-round me-2">groups</i>
                            <span>Clients</span>
                        </a>
                    </li>
                    <li class="nav-item me-lg-2 mb-2 mb-lg-0">
                        <a class="nav-link rounded-pill px-3 d-flex align-items-center" 
                           href="Traitement-Reclamations.php">
                            <i class="material-icons-round me-2">report</i>
                            <span>Réclamations</span>
                        </a>
                    </li>
                    <li class="nav-item me-lg-2 mb-2 mb-lg-0">
                        <a class="nav-link rounded-pill px-3 d-flex align-items-center" 
                           href="Gestion-Consommations.php">
                            <i class="material-icons-round me-2">show_chart</i>
                            <span>Consommations</span>
                        </a>
                    </li>
                </div>
                
                <div class="d-flex flex-column flex-lg-row">
                    <li class="nav-item me-lg-2 mb-2 mb-lg-0">
                        <a class="nav-link rounded-pill px-3 d-flex align-items-center" 
                           href="Factures-Fournisseur.php">
                            <i class="material-icons-round me-2">receipt_long</i>
                            <span>Factures</span>
                        </a>
                    </li>
                    <li class="nav-item me-lg-2 mb-2 mb-lg-0">
                        <a class="nav-link rounded-pill px-3 d-flex align-items-center" 
                           href="Historique-Factures.php">
                            <i class="material-icons-round me-2">history</i>
                            <span>Historique</span>
                        </a>
                    </li>
                    <li class="nav-item me-lg-2 mb-2 mb-lg-0">
                        <a class="nav-link rounded-pill px-3 d-flex align-items-center" 
                           href="Rapport-Anomalies.php">
                            <i class="material-icons-round me-2">warning</i>
                            <span>Anomalies</span>
                        </a>
                    </li>
                    <li class="nav-item me-lg-2 mb-2 mb-lg-0 position-relative">
                        <a class="nav-link rounded-pill px-3 d-flex align-items-center" 
                           href="verification_consommation.php">
                            <i class="material-icons-round me-2">notifications</i>
                            <span>Vérification des Consommations</span>
                            
                        </a>
                    </li>
                </div>
                
                <li class="nav-item">
                    <a class="nav-link rounded-pill px-3 bg-danger bg-opacity-10 text-danger d-flex align-items-center" 
                       href="logout.php">
                        <i class="material-icons-round me-2">logout</i>
                        <span>Déconnexion</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Contenu Principal -->
<main class="container-fluid my-4">
    <div class="alert alert-info border-start border-5 border-primary">
        <div class="d-flex align-items-center">
            <i class="material-icons-round me-3 fs-3">info</i>
            <div>
                <h5 class="alert-heading mb-1">Bienvenue dans votre espace fournisseur</h5>
                <p class="mb-0">Gérez efficacement vos clients, factures et consommations.</p>
            </div>
        </div>
    </div>
</main>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>