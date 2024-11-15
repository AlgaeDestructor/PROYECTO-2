<?php
// /controllers/AdminConsejoController.php

session_start();
require_once '../config/config.php';
require_once '../models/Consejo.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != 'admin') {
    // If not an admin, redirect to the homepage
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}

$consejoModel = new Consejo($pdo);

// Handle the form submission for creating or updating a sustainability tip
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'titulo' => $_POST['titulo'],                    
        'descripcion_breve' => $_POST['descripcion_breve'], 
        'texto_explicativo' => $_POST['texto_explicativo'],
        'etiquetas' => $_POST['etiquetas']                
    ];

    // Check if an ID is provided for updating an existing tip
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update the existing tip
        $consejoModel->updateConsejo($_POST['id'], $data);
    } else {
        // Create a new tip
        $consejoModel->createConsejo($data);
    }

    // Redirect to the tips management page after the operation
    header('Location: ' . BASE_URL . 'admin/consejos_list.php');
    exit;
} elseif (isset($_GET['delete'])) {
    // Delete the tip if the delete parameter is present
    $consejoModel->deleteConsejo($_GET['delete']);
    header('Location: ' . BASE_URL . 'admin/consejos_list.php');
    exit;
}
?>
