<?php

session_start();
require_once '../config/config.php';
require_once '../models/Consejo.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != 'admin') {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}

$consejoModel = new Consejo($pdo);
$consejos = $consejoModel->getAllConsejos();

include '../views/templates/header.php';
?>

<div class="container">
    <h1>Gestión de Consejos</h1>
    <a href="consejo_form.php" class="btn btn-primary mb-3">Agregar Consejo</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Título</th>
                <th>Etiquetas</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($consejos as $consejo): ?>
            <tr>
                <td><?= htmlspecialchars($consejo['titulo']) ?></td>
                <td><?= htmlspecialchars($consejo['etiquetas']) ?></td>
                <td>
                    <a href="consejo_form.php?id=<?= $consejo['id'] ?>" class="btn btn-secondary btn-sm">Editar</a>
                    <a href="../controllers/AdminConsejoController.php?delete=<?= $consejo['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este consejo?');">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../views/templates/footer.php'; ?>
