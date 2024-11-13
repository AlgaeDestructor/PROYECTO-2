<?php

session_start();
require_once '../config/config.php';
require_once '../models/Anuncio.php';
require_once '../models/Categoria.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != 'admin') {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}

$anuncioModel = new Anuncio($pdo);
$categoriaModel = new Categoria($pdo);

$categorias = $categoriaModel->getAllCategorias();

$anuncio = null;

if (isset($_GET['id'])) {
    $anuncio = $anuncioModel->getAnuncioById($_GET['id']);
}

include '../views/templates/header.php';
?>

<div class="container">
    <h1><?= $anuncio ? 'Editar Anuncio' : 'Agregar Anuncio' ?></h1>
    <form method="post" action="../controllers/AdminAnuncioController.php" enctype="multipart/form-data">
        <?php if ($anuncio): ?>
        <input type="hidden" name="id" value="<?= $anuncio['id'] ?>">
        <?php endif; ?>
        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" name="titulo" class="form-control" required value="<?= htmlspecialchars($anuncio['titulo'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" class="form-control" required><?= htmlspecialchars($anuncio['descripcion'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label for="categoria_id">Categoría:</label>
            <select name="categoria_id" class="form-control" required>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= $categoria['id'] ?>" <?= isset($anuncio['categoria_id']) && $anuncio['categoria_id'] == $categoria['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($categoria['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="estado">Estado:</label>
            <select name="estado" class="form-control">
                <option value="borrador" <?= (isset($anuncio['estado']) && $anuncio['estado'] == 'borrador') || !isset($anuncio['estado']) ? 'selected' : '' ?>>Borrador</option>
                <option value="publicado" <?= isset($anuncio['estado']) && $anuncio['estado'] == 'publicado' ? 'selected' : '' ?>>Publicado</option>
                <option value="caducado" <?= isset($anuncio['estado']) && $anuncio['estado'] == 'caducado' ? 'selected' : '' ?>>Caducado</option>
                <option value="eliminado" <?= isset($anuncio['estado']) && $anuncio['estado'] == 'eliminado' ? 'selected' : '' ?>>Eliminado</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><?= $anuncio ? 'Actualizar' : 'Crear' ?></button>
    </form>
</div>

<?php include '../views/templates/footer.php'; ?>
