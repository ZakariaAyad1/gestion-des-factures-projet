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
    <h1 class="title-box">👥 Ajouter un Client</h1>

    <!-- Formulaire d'ajout -->
    <form action="ajouter_client.php" method="POST">
        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" required><br><br>

        <label for="prenom">Prénom</label>
        <input type="text" id="prenom" name="prenom" required><br><br>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="adresse">Adresse</label>
        <input type="text" id="adresse" name="adresse" required><br><br>

         

        <button type="submit" class="btn">Ajouter le client</button>
    </form>
</div>

<script src="../../Assets/Js/js.js"></script>
</body>
</html>
