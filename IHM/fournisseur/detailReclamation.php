<?php
session_start(); // ✅ DÉPLACÉ tout en haut, avant tout autre code

include '../includes/header-fournisseur.php';
require_once '../../Traitement/fournisseurs/ReclamationsController.php';

$controller = new ReclamationController();
$data = $controller->getDetailData();

if ($data['should_redirect']) {
    header("Location: traitement-reclamations.php");
    exit();
}

// Récupérer les valeurs avec des fallbacks sécurisés
$reclamation = $data['reclamation'] ?? null;
$statutAffichage = $data['statutAffichage'] ?? 'Inconnu';
$clientInfo = $reclamation ? $controller->getClientInfo($reclamation['client_id']) : null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail de la Réclamation</title>
    <link rel="stylesheet" href="../../assets/css/style_reclamationdeta.css">
</head>
<body>
    <h2>📄 Détail de la Réclamation</h2>

    <?php if ($data['show_confirmation']): ?>
    <div class="confirmation-alert">
        <p>✔️ Réponse enregistrée avec succès le <?= $data['confirmation_date'] ?></p>
        <?php if ($data['has_attached_file']): ?>
        <p>📎 Fichier joint: <a href="<?= $data['file_url'] ?>" target="_blank">Voir le fichier</a></p>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if ($data['has_success']): ?>
        <div class="alert success">La réclamation a été mise à jour avec succès!</div>
    <?php elseif ($data['has_error']): ?>
        <div class="alert error"><?= htmlspecialchars($data['error_message']) ?></div>
    <?php endif; ?>

    <div class="reclamation-info">
        <p><strong>📛 Nom du Client :</strong> 
            <?= $clientInfo ? htmlspecialchars($clientInfo['nom'].' '.$clientInfo['prenom']) : 'Non spécifié' ?>
        </p>
        <p><strong>📍 Adresse :</strong> 
            <?= $clientInfo ? htmlspecialchars($clientInfo['adresse']) : 'Non spécifiée' ?>
        </p>
        <p><strong>📧 Email :</strong> 
            <?= $clientInfo ? htmlspecialchars($clientInfo['email']) : 'Non spécifié' ?>
        </p>
        <?php if ($reclamation): ?>
            <p><strong>📅 Date de création :</strong> 
                <?= htmlspecialchars(date('d/m/Y H:i', strtotime($reclamation['created_at']))) ?>
            </p>
            <p><strong>📄 Type :</strong> <?= htmlspecialchars($reclamation['type']) ?></p>
            <p><strong>📝 Description :</strong></p>
            <div class="description-box"><?= nl2br(htmlspecialchars($reclamation['description'])) ?></div>
            <p><strong>🚦 Statut :</strong> <span class="statut-badge"><?= htmlspecialchars($statutAffichage) ?></span></p>
        <?php endif; ?>
    </div>

    <?php if ($reclamation && !empty($reclamation['piece_jointe'])): ?>
    <div class="piece-jointe">
        <h3>📎 Pièce Jointe</h3>
        <p>
            <a href="../../assets/uploads/<?= htmlspecialchars($reclamation['piece_jointe']) ?>" 
               target="_blank" 
               class="download-link">
               Télécharger le fichier
            </a>
        </p>
    </div>
    <?php endif; ?>

    <?php if ($reclamation): ?>
    <div class="response-form">
        <h3>✏ Répondre à la Réclamation</h3>
        <form method="post" action="../../Traitement/fournisseurs/traiter_reponse.php">
            <input type="hidden" name="id" value="<?= $reclamation['id'] ?>">
            
            <div class="form-group">
                <label for="statut">🚦 Mettre à jour le statut :</label>
                <select id="statut" name="statut" class="form-control">
                    <option value="En cours" <?= $reclamation['statut'] === 'En cours' ? 'selected' : '' ?>>En cours</option>
                    <option value="Resolu" <?= $reclamation['statut'] === 'Resolu' ? 'selected' : '' ?>>Résolue</option>
                </select>
            </div>

            <div class="form-group">
                <label for="reponse">📝 Réponse :</label>
                <textarea id="reponse" name="reponse" class="form-control" required><?= 
                    !empty($reclamation['reponse']) ? htmlspecialchars($reclamation['reponse']) : '' 
                ?></textarea>
            </div>

            <button type="submit" class="submit-btn">✅ Envoyer la réponse</button>
        </form>
    </div>
    <?php endif; ?>
</body>
</html>
<?php include '../includes/footer-fournisseur.php'; ?>