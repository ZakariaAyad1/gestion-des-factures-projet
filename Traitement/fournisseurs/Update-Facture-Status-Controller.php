<?php
require_once '../../BD/FactureModel.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données envoyées au format JSON
    $input = json_decode(file_get_contents('php://input'), true);
    $facture_id = $input['facture_id'] ?? null;
    $newStatus = $input['new_status'] ?? null;

    if (!$facture_id || !$newStatus) {
        echo json_encode(['success' => false, 'message' => 'Données manquantes']);
        exit;
    }

    $model = new FactureModel();
    $success = $model->updateFactureStatus($facture_id, $newStatus);
    
    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Mise à jour échouée']);
    }
}
?>
