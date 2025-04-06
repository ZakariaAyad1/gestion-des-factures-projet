
<?php
// ajouter-client.php
/* 
// Vérifier le paramètre dans l'URL et afficher un message
if (isset($_GET['ajout'])) {
    if ($_GET['ajout'] == 'success') {
        echo "<p style='color: green;'>Client ajouté avec succès !</p>";
    } elseif ($_GET['ajout'] == 'fail') {
        echo "<p style='color: red;'>Erreur lors de l'ajout du client.</p>";
    }
} */
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Client</title>
    <link rel="stylesheet" href="../../Assets/Css/test.css">
</head>
<body>

<div class="container">
    <div class="title-box">
        <h1>👥 Ajouter un Client</h1>
    </div>

    <!-- Affichage du message de succès ou d'échec -->
    <?php if (isset($_GET['ajout'])): ?>
        <?php if ($_GET['ajout'] == 'success'): ?>
            <p style="color: green;">Client ajouté avec succès !!</p>
        <?php elseif ($_GET['ajout'] == 'fail'): ?>
            <p style="color: red;">L'ajout du client a échoué. Veuillez réessayer.</p>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Formulaire d'ajout -->
    <form action="../../Traitement/Fournisseurs/gestion_clients_controller.php" method="POST">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" required><br>

        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" id="prenom" required><br>

        <label for="email">Email :</label>
        <input type="email" name="email" id="email" required><br>

        <label for="adresse">Adresse :</label>
        <input type="text" name="adresse" id="adresse" required><br>

        <label for="mot_de_passe">Mot de passe :</label>
        <input type="password" name="mot_de_passe" id="mot_de_passe" required><br>

        <button type="submit">Ajouter Client</button>
    </form>
</div>

<script src="../../Assets/Js/js.js"></script>
</body>
</html>

