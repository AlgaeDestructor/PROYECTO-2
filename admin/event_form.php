<?php
// /admin/event_form.php

// Start the session
session_start();

// Include configuration file
require_once '../config/config.php';

// Include the Event model for database operations
require_once '../models/Event.php';

// Check if the user is an admin, if not, redirect to the homepage
if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

// Create an instance of the Event model
$eventModel = new Event($pdo);

// Initialize $event to null (used for form population)
$event = null;

// If an event ID is passed in the URL, fetch the event data from the database
if (isset($_GET['id'])) {
    $event = $eventModel->getEventById($_GET['id']);
}

// Include the header template
include '../views/templates/header.php';
?>

<div class="container">
    <h1><?= $event ? 'Editar Evento' : 'Agregar Evento' ?></h1>
    <!-- Event form starts here -->
    <form method="post" action="../controllers/AdminEventController.php" enctype="multipart/form-data">
        <?php if ($event): ?>
        <input type="hidden" name="id" value="<?= $event['id'] ?>">
        <?php endif; ?>
        <!-- Title input field -->
        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" name="titulo" class="form-control" required value="<?= htmlspecialchars($event['titulo'] ?? '') ?>">
        </div>
        <!-- Description input field -->
        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" class="form-control" required><?= htmlspecialchars($event['descripcion'] ?? '') ?></textarea>
        </div>
        <!-- Event type selection dropdown -->
        <div class="form-group">
            <label for="tipo">Tipo:</label>
            <select name="tipo" class="form-control" required>
                <option value="">Seleccione un tipo</option>
                <option value="Concierto" <?= (isset($event['tipo']) && $event['tipo'] == 'Concierto') ? 'selected' : '' ?>>Concierto</option>
                <option value="Conferencia" <?= (isset($event['tipo']) && $event['tipo'] == 'Conferencia') ? 'selected' : '' ?>>Conferencia</option>
                <option value="Exposicion" <?= (isset($event['tipo']) && $event['tipo'] == 'Exposicion') ? 'selected' : '' ?>>Exposicion</option>
            </select>
            <div class="invalid-feedback">
                Solo se permiten letras y espacios.
            </div>
        </div>
        <!-- Date input field -->
        <div class="form-group">
            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" class="form-control" required value="<?= htmlspecialchars($event['fecha'] ?? '') ?>">
        </div>
        <!-- Time input field -->
        <div class="form-group" id="hora-field">
            <label for="hora">Hora:</label>
            <input type="time" name="hora" class="form-control" value="<?= htmlspecialchars($event['hora'] ?? '') ?>">
        </div>
        <!-- Latitude input field -->
        <div class="form-group">
            <label for="latitud">Latitud:</label>
            <input type="text" name="latitud" class="form-control" required value="<?= htmlspecialchars($event['latitud'] ?? '') ?>">
            <div class="invalid-feedback">
                Ingresa una latitud válida entre -90 y 90.
            </div>
        </div>
        <!-- Longitude input field -->
        <div class="form-group">
            <label for="longitud">Longitud:</label>
            <input type="text" name="longitud" class="form-control" required value="<?= htmlspecialchars($event['longitud'] ?? '') ?>">
            <div class="invalid-feedback">
                Ingresa una longitud válida entre -180 y 180.
            </div>
        </div>
        <!-- Image upload field -->
        <div class="form-group">
            <label for="imagenes">Imágenes:</label>
            <?php if ($event && $event['imagenes']): ?>
                <?php $imagenes = explode(',', $event['imagenes']); ?>
                <!-- Display existing images if the event has them -->
                <?php foreach ($imagenes as $imagen): ?>
                    <img src="<?= BASE_URL ?>uploads/event_images/<?= htmlspecialchars($imagen) ?>" alt="Imagen del Evento" width="150">
                <?php endforeach; ?>
            <?php endif; ?>
            <input type="file" name="imagenes[]" class="form-control-file" multiple>
        </div>
        <!-- Submit button -->
        <button type="submit" class="btn btn-primary"><?= $event ? 'Actualizar' : 'Crear' ?></button>
    </form>
</div>

<!-- Include jQuery from CDN for dynamic form interactions -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Script for validations and dynamic changes in the form -->
<script>
$(document).ready(function() {
    // Validate the 'Tipo' field to allow only letters and spaces
    $('select[name="tipo"]').on('change', function() {
        var value = $(this).val();
        var regex = /^[A-Za-z\s]+$/;
        if (!regex.test(value)) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Function to show or hide the "Hora" field based on selected event type
    function toggleHoraField() {
        var tipo = $('select[name="tipo"]').val();
        if (tipo === 'Concierto' || tipo === 'Conferencia') {
            $('#hora-field').show();
        } else {
            $('#hora-field').hide();
            $('input[name="hora"]').val(''); // Clear value if hidden
        }
    }

    // Call the function on page load and when the event type changes
    toggleHoraField();
    $('select[name="tipo"]').on('change', toggleHoraField);

    // Validate latitude and longitude inputs
    function validateCoordinate(value, min, max) {
        var regex = /^-?\d+(\.\d+)?$/;
        if (regex.test(value)) {
            var num = parseFloat(value);
            return num >= min && num <= max;
        }
        return false;
    }

    // Validate latitude and longitude fields on input
    $('input[name="latitud"], input[name="longitud"]').on('input', function() {
        var name = $(this).attr('name');
        var value = $(this).val();
        var isValid = false;

        if (name === 'latitud') {
            isValid = validateCoordinate(value, -90, 90);
        } else if (name === 'longitud') {
            isValid = validateCoordinate(value, -180, 180);
        }

        if (!isValid) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Check for invalid fields before form submission
    $('form').on('submit', function(e) {
        var invalidFields = $('.is-invalid');
        if (invalidFields.length > 0) {
            e.preventDefault();
            alert('Por favor, corrige los campos inválidos antes de enviar el formulario.');
        }
    });
});
</script>

<?php 
// Include the footer template
include '../views/templates/footer.php'; 
?>
