<?php
// /models/Event.php

class Event {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }


    // Get upcoming events with optional search and date filters
    public function getUpcomingEvents($search = '', $date = '') {
        $sql = "SELECT * FROM eventos WHERE estado = 'vigente' AND fecha >= CURDATE()";
        $params = [];

        if ($search) {
            $sql .= " AND titulo LIKE ?";
            $params[] = '%' . $search . '%';
        }
        if ($date) {
            $sql .= " AND fecha = ?";
            $params[] = $date;
        }

        $sql .= " ORDER BY fecha ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    // Get event details by ID
    public function getEventById($id) {
        $sql = "SELECT * FROM eventos WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Increment the number of views for an event
    public function incrementViews($event_id) {
        $sql = "UPDATE eventos SET num_visualizaciones = num_visualizaciones + 1 WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$event_id]);
    }

    // Create a new event
    public function createEvent($data) {
        $sql = "INSERT INTO eventos (titulo, latitud, longitud, descripcion, imagenes, tipo, fecha, hora, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'vigente')";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['titulo'],
            $data['latitud'],
            $data['longitud'],
            $data['descripcion'],
            $data['imagenes'],
            $data['tipo'],
            $data['fecha'],
            $data['hora']
        ]);
    }

    // Update an existing event
    public function updateEvent($id, $data) {
        $sql = "UPDATE eventos SET titulo = ?, latitud = ?, longitud = ?, descripcion = ?, imagenes = ?, tipo = ?, fecha = ?, hora = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['titulo'],
            $data['latitud'],
            $data['longitud'],
            $data['descripcion'],
            $data['imagenes'],
            $data['tipo'],
            $data['fecha'],
            $data['hora'],
            $id
        ]);
    }

    // Delete an event
    public function deleteEvent($event_id) {
        $sql = "DELETE FROM eventos WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$event_id]);
    }

    // Get all events (for admin)
    public function getAllEvents() {
        $sql = "SELECT * FROM eventos ORDER BY fecha DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}
?>