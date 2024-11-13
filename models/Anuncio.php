<?php
class Anuncio {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function getAllAnuncios() {
        $sql = "SELECT anuncios.*, categorias.nombre AS categoria_nombre, users.nombre_usuario FROM anuncios
                LEFT JOIN categorias ON anuncios.categoria_id = categorias.id
                LEFT JOIN users ON anuncios.id_usuario = users.id
                WHERE anuncios.soft_delete = 0";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
    public function getAnuncioById($id) {
        $sql = "SELECT anuncios.*, categorias.nombre AS categoria_nombre FROM anuncios
                LEFT JOIN categorias ON anuncios.categoria_id = categorias.id
                WHERE anuncios.id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public function createAnuncio($data) {
        $sql = "INSERT INTO anuncios (id_usuario, titulo, descripcion, categoria_id, estado, soft_delete) VALUES (?, ?, ?, ?, ?, 0)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['id_usuario'],
            $data['titulo'],
            $data['descripcion'],
            $data['categoria_id'],
            $data['estado']
        ]);
    }
    public function updateAnuncio($id, $data) {
        $sql = "UPDATE anuncios SET titulo = ?, descripcion = ?, categoria_id = ?, estado = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['titulo'],
            $data['descripcion'],
            $data['categoria_id'],
            $data['estado'],
            $id
        ]);
    }

    public function deleteAnuncio($id) {
        $sql = "UPDATE anuncios SET soft_delete = 1 WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function getPublicAnuncios() {
        $sql = "SELECT anuncios.*, categorias.nombre AS categoria_nombre FROM anuncios
                LEFT JOIN categorias ON anuncios.categoria_id = categorias.id
                WHERE anuncios.estado = 'publicado' AND anuncios.soft_delete = 0";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}
?>
