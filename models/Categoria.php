<?php

class Categoria {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function getAllCategorias() {
        $sql = "SELECT * FROM categorias";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
    public function createCategoria($nombre) {
        $sql = "INSERT INTO categorias (nombre) VALUES (?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nombre]);
    }
    public function updateCategoria($id, $nombre) {
        $sql = "UPDATE categorias SET nombre = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nombre, $id]);
    }
    public function deleteCategoria($id) {
        $sql = "DELETE FROM categorias WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>
