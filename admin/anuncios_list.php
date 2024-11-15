<?php
// /admin/anuncios_list.php

// Start the session
session_start();

// Include necessary configuration and model files
require_once '../config/config.php';
require_once '../models/Anuncio.php';

// Check if the user is an admin, if not, redirect to the homepage
if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != 'admin') {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}

// Instantiate the 'Anuncio' model
$anuncioModel = new Anuncio($pdo);

// Retrieve all anuncios from the database
$anuncios = $anuncioModel->getAllAnuncios();

// Include the header template
include '../views/templates/header.php';
?>

<div class="container">
    <!-- Display the title for the 'Anuncios' management page -->
    <h1>Gestión de Anuncios</h1>
    
    <!-- Button to add a new anuncio -->
    <a href="anuncio_form.php" class="btn btn-primary mb-3">Agregar Anuncio</a>
    
    <!-- Table displaying all anuncios -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <!-- Table headers -->
                <th>Título</th>
                <th>Categoría</th>
                <th>Usuario</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Loop through each 'anuncio' and display its details in the table -->
            <?php foreach($anuncios as $anuncio): ?>
            <tr>
                <!-- Display the 'titulo', 'categoria_nombre', 'nombre_usuario', and 'estado' for each anuncio -->
                <td><?= htmlspecialchars($anuncio['titulo']) ?></td>
                <td><?= htmlspecialchars($anuncio['categoria_nombre']) ?></td>
                <td><?= htmlspecialchars($anuncio['nombre_usuario']) ?></td>
                <td><?= $anuncio['estado'] ?></td>
                <td>
                    <!-- Link to edit the anuncio -->
                    <a href="anuncio_form.php?id=<?= $anuncio['id'] ?>" class="btn btn-secondary btn-sm">Editar</a>
                    <!-- Link to delete the anuncio, with a confirmation prompt -->
                    <a href="../controllers/AdminAnuncioController.php?delete=<?= $anuncio['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este anuncio?');">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php 
// Include the footer template
include '../views/templates/footer.php'; 
?>
