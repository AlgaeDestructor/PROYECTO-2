<?php
// /controllers/AdminEventController.php

session_start();
require_once '../config/config.php';
require_once '../models/Event.php';

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != 'admin') {
    // Redirect to the homepage if the user is not an admin
    header('Location: ../index.php');
    exit;
}

$eventModel = new Event($pdo);

// Handle form submission for creating or updating an event
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'titulo' => $_POST['titulo'],                   // Event title
        'descripcion' => $_POST['descripcion'],         // Event description
        'tipo' => $_POST['tipo'],                       // Event type
        'fecha' => $_POST['fecha'],                     // Event date
        'hora' => $_POST['hora'],                       // Event time
        'latitud' => $_POST['latitud'],                 // Event latitude
        'longitud' => $_POST['longitud'],               // Event longitude
    ];

    // Handle uploaded images
    $imagenesSubidas = [];
    if (isset($_FILES['imagenes'])) {
        $totalArchivos = count($_FILES['imagenes']['name']);
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];

        for ($i = 0; $i < $totalArchivos; $i++) {
            $nombreArchivo = $_FILES['imagenes']['name'][$i];        // File name
            $tmpArchivo = $_FILES['imagenes']['tmp_name'][$i];       // Temporary file path
            $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION); // File extension

            // Check if the file extension is allowed
            if (in_array(strtolower($extension), $extensionesPermitidas)) {
                $nuevoNombre = uniqid() . '.' . $extension;           // Generate a unique name for the file
                $rutaDestino = '../uploads/event_images/' . $nuevoNombre; // Destination path for the file

                // Move the uploaded file to the destination
                if (move_uploaded_file($tmpArchivo, $rutaDestino)) {
                    $imagenesSubidas[] = $nuevoNombre; // Add the uploaded file name to the list
                }
            }
        }
    }

    // If updating an existing event, retrieve the current images
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $eventoExistente = $eventModel->getEventById($_POST['id']);
        $imagenesExistentes = $eventoExistente['imagenes'] ? explode(',', $eventoExistente['imagenes']) : [];
        $imagenesTotales = array_merge($imagenesExistentes, $imagenesSubidas); // Merge old and new images
    } else {
        $imagenesTotales = $imagenesSubidas; // Only use the newly uploaded images
    }

    $data['imagenes'] = implode(',', $imagenesTotales); // Store the images as a comma-separated string

    // If an ID is present, update the existing event, otherwise create a new one
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update the event
        $eventModel->updateEvent($_POST['id'], $data);
    } else {
        // Create a new event
        $eventModel->createEvent($data);
    }

    // Redirect to the events list page after the operation
    header('Location: ../admin/events_list.php');
    exit;
} elseif (isset($_GET['delete'])) {
    // Delete the event if the delete parameter is present
    $eventModel->deleteEvent($_GET['delete']);
    header('Location: ../admin/events_list.php');
    exit;
}
?>
