<?php
// /controllers/ValoracionController.php

session_start();
require_once '../config/config.php';
require_once '../models/Valoracion.php';

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user'])) {
    header('Location: ../views/user/login.php');
    exit;
}

$valoracionModel = new Valoracion($pdo);

// Handle form submission for rating
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_evento = $_POST['id_evento']; // Event ID
    $id_usuario = $_SESSION['user']['id']; // User ID from session
    $puntuacion = intval($_POST['puntuacion']); // Rating value (convert to integer)

    // Check if the rating is between 1 and 5
    if ($puntuacion >= 1 && $puntuacion <= 5) {
        // Add or update the rating for the event
        $valoracionModel->addOrUpdateValoracion($id_evento, $id_usuario, $puntuacion);
    }

    // Redirect back to the event page after the rating is processed
    header('Location: ../event.php?id=' . $id_evento);
    exit;
}
?>
