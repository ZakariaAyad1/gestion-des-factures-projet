<?php
session_start();
include '../includes/header-fournisseur.php';
require_once '../../Traitement/fournisseurs/ReclamationsController.php';

$controller = new ReclamationController();
$data = $controller->getDetailData();

if ($data['should_redirect']) {
    header("Location: traitement-reclamations.php");
    exit();
}

$reclamation = $data['reclamation'] ?? null;
$statutAffichage = $data['statutAffichage'] ?? 'Inconnu';
$clientInfo = $reclamation ? $controller->getClientInfo($reclamation['client_id']) : null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Détail de la Réclamation</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h2 class="display-4 mb-4 animate__animated animate__fadeIn">
            <i class="bi bi-file-text"></i> Détail de la Réclamation
        </h2>

        <?php if ($data['show_confirmation']): ?>
        <div class="alert alert-success animate__animated animate__fadeInDown d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div>
                Réponse enregistrée avec succès le <?= $data['confirmation_date'] ?>
                <?php if ($data['has_attached_file']): ?>
                <div class="mt-2">
                    <a href="<?= $data['file_url'] ?>" class="btn btn-sm btn-outline-success" target="_blank">
                        <i class="bi bi-paperclip"></i> Voir le fichier joint
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4 animate__animated animate__fadeIn">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informations Client</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><i class="bi bi-person-fill"></i> <strong>Client:</strong> 
                                    <?= $clientInfo ? htmlspecialchars($clientInfo['nom'].' '.$clientInfo['prenom']) : 'Non spécifié' ?>
                                </p>
                                <p><i class="bi bi-geo-alt-fill"></i> <strong>Adresse:</strong> 
                                    <?= $clientInfo ? htmlspecialchars($clientInfo['adresse']) : 'Non spécifiée' ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><i class="bi bi-envelope-fill"></i> <strong>Email:</strong> 
                                    <?= $clientInfo ? htmlspecialchars($clientInfo['email']) : 'Non spécifié' ?>
                                </p>
                                <?php if ($reclamation): ?>
                                <p><i class="bi bi-calendar-event"></i> <strong>Date:</strong> 
                                    <?= htmlspecialchars(date('d/m/Y H:i', strtotime($reclamation['created_at']))) ?>
                                </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($reclamation): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-chat-left-text"></i> Détails de la Réclamation</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6><i class="bi bi-tag"></i> Type:</h6>
                            <span class="badge bg-secondary"><?= htmlspecialchars($reclamation['type']) ?></span>
                        </div>
                        <div class="mb-3">
                            <h6><i class="bi bi-text-paragraph"></i> Description:</h6>
                            <div class="p-3 bg-light rounded">
                                <?= nl2br(htmlspecialchars($reclamation['description'])) ?>
                            </div>
                        </div>
                        <div>
                            <h6><i class="bi bi-traffic-light"></i> Statut:</h6>
                            <span class="badge bg-<?= $reclamation['statut'] === 'Resolu' ? 'success' : 'warning' ?>">
                                <?= htmlspecialchars($statutAffichage) ?>
                            </span>
                        </div>
                    </div>
                </div>

                <?php if (!empty($reclamation['piece_jointe'])): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="bi bi-paperclip"></i> Pièce Jointe</h5>
                    </div>
                    <div class="card-body">
                        <a href="../../assets/uploads/<?= htmlspecialchars($reclamation['piece_jointe']) ?>" 
                           class="btn btn-outline-primary" target="_blank">
                            <i class="bi bi-download"></i> Télécharger le fichier
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-reply"></i> Répondre à la Réclamation</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="../../Traitement/fournisseurs/traiter_reponse.php">
                            <input type="hidden" name="id" value="<?= $reclamation['id'] ?>">
                            
                            <div class="mb-3">
                                <label for="statut" class="form-label">
                                    <i class="bi bi-toggle-on"></i> Mettre à jour le statut
                                </label>
                                <select id="statut" name="statut" class="form-select">
                                    <option value="En cours" <?= $reclamation['statut'] === 'En cours' ? 'selected' : '' ?>>
                                        En cours
                                    </option>
                                    <option value="Resolu" <?= $reclamation['statut'] === 'Resolu' ? 'selected' : '' ?>>
                                        Résolue
                                    </option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="reponse" class="form-label">
                                    <i class="bi bi-pencil-square"></i> Réponse
                                </label>
                                <textarea id="reponse" name="reponse" class="form-control" 
                                    rows="5" required><?= !empty($reclamation['reponse']) ? 
                                    htmlspecialchars($reclamation['reponse']) : '' ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-send"></i> Envoyer la réponse
                            </button>
                        </form>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar avec des informations complémentaires -->
            <div class="col-lg-4">
                <div class="position-sticky" style="top: 2rem;">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0"><i class="bi bi-clock-history"></i> Historique</h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <!-- Vous pouvez ajouter ici un historique des actions -->
                                <div class="alert alert-info">
                                    <small><i class="bi bi-info-circle"></i> Suivez l'évolution de la réclamation ici</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS et Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php include '../includes/footer-fournisseur.php'; ?>