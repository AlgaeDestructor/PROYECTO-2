<?php
// /admin/user_form.php

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

// Initialize the user variable to null
$user = null;

// Check if the 'id' parameter exists in the URL, if so, fetch the user's details for editing
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $user = $userModel->getUserById($user_id);
}

// Include the header template
include '../views/templates/header.php';
?>

<div class="container">
    <!-- Heading changes based on whether the user exists (edit) or not (create) -->
    <h1><?= $user ? 'Editar Usuario' : 'Agregar Usuario' ?></h1>
    
    <!-- Form for creating or editing a user -->
    <form method="post" action="../controllers/AdminUserController.php">
        <!-- Hidden input for the user ID if editing an existing user -->
        <?php if ($user): ?>
        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
        <?php endif; ?>

        <!-- Input fields for user details: Name, Surname, Username, Email -->
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" class="form-control" required value="<?= htmlspecialchars($user['nombre'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="apellidos">Apellidos:</label>
            <input type="text" name="apellidos" class="form-control" required value="<?= htmlspecialchars($user['apellidos'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="nombre_usuario">Nombre de Usuario:</label>
            <input type="text" name="nombre_usuario" class="form-control" required value="<?= htmlspecialchars($user['nombre_usuario'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="email">Correo Electrónico:</label>
            <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($user['email'] ?? '') ?>">
        </div>

        <!-- Password field is shown only when creating a new user (not editing) -->
        <?php if (!$user): // Only show the password field when creating a new user ?>
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <?php endif; ?>

        <!-- Dropdown for selecting the user role: user or admin -->
        <div class="form-group">
            <label for="rol">Rol:</label>
            <select name="rol" class="form-control">
                <option value="usuario" <?= isset($user['rol']) && $user['rol'] == 'usuario' ? 'selected' : '' ?>>Usuario</option>
                <option value="admin" <?= isset($user['rol']) && $user['rol'] == 'admin' ? 'selected' : '' ?>>Administrador</option>
            </select>
        </div>

        <!-- Submit button to create or update the user -->
        <button type="submit" class="btn btn-primary"><?= $user ? 'Actualizar' : 'Crear' ?></button>
    </form>
</div>

<?php 
// Include the footer template
include '../views/templates/footer.php'; 
?>
