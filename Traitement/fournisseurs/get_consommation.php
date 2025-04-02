<?php
require_once '../../BD/ConsommationMensuelleModel.php';
header('Content-Type: application/json');
if (!isset($_GET['consommation_id'])) {
    echo json_encode(["error" => "ID non fourni"]);
    exit;
}
if (isset($_GET['consommation_id'])) {
    $model = new ConsommationMensuelleModel();
    $consommation = $model->getConsommationById($_GET['consommation_id']);
    
    if (!$consommation) {
        echo json_encode(["error" => "Consommation introuvable"]);
        exit;
    }
    
    echo json_encode($consommation);
}
?>
