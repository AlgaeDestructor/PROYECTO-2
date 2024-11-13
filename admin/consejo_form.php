<?php
session_start();
require_once '../config/config.php';
require_once '../models/Consejo.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != 'admin') {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}

$consejoModel = new Consejo($pdo);

$consejo = null;

if (isset($_GET['id'])) {
    $consejo = $consejoModel->getConsejoById($_GET['id']);
}

include '../views/templates/header.php';
?>

<div class="container">
    <h1><?= $consejo ? 'Editar Consejo' : 'Agregar Consejo' ?></h1>
    <form method="post" action="../controllers/AdminConsejoController.php">
        <?php if ($consejo): ?>
        <input type="hidden" name="id" value="<?= $consejo['id'] ?>">
        <?php endif; ?>
        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" name="titulo" class="form-control" required value="<?= htmlspecialchars($consejo['titulo'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="descripcion_breve">Descripción Breve:</label>
            <textarea name="descripcion_breve" class="form-control" required><?= htmlspecialchars($consejo['descripcion_breve'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label for="texto_explicativo">Texto Explicativo:</label>
            <textarea name="texto_explicativo" class="form-control" required><?= htmlspecialchars($consejo['texto_explicativo'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label for="etiquetas">Etiquetas:</label>
            <input type="text" name="etiquetas" class="form-control" value="<?= htmlspecialchars($consejo['etiquetas'] ?? '') ?>">
        </div>
        <button type="submit" class="btn btn-primary"><?= $consejo ? 'Actualizar' : 'Crear' ?></button>
    </form>
</div>

<?php include '../views/templates/footer.php'; ?>
