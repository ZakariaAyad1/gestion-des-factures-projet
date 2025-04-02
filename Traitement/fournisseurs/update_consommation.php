<?php
require_once '../../BD/ConsommationMensuelleModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["consommation_id"])) {
    $model = new ConsommationMensuelleModel();
    $consommation_id = $_POST['consommation_id'];

    // Préparer les données à mettre à jour
    $data = [
        'mois' => $_POST['mois'],
        'annee' => $_POST['annee'],
        'consommation' => $_POST['consommation']
    ];

    // Vérifier si une nouvelle photo a été téléchargée
    if (!empty($_FILES["photo_compteur"]["name"])) {
        $uploadDir = "../../assets/uploads/Photo_compteur/";
        $fileName = time() . "_" . basename($_FILES["photo_compteur"]["name"]);
        $filePath = $uploadDir . $fileName;

        // Déplacer le fichier uploadé vers le répertoire cible
        if (move_uploaded_file($_FILES["photo_compteur"]["tmp_name"], $filePath)) {
            $data['photo_compteur'] = $fileName; // Ajouter le nouveau fichier à la mise à jour
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors du téléversement de l\'image.']);
            exit;
        }
    }

    // Effectuer la mise à jour dans la base de données
    $success = $model->updateConsommation($consommation_id, $data);
    echo json_encode(['success' => $success]);
}
?>
