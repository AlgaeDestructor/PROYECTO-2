<?php
// /models/Consejo.php

class Consejo {
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Get all consejos
    public function getAllConsejos() {
        $sql = "SELECT * FROM consejos"; // Select all 'consejos' (tips) from the 'consejos' table
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(); // Return all consejos as an array
    }

    // Get a consejo by ID
    public function getConsejoById($id) {
        $sql = "SELECT * FROM consejos WHERE id = ?"; // Select a specific consejo by ID
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]); // Execute the prepared statement with the provided ID
        return $stmt->fetch(); // Return the consejo matching the ID
    }

    // Create a new consejo
    public function createConsejo($data) {
        $sql = "INSERT INTO consejos (titulo, descripcion_breve, texto_explicativo, etiquetas) VALUES (?, ?, ?, ?)"; // Insert a new consejo with the given data
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['titulo'],
            $data['descripcion_breve'],
            $data['texto_explicativo'],
            $data['etiquetas']
        ]); // Execute the insert statement with provided values
    }

    // Update an existing consejo
    public function updateConsejo($id, $data) {
        $sql = "UPDATE consejos SET titulo = ?, descripcion_breve = ?, texto_explicativo = ?, etiquetas = ? WHERE id = ?"; // Update an existing consejo with new values
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['titulo'],
            $data['descripcion_breve'],
            $data['texto_explicativo'],
            $data['etiquetas'],
            $id
        ]); // Execute the update statement with the provided data
    }

    // Delete a consejo
    public function deleteConsejo($id) {
        $sql = "DELETE FROM consejos WHERE id = ?"; // Delete a consejo by its ID
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]); // Execute the delete statement with the provided ID
    }
}
?>
