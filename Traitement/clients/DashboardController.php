<?php
require_once '../../BD/DashboardClientModel.php';

class DashboardController {
    private $model;

    public function __construct($db) {
        $this->model = new DashboardClientModel($db);
    }

    public function getDashboardData() {
        // Démarrer la session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['client_id'])) {
            header("Location: login.php");
            exit;
        }
    
        $client_id = $_SESSION['client_id'];
        
        return [
            'client_nom' => $_SESSION['client_nom'] ?? 'Client',
            'client_prenom' => $_SESSION['client_prenom'] ?? '',
            'factureStats' => $this->model->getFactureStats($client_id),
            'reclamationCount' => $this->model->getReclamationStats($client_id),
            'chartData' => $this->prepareChartData($this->model->getConsommationsMensuelles($client_id))
        ];
    }
    
    private function prepareChartData($consoMensuelle) {
        $months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
        $data = [
            'labels' => [],
            'data' => [],
            'colors' => []
        ];
    
        foreach ($consoMensuelle as $conso) {
            $data['labels'][] = $months[$conso['mois'] - 1] . ' ' . $conso['annee'];
            $data['data'][] = $conso['consommation'];
            $data['colors'][] = $this->getColorForConsommation($conso['consommation']);
        }
    
        return $data;
    }
    
    private function getColorForConsommation($value) {
        if ($value < 100) return 'rgba(75, 192, 192, 0.2)';
        if ($value < 200) return 'rgba(54, 162, 235, 0.2)';
        return 'rgba(255, 99, 132, 0.2)';
    }
}