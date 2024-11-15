<?php
// /models/Categoria.php

class Categoria {
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Get all categories
    public function getAllCategorias() {
        $sql = "SELECT * FROM categorias"; // Select all categories from the 'categorias' table
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(); // Return all categories as an array
    }

    // Create a new category
    public function createCategoria($nombre) {
        $sql = "INSERT INTO categorias (nombre) VALUES (?)"; // Insert a new category with the given name
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nombre]); // Execute the insert statement
    }

    // Update an existing category
    public function updateCategoria($id, $nombre) {
        $sql = "UPDATE categorias SET nombre = ? WHERE id = ?"; // Update category name where the ID matches
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nombre, $id]); // Execute the update statement
    }

    // Delete a category
    public function deleteCategoria($id) {
        $sql = "DELETE FROM categorias WHERE id = ?"; // Delete a category by its ID
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]); // Execute the delete statement
    }
}
?>
