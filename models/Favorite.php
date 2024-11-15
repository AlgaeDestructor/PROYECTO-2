<?php
// /models/Favorite.php

class Favorite {
    private $pdo;

    // Constructor to initialize PDO connection
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Add event to user's favorites
    public function addFavorite($user_id, $event_id) {
        $sql = "INSERT IGNORE INTO favoritos (id_usuario, id_evento) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$user_id, $event_id]);
    }

    // Remove event from user's favorites
    public function removeFavorite($user_id, $event_id) {
        $sql = "DELETE FROM favoritos WHERE id_usuario = ? AND id_evento = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$user_id, $event_id]);
    }

    // Check if an event is in user's favorites
    public function isFavorite($user_id, $event_id) {
        $sql = "SELECT * FROM favoritos WHERE id_usuario = ? AND id_evento = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id, $event_id]);
        return $stmt->fetch() ? true : false;
    }

    // Get user's favorite events
    public function getUserFavorites($user_id) {
        $sql = "SELECT e.* FROM favoritos f
                JOIN eventos e ON f.id_evento = e.id
                WHERE f.id_usuario = ? AND e.estado = 'vigente' AND e.fecha >= CURDATE()";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }
}
?>