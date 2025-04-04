<?php
require_once '../../BD/ConsommationAnnuelleModel.php';

session_start();
if (!isset($_SESSION['agent'])) {
    header('HTTP/1.1 403 Forbidden');
    exit('Accès non autorisé');
}

$model = new ConsommationAnnuelleModel();
$agent_id = $_SESSION['agent']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'Erreur de téléchargement']);
        exit();
    }

    $lines = file($file['tmp_name']);
    $imported = 0;
    $errors = [];

    foreach ($lines as $line) {
        $data = explode(',', trim($line));
        if (count($data) !== 4) {
            $errors[] = "Format incorrect: $line";
            continue;
        }

        list($client_id, $consommation, $annee, $date_saisie) = $data;

        if ($model->importerConsommation($client_id, $agent_id, $consommation, $annee, $date_saisie)) {
            $imported++;
        } else {
            $errors[] = "Erreur avec la ligne: $line";
        }
    }

    echo json_encode([
        'success' => true,
        'imported' => $imported,
        'errors' => $errors
    ]);
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'getHistorique') {
    $historique = $model->getHistoriqueByAgent($agent_id);
    echo json_encode($historique);
    exit();
}

header('HTTP/1.1 400 Bad Request');
exit();