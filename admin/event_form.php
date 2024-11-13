<?php

session_start();
require_once '../config/config.php';
require_once '../models/Event.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

$eventModel = new Event($pdo);

$event = null;

if (isset($_GET['id'])) {
    $event = $eventModel->getEventById($_GET['id']);
}

include '../views/templates/header.php';
?>

<div class="container">
    <h1><?= $event ? 'Editar Evento' : 'Agregar Evento' ?></h1>
    <form method="post" action="../controllers/AdminEventController.php" enctype="multipart/form-data">
        <?php if ($event): ?>
        <input type="hidden" name="id" value="<?= $event['id'] ?>">
        <?php endif; ?>
        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" name="titulo" class="form-control" required value="<?= htmlspecialchars($event['titulo'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" class="form-control" required><?= htmlspecialchars($event['descripcion'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label for="tipo">Tipo:</label>
            <input type="text" name="tipo" class="form-control" value="<?= htmlspecialchars($event['tipo'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" class="form-control" required value="<?= htmlspecialchars($event['fecha'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="hora">Hora:</label>
            <input type="time" name="hora" class="form-control" required value="<?= htmlspecialchars($event['hora'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="latitud">Latitud:</label>
            <input type="text" name="latitud" class="form-control" required value="<?= htmlspecialchars($event['latitud'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="longitud">Longitud:</label>
            <input type="text" name="longitud" class="form-control" required value="<?= htmlspecialchars($event['longitud'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="imagenes">Imágenes:</label>
            <?php if ($event && $event['imagenes']): ?>
                <?php $imagenes = explode(',', $event['imagenes']); ?>
                <?php foreach ($imagenes as $imagen): ?>
                    <img src="<?= BASE_URL ?>uploads/event_images/<?= htmlspecialchars($imagen) ?>" alt="Imagen del Evento" width="150">
                <?php endforeach; ?>
            <?php endif; ?>
            <input type="file" name="imagenes[]" class="form-control-file" multiple>
        </div>
        <button type="submit" class="btn btn-primary"><?= $event ? 'Actualizar' : 'Crear' ?></button>
    </form>
</div>

<?php include '../views/templates/footer.php'; ?>
