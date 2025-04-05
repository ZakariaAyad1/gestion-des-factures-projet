<?php
require_once 'BD/DBConnection.php';
require_once 'Traitement/clients/DashboardController.php';

// Initialisation
$db = DBConnection::getInstance()->getConnection();
$controller = new DashboardController($db);

// Affichage
$controller->showDashboard();
?>