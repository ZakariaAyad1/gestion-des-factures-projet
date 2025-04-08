<?php
require_once __DIR__.'/../BD/DBConnection.php';
require_once __DIR__.'/../BD/VerificationConsommationModel.php';
require_once __DIR__.'/../BD/NotificationModel.php';

// Configuration
$annee = date('Y') - 1; // Vérifie l'année précédente
$logFile = __DIR__.'/logs/verification_annuelle_'.$annee.'.log';

// Initialisation
$verifModel = new VerificationConsommationModel();
$notificationModel = new NotificationModel();

try {
    // 1. Comparaison des consommations
    $comparaisons = $verifModel->comparerConsommationsAnnuelle($annee);
    $nbAnomalies = 0;

    foreach($comparaisons as $comparaison) {
        $statut = $comparaison['difference'] <= 50 ? 'Validé' : 'En_Reclamation';
        
        // 2. Mise à jour du statut
        $verifModel->mettreAJourStatut(
            $comparaison['client_id'],
            $annee,
            $statut,
            $comparaison['difference']
        );

        // 3. Traitement des anomalies
        if($statut === 'En_Reclamation') {
            $nbAnomalies++;
            
            // Notification
            $message = sprintf(
                "Écart de consommation détecté: %d kWh (Votre saisie: %d kWh, Relevé agent: %d kWh)",
                $comparaison['difference'],
                $comparaison['conso_client'],
                $comparaison['conso_agent']
            );
            
            $notificationModel->create(
                $comparaison['client_id'],
                'Anomalie Consommation',
                $message,
                'warning'
            );
        }
    }

    // Log final
    file_put_contents($logFile, 
        "[".date('Y-m-d H:i:s')."] Vérification terminée - $nbAnomalies anomalies\n", 
        FILE_APPEND);

} catch(Exception $e) {
    file_put_contents($logFile, 
        "[".date('Y-m-d H:i:s')."] ERREUR: ".$e->getMessage()."\n", 
        FILE_APPEND);
}
?>