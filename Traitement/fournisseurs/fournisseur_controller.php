<?php
 


include '../../BD/Client-Model.php';
header('Content-Type: application/json');
// Créer une instance de ClientModel
$clientModel = new ClientModel();

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $adresse = $_POST['adresse'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Ajouter le client dans la base de données
    $isAdded = $clientModel->addClient($nom, $prenom, $email, $adresse, $mot_de_passe);

    if ($isAdded) {
        // Si l'ajout est réussi, rediriger vers la gestion des clients avec un message de succès
        echo json_encode(['status' => 'success']);
    } else {
        // Sinon, renvoyer une erreur
        echo json_encode(['status' => 'error', 'message' => 'Erreur BDD']);

    }
}
?>

