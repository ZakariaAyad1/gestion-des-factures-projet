<?php

require_once '../../BD/FactureModel.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données JSON envoyées
    $input = json_decode(file_get_contents('php://input'), true);
    $client_id = $input['client_id'];
    $consommation_id = $input['consommation_id'];

    // Créer une instance du modèle Facture
    $factureModel = new FactureModel();

    // Préparer les données pour l'insertion
    // On insère uniquement client_id et consommation_id ; les triggers se chargeront du calcul
    $data = [
        'client_id' => $client_id,
        'consommation_id' => $consommation_id,
    ];

    // Appeler la méthode d'insertion du modèle
    $result = $factureModel->insertFacture($data);

    // Retourner une réponse JSON
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Insertion échouée']);
    }
}
?>
