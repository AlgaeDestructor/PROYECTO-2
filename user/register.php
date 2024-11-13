<?php
require_once dirname(__FILE__, 3) . '/config/config.php';
include dirname(__FILE__, 2) . '/templates/header.php';
?>
<div class="container">
    <h2>Registro</h2>
    <form method="post" action="../../controllers/UserController.php">
        <input type="hidden" name="register" value="1">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" required class="form-control">
        </div>
        <div class="form-group">
            <label for="apellidos">Apellidos:</label>
            <input type="text" name="apellidos" required class="form-control">
        </div>
        <div class="form-group">
            <label for="nombre_usuario">Nombre de Usuario:</label>
            <input type="text" name="nombre_usuario" required class="form-control">
        </div>
        <div class="form-group">
            <label for="email">Correo Electrónico:</label>
            <input type="email" name="email" required class="form-control">
        </div>
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" name="password" required class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Registrarse</button>
    </form>
</div>
<?php include dirname(__FILE__, 2) . '/templates/footer.php'; ?>
