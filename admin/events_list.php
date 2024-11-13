<?php
session_start();
require_once '../config/config.php';
require_once '../models/Event.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

$eventModel = new Event($pdo);
$events = $eventModel->getAllEvents();

include '../views/templates/header.php';
?>

<div class="container">
    <h1>Gestión de Eventos</h1>
    <a href="event_form.php" class="btn btn-primary mb-3">Agregar Evento</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Título</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Visualizaciones</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($events as $event): ?>
            <tr>
                <td><?= htmlspecialchars($event['titulo']) ?></td>
                <td><?= $event['fecha'] ?></td>
                <td><?= $event['estado'] ?></td>
                <td><?= $event['num_visualizaciones'] ?></td>
                <td>
                    <a href="event_form.php?id=<?= $event['id'] ?>" class="btn btn-secondary btn-sm">Editar</a>
                    <a href="../controllers/AdminEventController.php?delete=<?= $event['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este evento?');">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../views/templates/footer.php'; ?>
