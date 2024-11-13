<?php

class Favorite {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addFavorite($user_id, $event_id) {
        $sql = "INSERT IGNORE INTO favoritos (id_usuario, id_evento) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$user_id, $event_id]);
    }

    public function removeFavorite($user_id, $event_id) {
        $sql = "DELETE FROM favoritos WHERE id_usuario = ? AND id_evento = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$user_id, $event_id]);
    }

    public function isFavorite($user_id, $event_id) {
        $sql = "SELECT * FROM favoritos WHERE id_usuario = ? AND id_evento = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id, $event_id]);
        return $stmt->fetch() ? true : false;
    }

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
