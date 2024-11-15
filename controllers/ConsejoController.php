<?php
// /controllers/ConsejoController.php

session_start();
require_once '../config/config.php';
require_once '../models/Consejo.php';

// Check if the user is logged in and has an 'admin' role, otherwise redirect to home
if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

$consejoModel = new Consejo($pdo);

// Handle form submission for adding or updating a 'consejo'
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'titulo' => $_POST['titulo'], // Title of the consejo
        'descripcion_breve' => $_POST['descripcion_breve'], // Brief description
        'texto_explicativo' => $_POST['texto_explicativo'], // Explanatory text
        'etiquetas' => $_POST['etiquetas'], // Tags associated with the consejo
    ];

    // If there's an ID in the POST data, update an existing consejo, otherwise create a new one
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $consejoModel->updateConsejo($_POST['id'], $data); // Update existing consejo
    } else {
        $consejoModel->createConsejo($data); // Create new consejo
    }

    // After handling the form, redirect to the list of consejos
    header('Location: ../admin/consejos_list.php');
    exit;
} elseif (isset($_GET['delete'])) {
    // If a delete action is triggered, delete the corresponding consejo
    $consejoModel->deleteConsejo($_GET['delete']);
    header('Location: ../admin/consejos_list.php');
    exit;
}
?>
