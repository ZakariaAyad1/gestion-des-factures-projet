<?php
require_once '../../BD/ReclamationModel.php';

class ReclamationController {
    private $reclamationModel;

    public function __construct($database) {
        $this->reclamationModel = new ReclamationModel($database);
    }

    public function afficherReclamations() {
        $reclamations = $this->reclamationModel->getAllReclamations();
        require '../../IHM/clients/historique-Reclamations.php';
    }
}
?>
