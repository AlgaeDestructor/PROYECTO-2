<?php
// header.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__FILE__, 3) . '/config/config.php';

$base_url = BASE_URL;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Eventos Sostenibles</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS desde CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="<?= $base_url ?>assets/css/styles.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg" role="navigation" aria-label="Menú principal">
            <a class="navbar-brand" href="<?= $base_url ?>index.php" title="Inicio"><img
                    src="<?= $base_url ?>assets/logo/logo.png" alt="Logo de Eventos Sostenibles" width="50"
                    height="50"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Alternar navegación">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <!-- Navigator links -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $base_url ?>index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $base_url ?>views/consejos.php">Consejos Sostenibles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $base_url ?>views/anuncios.php">Anuncios</a>
                    </li>
                    <?php if (isset($_SESSION['user'])): ?>
                        <?php if ($_SESSION['user']['rol'] == 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= $base_url ?>admin/dashboard.php">Panel Admin</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $base_url ?>views/user/favorites.php">Mis Favoritos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $base_url ?>views/user/profile.php">
                                <?php if ($_SESSION['user']['imagen_perfil']): ?>
                                    <img src="<?= $base_url ?>uploads/profile_images/<?= htmlspecialchars($_SESSION['user']['imagen_perfil']) ?>"
                                        alt="Foto de perfil de <?= htmlspecialchars($_SESSION['user']['nombre_usuario']) ?>"
                                        width="30" height="30" class="rounded-circle">
                                <?php else: ?>
                                    <img src="<?= $base_url ?>assets/images/default_profile.png"
                                        alt="Imagen de perfil por defecto" width="30" height="30" class="rounded-circle">
                                <?php endif; ?>
                                <span><?= htmlspecialchars($_SESSION['user']['nombre_usuario']) ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $base_url ?>controllers/LogoutController.php">Cerrar Sesión</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $base_url ?>views/user/login.php">Iniciar Sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $base_url ?>views/user/register.php">Registrarse</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Aquí iría el contenido de la página -->

    <!-- Scripts necesarios para Bootstrap -->
    <!-- Incluir jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- Incluir Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <!-- Incluir Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
