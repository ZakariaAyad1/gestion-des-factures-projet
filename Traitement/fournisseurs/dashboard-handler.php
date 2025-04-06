<?php 
// Inclure le fichier de connexion à la base de données
include '../../BD/DBConnection.php';  // Ajustez le chemin d'inclusion si nécessaire

// Récupérer la connexion
$dbConnection = DBConnection::getInstance()->getConnection();

// Vérifiez si la connexion est correctement établie
if ($dbConnection === null) {
    die("Erreur de connexion à la base de données.");
}

// Exemple de requête pour obtenir le nombre total de clients
// Exemple de requête pour obtenir le nombre total de clients
$query = "SELECT COUNT(*) AS totalClients FROM clients";
$result = $dbConnection->query($query);

if ($result) {
    $data = $result->fetch(PDO::FETCH_ASSOC);
    $totalClients = $data['totalClients']; 
} else {
    $totalClients = 0; // Assurer une valeur par défaut en cas d'erreur
}


// Récupérer le total des factures impayées
$queryUnpaidInvoices = "SELECT COUNT(*) AS totalUnpaid FROM factures WHERE etat = 'non_payee'";
$resultUnpaidInvoices = $dbConnection->query($queryUnpaidInvoices);

// Vérifiez si la requête a réussi
if ($resultUnpaidInvoices) {
    // Utiliser fetch()  
    $dataUnpaidInvoices = $resultUnpaidInvoices->fetch(PDO::FETCH_ASSOC);
    $totalUnpaidInvoices = $dataUnpaidInvoices['totalUnpaid'];
} else {
    die("Erreur lors de l'exécution de la requête pour les factures impayées.");
}

// Récupérer le total des réclamations en attente
$queryPendingReclamations = "SELECT COUNT(*) AS totalPending FROM reclamations WHERE statut = 'En cours'";
$resultPendingReclamations = $dbConnection->query($queryPendingReclamations);

// Vérifiez si la requête a réussi
if ($resultPendingReclamations) {
    // Utiliser fetch()  
    $dataPendingReclamations = $resultPendingReclamations->fetch(PDO::FETCH_ASSOC);
    $totalPendingReclamations = $dataPendingReclamations['totalPending'];
} else {
    die("Erreur lors de l'exécution de la requête pour les réclamations en attente.");
}

// Requête pour récupérer la consommation totale par mois et année
$queryConsumption = "SELECT mois, annee, SUM(consommation) AS total 
                     FROM consommations_mensuelles 
                     GROUP BY annee, mois 
                     ORDER BY annee DESC, mois ASC";

$resultConsumption = $dbConnection->query($queryConsumption);
$months = [];
$monthlyConsumptions = [];

if ($resultConsumption) {
    while ($row = $resultConsumption->fetch(PDO::FETCH_ASSOC)) {
        // Transformer le mois en nom (Janvier, Février, etc.)
        $months[] = date('F', mktime(0, 0, 0, $row['mois'], 1)) . " " . $row['annee'];
        $monthlyConsumptions[] = $row['total'];
    }
} else {
    $months = [];
    $monthlyConsumptions = [];
}


?>

