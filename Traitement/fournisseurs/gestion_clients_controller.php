<?php // Traitement/Fournisseurs/Gestion-Clients-Controller.php
require_once '../../BD/ClientModel.php';

// Connexion à la base de données
$db = DBConnection::getInstance()->getConnection();

// Gérer l'ajout d'un client
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['adresse'], $_POST['mot_de_passe'])) {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $adresse = $_POST['adresse'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Validation basique (tu peux ajouter des règles supplémentaires si nécessaire)
    if (empty($nom) || empty($prenom) || empty($email) || empty($adresse) || empty($mot_de_passe)) {
        header('Location: ../../IHM/Fournisseur/ajouter-client.php?ajout=fail');
        exit;
    }

    // Créer une instance du modèle Client en passant la connexion à la base de données
    $clientModel = new ClientModel($db);
    
    // Ajouter le client dans la base de données
    $resultat = $clientModel->ajouterClient($nom, $prenom, $email, $adresse, $mot_de_passe);
    
    // Vérifier si l'ajout a réussi
    if ($resultat) {
        header('Location: ../../IHM/Fournisseur/ajouter-client.php?ajout=success');
        exit;
    } else {
        header('Location: ../../IHM/Fournisseur/ajouter-client.php?ajout=fail');
        exit;
    }
}

// Gérer la suppression d'un client
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && !isset($_POST['nom'])) {
    $id = $_POST['id'];

    // Vérification de l'ID pour s'assurer qu'il est valide
    if (!is_numeric($id) || $id <= 0) {
        echo "fail - invalid ID";
        exit;
    }

    // Ajouter un log pour vérifier l'ID reçu
    file_put_contents("debug.log", "ID reçu pour suppression : " . $id . "\n", FILE_APPEND);

    // Appeler la méthode de suppression du modèle en passant la connexion à la base de données
    $clientModel = new ClientModel($db);
    $resultat = $clientModel->supprimerClient($id);

    // Vérifier le résultat de la suppression
    if ($resultat) {
        echo "success";
    } else {
        echo "fail - DB error";
    }
} else {
    echo "fail - bad request";
}
?>
