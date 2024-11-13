<?php

session_start();
require_once '../config/config.php';
require_once '../models/Consejo.php';

if (!isset($_GET['id'])) {
    header('Location: consejos.php');
    exit;
}

$consejoModel = new Consejo($pdo);
$consejo = $consejoModel->getConsejoById($_GET['id']);

if (!$consejo) {
    echo "Consejo no encontrado.";
    exit;
}

include 'templates/header.php';
?>

<div class="container">
    <h1><?= htmlspecialchars($consejo['titulo']) ?></h1>
    <p><?= nl2br(htmlspecialchars($consejo['texto_explicativo'])) ?></p>
    <p><strong>Etiquetas:</strong> <?= htmlspecialchars($consejo['etiquetas']) ?></p>
</div>

<?php include 'templates/footer.php'; ?>
