<?php


session_start();
require_once '../config/config.php';
require_once '../models/Categoria.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

$categoriaModel = new Categoria($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        
        $categoriaModel->updateCategoria($_POST['id'], $nombre);
    } else {
        
        $categoriaModel->createCategoria($nombre);
    }

    header('Location: ../admin/categorias_list.php');
    exit;
} elseif (isset($_GET['delete'])) {
    
    $categoriaModel->deleteCategoria($_GET['delete']);
    header('Location: ../admin/categorias_list.php');
    exit;
}
?>