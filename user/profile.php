<?php


session_start();
require_once '../../config/config.php';
require_once '../../models/User.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$userModel = new User($pdo);
$user = $userModel->getUserById($_SESSION['user']['id']);

include '../templates/header.php';
?>

<div class="container">
    <h1>Mi Perfil</h1>
    <form method="post" action="../../controllers/ProfileController.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" class="form-control" required value="<?= htmlspecialchars($user['nombre']) ?>">
        </div>
        <div class="form-group">
            <label for="apellidos">Apellidos:</label>
            <input type="text" name="apellidos" class="form-control" required value="<?= htmlspecialchars($user['apellidos']) ?>">
        </div>
        <div class="form-group">
            <label for="nombre_usuario">Nombre de Usuario:</label>
            <input type="text" name="nombre_usuario" class="form-control" required value="<?= htmlspecialchars($user['nombre_usuario']) ?>">
        </div>
        <div class="form-group">
            <label for="email">Correo Electrónico:</label>
            <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($user['email']) ?>">
        </div>
        <div class="form-group">
            <label for="imagen_perfil">Foto de Perfil:</label>
            <?php if ($user['imagen_perfil']): ?>
                <img src="<?= BASE_URL ?>uploads/profile_images/<?= htmlspecialchars($user['imagen_perfil']) ?>" alt="Foto de Perfil" width="150">
            <?php endif; ?>
            <input type="file" name="imagen_perfil" class="form-control-file">
        </div>
        <div class="form-group">
            <label for="contrasena_actual">Contraseña Actual:</label>
            <input type="password" name="contrasena_actual" class="form-control">
        </div>
        <div class="form-group">
            <label for="nueva_contrasena">Nueva Contraseña:</label>
            <input type="password" name="nueva_contrasena" class="form-control">
        </div>
        <div class="form-group">
            <label for="confirmar_contrasena">Confirmar Nueva Contraseña:</label>
            <input type="password" name="confirmar_contrasena" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</div>

<?php include '../templates/footer.php'; ?>
