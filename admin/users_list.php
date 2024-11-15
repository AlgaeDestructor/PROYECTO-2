<?php
// /admin/users_list.php

// Start the session to check user authentication
session_start();

// Include configuration file for database connection
require_once '../config/config.php';

// Include the User model for database operations
require_once '../models/User.php';

// Check if the user is an admin, if not, redirect to the homepage
if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

// Create an instance of the User model
$userModel = new User($pdo);

// Fetch all users from the database
$users = $userModel->getAllUsers();

// Include the header template
include '../views/templates/header.php';
?>

<div class="container">
    <!-- Heading for the User Management page -->
    <h1>Gestión de Usuarios</h1>
    
    <!-- Button to add a new user -->
    <a href="user_form.php" class="btn btn-primary mb-3">Agregar Usuario</a>
    
    <!-- Table displaying the list of users -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre de Usuario</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Loop through all users and display their details in table rows -->
            <?php foreach($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['nombre_usuario']) ?></td>
                <td><?= htmlspecialchars($user['nombre'] . ' ' . $user['apellidos']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= $user['rol'] ?></td>
                <td>
                    <!-- Edit button to go to the user form -->
                    <a href="user_form.php?id=<?= $user['id'] ?>" class="btn btn-secondary btn-sm">Editar</a>
                    <!-- Delete button with a confirmation prompt -->
                    <a href="../controllers/AdminUserController.php?delete=<?= $user['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este usuario?');">Eliminar</a>
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
