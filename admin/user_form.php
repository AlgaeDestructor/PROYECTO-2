<?php

session_start();
require_once '../config/config.php';
require_once '../models/User.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

$userModel = new User($pdo);

$user = null;

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $user = $userModel->getUserById($user_id);
}

include '../views/templates/header.php';
?>
<div class="container">
    <h1><?= $user ? 'Editar Usuario' : 'Agregar Usuario' ?></h1>
    <form method="post" action="../controllers/AdminUserController.php">
        <?php if ($user): ?>
        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
        <?php endif; ?>
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
        <?php if (!$user) ?>
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <?php endif; ?>
        <div class="form-group">
            <label for="rol">Rol:</label>
            <select name="rol" class="form-control">
                <option value="usuario" <?= isset($user['rol']) && $user['rol'] == 'usuario' ? 'selected' : '' ?>>Usuario</option>
                <option value="admin" <?= isset($user['rol']) && $user['rol'] == 'admin' ? 'selected' : '' ?>>Administrador</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><?= $user ? 'Actualizar' : 'Crear' ?></button>
    </form>
</div>
<?php include '../views/templates/footer.php'; ?>
