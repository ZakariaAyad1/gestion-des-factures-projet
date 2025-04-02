<?php
require_once '../../BD/ConsommationMensuelleModel.php';

header('Content-Type: application/json');

$ConsommationMensuelleModel = new ConsommationMensuelleModel();
$consommations = $ConsommationMensuelleModel->getConsommations();

echo json_encode($consommations);







