<?php
// /admin/events_list.php

// Start the session to check user authentication
session_start();

// Include configuration file for database connection
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

// Fetch all events from the database
$events = $eventModel->getAllEvents();

// Include the header template
include '../views/templates/header.php';
?>

<div class="container">
    <h1>Gestión de Eventos</h1>
    <!-- Button to add a new event -->
    <a href="event_form.php" class="btn btn-primary mb-3">Agregar Evento</a>
    
    <!-- Table displaying the list of events -->
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
            <!-- Loop through each event and display its details -->
            <?php foreach($events as $event): ?>
            <tr>
                <td><?= htmlspecialchars($event['titulo']) ?></td> <!-- Event title -->
                <td><?= $event['fecha'] ?></td> <!-- Event date -->
                <td><?= $event['estado'] ?></td> <!-- Event status -->
                <td><?= $event['num_visualizaciones'] ?></td> <!-- Event views count -->
                <td>
                    <!-- Edit button, redirects to event form page with event ID -->
                    <a href="event_form.php?id=<?= $event['id'] ?>" class="btn btn-secondary btn-sm">Editar</a>
                    <!-- Delete button with confirmation prompt before deleting -->
                    <a href="../controllers/AdminEventController.php?delete=<?= $event['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este evento?');">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php 
// Include the footer template
include '../views/templates/footer.php'; 
?>
