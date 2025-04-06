<?php
include '../Includes/Header-Fournisseur.php';
include '../../BD/DBConnection.php';

if (isset($_GET['id'])) {
    $clientId = $_GET['id'];
    $db = DBConnection::getInstance();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("SELECT * FROM clients WHERE client_id = :client_id");
    $stmt->bindParam(':client_id', $clientId, PDO::PARAM_INT);
    $stmt->execute();
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($client) {
        $nom = $client['nom'];
        $prenom = $client['prenom'];
        $adresse = $client['adresse'];
    } else {
        header("Location: Gestion-Clients.php");
        exit;
    }
} else {
    header("Location: Gestion-Clients.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Client</title>
    <link rel="stylesheet" href="../../Assets/Css/test.css">
</head>
<body>

<div class="container">
    <h1>Modifier le Client</h1>

    <?php if (isset($_GET['success']) && $_GET['success'] === 'true'): ?>
        <div class="success-message">
            ✅ Modifications enregistrées avec succès !
        </div>
    <?php endif; ?>

    <form id="form-modifier-client" method="POST">
    <input type="hidden" id="client_id" name="client_id" value="<?php echo $clientId; ?>">
    <label for="nom">Nom :</label>
    <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($nom); ?>" required><br>

    <label for="prenom">Prénom :</label>
    <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($prenom); ?>" required><br>

    <label for="adresse">Adresse :</label>
    <input type="text" id="adresse" name="adresse" value="<?php echo htmlspecialchars($adresse); ?>" required><br>

    <button type="submit">Sauvegarder</button>
</form>

<!-- Message de succès dynamique -->
<div id="message-succes" style="display:none;"></div>

</div>

<script>
document.getElementById('form-modifier-client').addEventListener('submit', function(e) {
    e.preventDefault();  // Empêcher le formulaire de soumettre de manière traditionnelle.

    var formData = new FormData(this);

    fetch('../../Traitement/Clients/Modifier-Client-Action.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        var message = document.getElementById('message-succes');
        if (data.status === 'success') {
            message.style.display = 'block';
            message.style.color = 'green';
            message.innerText = '✅ Modifications enregistrées avec succès !';

            // Optionnel : rediriger après 3 secondes ou autre comportement
            setTimeout(() => {
                window.location.href = 'Gestion-Clients.php';  // Rediriger vers la page de gestion des clients après succès
            }, 3000);
        } else {
            message.style.display = 'block';
            message.style.color = 'red';
            message.innerText = '❌ Une erreur est survenue.';
        }
    })
    .catch(err => {
        console.error(err);
        var message = document.getElementById('message-succes');
        message.style.display = 'block';
        message.style.color = 'red';
        message.innerText = '❌ Une erreur réseau est survenue.';
    });
});
</script>

</body>
</html>
