<?php
require_once'../../BD/Facture-Model.php';

session_start();

// Récupérer l'ID du client connecté depuis la session
$clientId = $_SESSION['client_id'] ?? null;

// Vérifiez si l'ID du client est défini dans la session (il faut que l'utilisateur soit connecté)
if ($clientId === null) {
    // Rediriger vers la page de connexion si le client n'est pas connecté
    header('Location: login.php');
    exit();
}

$factureModel = new FactureModel();
$factures = $factureModel->getFacturesByClient($clientId);

// Si vous avez des erreurs ou un message à afficher
if (!$factures) {
    echo "Aucune facture disponible";
}