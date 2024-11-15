<?php
// /controllers/CategoriaController.php

session_start();
require_once '../config/config.php';
require_once '../models/Categoria.php';

// Check if the user is logged in and has the 'admin' role
if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != 'admin') {
    // Redirect to the homepage if the user is not an admin
    header('Location: ../index.php');
    exit;
}

$categoriaModel = new Categoria($pdo);

// Handle form submission for creating or updating a category
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre']; // Get the category name from the form

    // If an ID is provided, update the existing category; otherwise, create a new one
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update existing category
        $categoriaModel->updateCategoria($_POST['id'], $nombre);
    } else {
        // Create a new category
        $categoriaModel->createCategoria($nombre);
    }

    // Redirect to the category list after the operation
    header('Location: ../admin/categorias_list.php');
    exit;
} elseif (isset($_GET['delete'])) {
    // Delete the category if the 'delete' parameter is provided
    $categoriaModel->deleteCategoria($_GET['delete']);
    header('Location: ../admin/categorias_list.php');
    exit;
}
?>
