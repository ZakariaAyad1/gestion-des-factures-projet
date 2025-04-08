<?php
require_once __DIR__.'/../../BD/VerificationConsommationModel.php';

class VerificationController {
    private $model;

    public function __construct() {
        $this->model = new VerificationConsommationModel();
    }

    public function verifierAnnuelle($annee) {
        $donnees = $this->model->comparerConsommationsAnnuelle($annee);
        $rapport = [
            'annee' => $annee,
            'clients' => [],
            'total_anomalies' => 0
        ];

        foreach($donnees as $client) {
            $estAnomalie = $client['difference'] > 50;
            
            if($estAnomalie) {
                $rapport['total_anomalies']++;
                $this->model->marquerStatutValidation(
                    $client['client_id'],
                    $annee,
                    'ANOMALIE'
                );
            } else {
                $this->model->marquerStatutValidation(
                    $client['client_id'],
                    $annee,
                    'VALIDE'
                );
            }

            $rapport['clients'][] = [
                'info' => $client,
                'anomalie' => $estAnomalie
            ];
        }
        
        return $rapport;
    }
    
}
?>