<?php
require_once 'DBConnection.php';

class NotificationModel {
    private $db;

    public function __construct() {
        $this->db = DBConnection::getInstance();
    }

    /**
     * Crée une nouvelle notification pour un client
     * @param int $client_id ID du client
     * @param string $titre Titre de la notification
     * @param string $message Contenu de la notification
     * @param string $type Type de notification (info, warning, danger, success)
     * @return bool True si la création a réussi
     */
    public function create($client_id, $titre, $message, $type = 'info') {
        $sql = "INSERT INTO notifications 
                (client_id, titre, message, type, date_creation, lue) 
                VALUES (?, ?, ?, ?, NOW(), 0)";
        
        return $this->db->update($sql, [
            $client_id,
            $titre,
            $message,
            $type
        ]) > 0;
    }

    /**
     * Récupère les notifications non lues d'un client
     * @param int $client_id ID du client
     * @return array Liste des notifications
     */
    public function getUnread($client_id) {
        $sql = "SELECT * FROM notifications 
                WHERE client_id = ? AND lue = 0 
                ORDER BY date_creation DESC";
        return $this->db->fetchAll($sql, [$client_id]);
    }

    /**
     * Marque une notification comme lue
     * @param int $notification_id ID de la notification
     * @return bool True si la mise à jour a réussi
     */
    public function markAsRead($notification_id) {
        $sql = "UPDATE notifications SET lue = 1 WHERE id = ?";
        return $this->db->update($sql, [$notification_id]) > 0;
    }

    /**
     * Supprime les notifications anciennes (plus de 30 jours)
     * @return int Nombre de notifications supprimées
     */
    public function cleanOldNotifications() {
        $sql = "DELETE FROM notifications WHERE date_creation < DATE_SUB(NOW(), INTERVAL 30 DAY)";
        return $this->db->delete($sql);
    }
}
?>