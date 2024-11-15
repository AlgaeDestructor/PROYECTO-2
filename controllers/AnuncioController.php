<?php
// /controllers/AnuncioController.php

session_start();
require_once '../config/config.php';
require_once '../models/Anuncio.php';

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    // Redirect to login page if the user is not logged in
    header('Location: ../views/user/login.php');
    exit;
}

$anuncioModel = new Anuncio($pdo);

// Handle form submission for creating or updating an ad
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'id_usuario' => $_SESSION['user']['id'],          
        'titulo' => $_POST['titulo'],                     
        'descripcion' => $_POST['descripcion'],          
        'imagenes' => $_POST['imagenes'],                
        'categoria_id' => $_POST['categoria_id'],        
        'estado' => 'esborrany',                           
        'soft_delete' => 0                                
    ];

    // If the ID is provided, update the existing ad; otherwise, create a new one
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update the existing ad
        $anuncioModel->updateAnuncio($_POST['id'], $data);
    } else {
        // Create a new ad
        $anuncioModel->createAnuncio($data);
    }

    // Redirect to the user's ads page after the operation
    header('Location: ../user/mis_anuncios.php');
    exit;
} elseif (isset($_GET['delete'])) {
    // Soft delete the ad if the delete parameter is present
    $anuncioModel->DeleteAnuncio($_GET['delete']);
    header('Location: ../user/mis_anuncios.php');
    exit;
}
?>
