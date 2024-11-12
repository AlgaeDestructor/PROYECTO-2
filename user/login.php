<?php
require_once dirname(__FILE__, 3) . '/config/config.php';
include dirname(__FILE__, 2) . '/templates/header.php';
?>
<div class="container">
    <h2>Iniciar Sesión</h2>
    <?php if(isset($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="post" action="../../controllers/UserController.php">
        <input type="hidden" name="login" value="1">
        <div class="form-group">
            <label for="nombre_usuario">Nombre de Usuario:</label>
            <input type="text" name="nombre_usuario" required class="form-control">
        </div>
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" name="password" required class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
    </form>
</div>
<?php include dirname(__FILE__, 2) . '/templates/footer.php'; ?>
