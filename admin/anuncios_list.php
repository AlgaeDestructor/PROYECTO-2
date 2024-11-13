<?php
session_start();
require_once '../config/config.php';
require_once '../models/Anuncio.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != 'admin') {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}

$anuncioModel = new Anuncio($pdo);
$anuncios = $anuncioModel->getAllAnuncios();

include '../views/templates/header.php';
?>

<div class="container">
    <h1>Gestión de Anuncios</h1>
    <a href="anuncio_form.php" class="btn btn-primary mb-3">Agregar Anuncio</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Título</th>
                <th>Categoría</th>
                <th>Usuario</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($anuncios as $anuncio): ?>
            <tr>
                <td><?= htmlspecialchars($anuncio['titulo']) ?></td>
                <td><?= htmlspecialchars($anuncio['categoria_nombre']) ?></td>
                <td><?= htmlspecialchars($anuncio['nombre_usuario']) ?></td>
                <td><?= $anuncio['estado'] ?></td>
                <td>
                    <a href="anuncio_form.php?id=<?= $anuncio['id'] ?>" class="btn btn-secondary btn-sm">Editar</a>
                    <a href="../controllers/AdminAnuncioController.php?delete=<?= $anuncio['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este anuncio?');">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../views/templates/footer.php'; ?>
