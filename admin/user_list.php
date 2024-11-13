<?php
session_start();
require_once '../config/config.php';
require_once '../models/User.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

$userModel = new User($pdo);
$users = $userModel->getAllUsers();

include '../views/templates/header.php';
?>

<div class="container">
    <h1>Gestión de Usuarios</h1>
    <a href="user_form.php" class="btn btn-primary mb-3">Agregar Usuario</a>
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
            <?php foreach($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['nombre_usuario']) ?></td>
                <td><?= htmlspecialchars($user['nombre'] . ' ' . $user['apellidos']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= $user['rol'] ?></td>
                <td>
                    <a href="user_form.php?id=<?= $user['id'] ?>" class="btn btn-secondary btn-sm">Editar</a>
                    <a href="../controllers/AdminUserController.php?delete=<?= $user['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este usuario?');">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../views/templates/footer.php'; ?>
