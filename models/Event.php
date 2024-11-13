<?php
class Event {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }


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

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getEventById($id) {
        $sql = "SELECT * FROM eventos WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public function incrementViews($event_id) {
        $sql = "UPDATE eventos SET num_visualizaciones = num_visualizaciones + 1 WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$event_id]);
    }
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
    public function deleteEvent($event_id) {
        $sql = "DELETE FROM eventos WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$event_id]);
    }
    public function getAllEvents() {
        $sql = "SELECT * FROM eventos ORDER BY fecha DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}
?>
