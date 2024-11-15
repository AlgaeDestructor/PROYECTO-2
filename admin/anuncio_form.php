<?php
// /admin/anuncio_form.php

// Start the session
session_start();

// Include necessary configuration and model files
require_once '../config/config.php';
require_once '../models/Anuncio.php';
require_once '../models/Categoria.php';

// Check if the user is an admin, if not, redirect to the homepage
if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != 'admin') {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}

// Instantiate the models for 'Anuncio' and 'Categoria'
$anuncioModel = new Anuncio($pdo);
$categoriaModel = new Categoria($pdo);

// Get all categories from the database
$categorias = $categoriaModel->getAllCategorias();

// Initialize the 'anuncio' variable as null
$anuncio = null;

// If an 'id' is set in the query string, retrieve the specific anuncio
if (isset($_GET['id'])) {
    $anuncio = $anuncioModel->getAnuncioById($_GET['id']);
}

// Include the header template
include '../views/templates/header.php';
?>

<div class="container">
    <!-- Display a title depending on whether we're editing or adding a new 'Anuncio' -->
    <h1><?= $anuncio ? 'Editar Anuncio' : 'Agregar Anuncio' ?></h1>
    
    <!-- Form for submitting a new or edited 'Anuncio' -->
    <form method="post" action="../controllers/AdminAnuncioController.php" enctype="multipart/form-data">
        
        <!-- If editing an existing anuncio, include the 'id' as a hidden field -->
        <?php if ($anuncio): ?>
        <input type="hidden" name="id" value="<?= $anuncio['id'] ?>">
        <?php endif; ?>
        
        <!-- Title input field -->
        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" name="titulo" class="form-control" required value="<?= htmlspecialchars($anuncio['titulo'] ?? '') ?>">
        </div>
        
        <!-- Description textarea -->
        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" class="form-control" required><?= htmlspecialchars($anuncio['descripcion'] ?? '') ?></textarea>
        </div>
        
        <!-- Category selection dropdown -->
        <div class="form-group">
            <label for="categoria_id">Categoría:</label>
            <select name="categoria_id" class="form-control" required>
                <!-- Loop through the categories and display them as options -->
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= $categoria['id'] ?>" <?= isset($anuncio['categoria_id']) && $anuncio['categoria_id'] == $categoria['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($categoria['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <!-- Status selection dropdown -->
        <div class="form-group">
            <label for="estado">Estado:</label>
            <select name="estado" class="form-control">
                <!-- Option for 'Draft' status -->
                <option value="borrador" <?= (isset($anuncio['estado']) && $anuncio['estado'] == 'borrador') || !isset($anuncio['estado']) ? 'selected' : '' ?>>Borrador</option>
                <!-- Option for 'Published' status -->
                <option value="publicado" <?= isset($anuncio['estado']) && $anuncio['estado'] == 'publicado' ? 'selected' : '' ?>>Publicado</option>
                <!-- Option for 'Expired' status -->
                <option value="caducado" <?= isset($anuncio['estado']) && $anuncio['estado'] == 'caducado' ? 'selected' : '' ?>>Caducado</option>
                <!-- Option for 'Deleted' status -->
                <option value="eliminado" <?= isset($anuncio['estado']) && $anuncio['estado'] == 'eliminado' ? 'selected' : '' ?>>Eliminado</option>
            </select>
        </div>
        
        <!-- Submit button, text changes depending on whether it's editing or creating a new anuncio -->
        <button type="submit" class="btn btn-primary"><?= $anuncio ? 'Actualizar' : 'Crear' ?></button>
    </form>
</div>

<?php 
// Include the footer template
include '../views/templates/footer.php'; 
?>
