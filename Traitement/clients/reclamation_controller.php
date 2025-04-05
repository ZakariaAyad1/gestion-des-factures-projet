<?php
require_once '../../BD/Reclamation-Model.php';

session_start();

if (!isset($_SESSION['client_id'])) {
    header("Location: ../IHM/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = [
            'client_id' => $_SESSION['client_id'],
            'type' => $_POST['type_reclamation'] === 'Autre' ? $_POST['autre_type'] : $_POST['type_reclamation'],
            'description' => $_POST['description'],
            'piece_jointe' => $_FILES['piece_jointe'] ?? null
        ];

        if (empty($data['type'])) {
            throw new Exception("Le type de réclamation est requis");
        }

        $model = new ReclamationModel();
        $reclamationId = $model->create($data);
        
        // Redirection après succès
        header("Location: ../../IHM/clients/Historique-Reclamations.php");
        exit;

    } catch (Exception $e) {
        header("Location: ../IHM/Soumettre-Reclamation.php?error=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    header("Location: ../IHM/Soumettre-Reclamation.php");
    exit;
}