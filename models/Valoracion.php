<?php
// /models/Valoracion.php

class Valoracion {
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Add or update a rating for an event by a user
    public function addOrUpdateValoracion($id_evento, $id_usuario, $puntuacion) {
        $sql = "INSERT INTO valoraciones (id_evento, id_usuario, puntuacion) VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE puntuacion = VALUES(puntuacion)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_evento, $id_usuario, $puntuacion]);
    }

    // Get the average rating for an event
    public function getAverageRating($id_evento) {
        $sql = "SELECT AVG(puntuacion) as promedio FROM valoraciones WHERE id_evento = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_evento]);
        $result = $stmt->fetch();
        return $result ? round($result['promedio'], 2) : null;
    }

    // Get a user's rating for a specific event
    public function getUserRating($id_evento, $id_usuario) {
        $sql = "SELECT puntuacion FROM valoraciones WHERE id_evento = ? AND id_usuario = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_evento, $id_usuario]);
        $result = $stmt->fetch();
        return $result ? $result['puntuacion'] : null;
    }
}
?>
