<?php


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

<div class="container">
    <h1>Mis Eventos Favoritos</h1>
    <?php if (count($favorites) > 0): ?>
        <?php foreach($favorites as $event): ?>
            <div class="event mb-3">
                <h3>
                    <a href="<?= BASE_URL ?>event.php?id=<?= $event['id'] ?>"><?= htmlspecialchars($event['titulo']) ?></a>
                </h3>
                <p><?= htmlspecialchars($event['descripcion']) ?></p>
                <p><strong>Fecha:</strong> <?= $event['fecha'] ?> <strong>Hora:</strong> <?= $event['hora'] ?></p>
                <form method="post" action="<?= BASE_URL ?>controllers/FavoriteController.php">
                    <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                    <input type="hidden" name="action" value="remove">
                    <button type="submit" class="btn btn-danger">Eliminar de Favoritos</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No tienes eventos favoritos.</p>
    <?php endif; ?>
</div>

<?php

include $baseDir . '/views/templates/footer.php';
?>
