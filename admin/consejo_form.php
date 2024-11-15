<?php
// /admin/consejo_form.php

// Start the session
session_start();

// Include necessary configuration and model files
require_once '../config/config.php';
require_once '../models/Consejo.php';

// Check if the user is an admin, if not, redirect to the homepage
if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != 'admin') {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}

// Instantiate the 'Consejo' model
$consejoModel = new Consejo($pdo);

// Initialize the 'consejo' variable to null
$consejo = null;

// If an 'id' is provided in the URL, retrieve the corresponding consejo
if (isset($_GET['id'])) {
    $consejo = $consejoModel->getConsejoById($_GET['id']);
}

// Include the header template
include '../views/templates/header.php';
?>

<div class="container">
    <!-- Display title based on whether it's editing or creating a consejo -->
    <h1><?= $consejo ? 'Editar Consejo' : 'Agregar Consejo' ?></h1>
    
    <!-- Form to create or update a consejo -->
    <form method="post" action="../controllers/AdminConsejoController.php">
        <!-- If editing, include the consejo's id in a hidden input -->
        <?php if ($consejo): ?>
        <input type="hidden" name="id" value="<?= $consejo['id'] ?>">
        <?php endif; ?>
        
        <!-- Input for the 'titulo' (title) of the consejo -->
        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" name="titulo" class="form-control" required value="<?= htmlspecialchars($consejo['titulo'] ?? '') ?>">
        </div>
        
        <!-- Textarea for the 'descripcion_breve' (brief description) -->
        <div class="form-group">
            <label for="descripcion_breve">Descripción Breve:</label>
            <textarea name="descripcion_breve" class="form-control" required><?= htmlspecialchars($consejo['descripcion_breve'] ?? '') ?></textarea>
        </div>
        
        <!-- Textarea for the 'texto_explicativo' (explanatory text) -->
        <div class="form-group">
            <label for="texto_explicativo">Texto Explicativo:</label>
            <textarea name="texto_explicativo" class="form-control" required><?= htmlspecialchars($consejo['texto_explicativo'] ?? '') ?></textarea>
        </div>
        
        <!-- Input for 'etiquetas' (tags) -->
        <div class="form-group">
            <label for="etiquetas">Etiquetas:</label>
            <input type="text" name="etiquetas" class="form-control" value="<?= htmlspecialchars($consejo['etiquetas'] ?? '') ?>">
        </div>
        
        <!-- Submit button, with the label changing based on whether it's create or update -->
        <button type="submit" class="btn btn-primary"><?= $consejo ? 'Actualizar' : 'Crear' ?></button>
    </form>
</div>

<?php 
// Include the footer template
include '../views/templates/footer.php'; 
?>
