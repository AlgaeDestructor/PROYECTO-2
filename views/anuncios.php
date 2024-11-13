<?php

session_start();
require_once '../config/config.php';
require_once '../models/Anuncio.php';

$anuncioModel = new Anuncio($pdo);
$anuncios = $anuncioModel->getPublicAnuncios();

include 'templates/header.php';
?>

<div class="container">
    <h1>Anuncios</h1>
    <?php if (count($anuncios) > 0): ?>
        <?php foreach($anuncios as $anuncio): ?>
            <div class="anuncio mb-3">
                <h3>
                    <a href="anuncio_detalle.php?id=<?= $anuncio['id'] ?>"><?= htmlspecialchars($anuncio['titulo']) ?></a>
                </h3>
                <p><?= htmlspecialchars($anuncio['descripcion']) ?></p>
                <p><strong>Categoría:</strong> <?= htmlspecialchars($anuncio['categoria_nombre']) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay anuncios disponibles.</p>
    <?php endif; ?>
</div>

<?php include 'templates/footer.php'; ?>
