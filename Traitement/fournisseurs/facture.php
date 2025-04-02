
<?php

require_once '../../BD/FactureModel.php';

$factureModel = new FactureModel();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['facture_id'], $_POST['etat'])) {
    $facture_id = $_POST['facture_id'];
    $newStatus = $_POST['etat'];

    if ($factureModel->updateFactureStatus($facture_id, $newStatus)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false]);
    }
    exit;
}

$factures = $factureModel->getAllFactures();
?>
