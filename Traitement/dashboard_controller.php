<?php
require_once __DIR__ . "/../BD/DBConnection.php";

class DashboardController {
    private $db;

    public function __construct() {
        $database = new DBConnection();
        $this->db = $database->getConnection();
    }

    // 🔹 Nombre total de clients
    public function getTotalClients() {
        $query = "SELECT COUNT(*) as total FROM clients";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // 🔹 Nombre de factures impayées
    public function getFacturesImpayees() {
        $query = "SELECT COUNT(*) as total FROM factures WHERE etat = 'non_payee'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // 🔹 Nombre de réclamations en attente
    public function getReclamationsEnAttente() {
        $query = "SELECT COUNT(*) as total FROM reclamations WHERE statut = 'en_cours'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // 🔹 Graphique des consommations mensuelles (regroupe par mois)
    public function getConsommationsMensuelles() {
        $query = "SELECT mois, annee, SUM(consommation) as total_conso
                  FROM consommations_mensuelles
                  GROUP BY annee, mois
                  ORDER BY annee DESC, mois DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

      // Méthode pour récupérer toutes les données du tableau de bord
      public function getDashboardData() {
        return [
            'totalClients' => $this->getTotalClients(),
            'totalUnpaidInvoices' => $this->getFacturesImpayees(),
            'totalPendingReclamations' => $this->getReclamationsEnAttente(),
            'monthlyConsumptions' => $this->getConsommationsMensuelles()
        ];
    }
}
?>
