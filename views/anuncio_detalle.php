<?php

session_start();
require_once '../config/config.php';
require_once '../models/Anuncio.php';

if (!isset($_GET['id'])) {
    header('Location: anuncios.php');
    exit;
}

$anuncioModel = new Anuncio($pdo);
$anuncio = $anuncioModel->getAnuncioById($_GET['id']);

if (!$anuncio || $anuncio['estado'] != 'publicado' || $anuncio['soft_delete'] == 1) {
    echo "Anuncio no encontrado o no disponible.";
    exit;
}

include 'templates/header.php';
?>

<div class="container">
    <h1><?= htmlspecialchars($anuncio['titulo']) ?></h1>
    <p><?= htmlspecialchars($anuncio['descripcion']) ?></p>
    <p><strong>Categoría:</strong> <?= htmlspecialchars($anuncio['categoria_nombre']) ?></p>
</div>

<?php include 'templates/footer.php'; ?>
