<?php
// /controllers/AdminAnuncioController.php

session_start();
require_once '../config/config.php';
require_once '../models/Anuncio.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != 'admin') {
    // If not an admin, redirect to the homepage
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}

$anuncioModel = new Anuncio($pdo);

// Handle the form submission for creating or updating an ad
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'titulo' => $_POST['titulo'],         
        'descripcion' => $_POST['descripcion'], 
        'categoria_id' => $_POST['categoria_id'], 
        'estado' => $_POST['estado'],          
    ];

    // Check if an ad ID is provided for updating an existing ad
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update the existing ad
        $anuncioModel->updateAnuncio($_POST['id'], $data);
    } else {
        // Create a new ad
        $data['id_usuario'] = $_SESSION['user']['id']; // Set the user ID for the ad
        $anuncioModel->createAnuncio($data);
    }

    // Redirect to the ad management page after the operation
    header('Location: ' . BASE_URL . 'admin/anuncios_list.php');
    exit;
} elseif (isset($_GET['delete'])) {
    // Delete the ad if the delete parameter is present
    $anuncioModel->deleteAnuncio($_GET['delete']);
    header('Location: ' . BASE_URL . 'admin/anuncios_list.php');
    exit;
}
?>
