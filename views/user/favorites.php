<?php
// /views/user/favorites.php

session_start();
$baseDir = dirname(__FILE__, 3);
require_once $baseDir . '/config/config.php';
require_once $baseDir . '/models/Favorite.php';

if (!isset($_SESSION['user'])) {
    header('Location: ' . BASE_URL . 'views/user/login.php');
    exit;
}

$favoriteModel = new Favorite($pdo);
$favorites = $favoriteModel->getUserFavorites($_SESSION['user']['id']);
include $baseDir . '/views/templates/header.php';
?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Mis Eventos Favoritos</h1>
    <?php if (count($favorites) > 0): ?>
        <div class="row">
            <?php foreach($favorites as $event): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 border-0 shadow" style="background-color: #A5D6A7;">
                        <?php
                        $imagenes = explode(',', $event['imagenes']);
                        $imagen = $imagenes[0] ?? null;
                        if ($imagen):
                        ?>
                            <img src="<?= BASE_URL ?>uploads/event_images/<?= htmlspecialchars($imagen) ?>" alt="Imagen del evento: <?= htmlspecialchars($event['titulo']) ?>" class="card-img-top img-fluid rounded">
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h3 class="card-title" style="color: #0A2E0A;">
                                <a href="<?= BASE_URL ?>event.php?id=<?= $event['id'] ?>" class="text-decoration-none"><?= htmlspecialchars($event['titulo']) ?></a>
                            </h3>
                            <p class="card-text"><?= htmlspecialchars(substr($event['descripcion'], 0, 100)) ?>...</p>
                            <p class="card-text"><strong>Fecha:</strong> <?= $event['fecha'] ?> <strong>Hora:</strong> <?= $event['hora'] ?></p>
                            <form method="post" action="<?= BASE_URL ?>controllers/FavoriteController.php" class="mt-auto">
                                <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                                <input type="hidden" name="action" value="remove">
                                <button type="submit" class="btn btn-danger">Eliminar de Favoritos</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-center">No tienes eventos favoritos.</p>
    <?php endif; ?>
</div>

<?php include $baseDir . '/views/templates/footer.php'; ?>
