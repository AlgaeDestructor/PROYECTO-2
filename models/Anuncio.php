<?php
// /models/Anuncio.php

class Anuncio {
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Get all ads (excluding soft deleted ones)
    public function getAllAnuncios() {
        $sql = "SELECT anuncios.*, categorias.nombre AS categoria_nombre, users.nombre_usuario FROM anuncios
                LEFT JOIN categorias ON anuncios.categoria_id = categorias.id
                LEFT JOIN users ON anuncios.id_usuario = users.id
                WHERE anuncios.soft_delete = 0"; // Exclude soft deleted ads
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    // Get a single ad by ID
    public function getAnuncioById($id) {
        $sql = "SELECT anuncios.*, categorias.nombre AS categoria_nombre FROM anuncios
                LEFT JOIN categorias ON anuncios.categoria_id = categorias.id
                WHERE anuncios.id = ?"; // Select ad by ID
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Create a new ad
    public function createAnuncio($data) {
        $sql = "INSERT INTO anuncios (id_usuario, titulo, descripcion, categoria_id, estado, soft_delete) 
                VALUES (?, ?, ?, ?, ?, 0)"; // Insert new ad, with soft delete defaulting to 0 (not deleted)
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['id_usuario'],   // User ID
            $data['titulo'],       // Ad title
            $data['descripcion'],  // Ad description
            $data['categoria_id'], // Category ID
            $data['estado']        // Ad status (draft, published, etc.)
        ]);
    }

    // Update an existing ad
    public function updateAnuncio($id, $data) {
        $sql = "UPDATE anuncios SET titulo = ?, descripcion = ?, categoria_id = ?, estado = ? WHERE id = ?"; // Update ad
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['titulo'],       // Ad title
            $data['descripcion'],  // Ad description
            $data['categoria_id'], // Category ID
            $data['estado'],       // Ad status
            $id                    // Ad ID
        ]);
    }

    // Soft delete an ad (mark as deleted without removing from database)
    public function deleteAnuncio($id) {
        $sql = "UPDATE anuncios SET soft_delete = 1 WHERE id = ?"; // Soft delete (set soft_delete to 1)
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Get only published ads (excluding soft deleted ones)
    public function getPublicAnuncios() {
        $sql = "SELECT anuncios.*, categorias.nombre AS categoria_nombre FROM anuncios
                LEFT JOIN categorias ON anuncios.categoria_id = categorias.id
                WHERE anuncios.estado = 'publicado' AND anuncios.soft_delete = 0"; // Filter for published ads
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}
?>
