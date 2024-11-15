<?php
// /admin/consejos_list.php

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

// Get all consejos from the database
$consejos = $consejoModel->getAllConsejos();

// Include the header template
include '../views/templates/header.php';
?>

<div class="container">
    <!-- Display the management title for consejos -->
    <h1>Gestión de Consejos</h1>
    
    <!-- Button to add a new consejo -->
    <a href="consejo_form.php" class="btn btn-primary mb-3">Agregar Consejo</a>
    
    <!-- Table to list all consejos -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <!-- Table headings -->
                <th>Título</th>
                <th>Etiquetas</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Loop through each consejo and display its data in a table row -->
            <?php foreach($consejos as $consejo): ?>
            <tr>
                <!-- Display the title and tags of the consejo -->
                <td><?= htmlspecialchars($consejo['titulo']) ?></td>
                <td><?= htmlspecialchars($consejo['etiquetas']) ?></td>
                <td>
                    <!-- Link to edit the consejo -->
                    <a href="consejo_form.php?id=<?= $consejo['id'] ?>" class="btn btn-secondary btn-sm">Editar</a>
                    
                    <!-- Link to delete the consejo, with a confirmation prompt -->
                    <a href="../controllers/AdminConsejoController.php?delete=<?= $consejo['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este consejo?');">Eliminar</a>
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
