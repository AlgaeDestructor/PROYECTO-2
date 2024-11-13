<?php

session_start();
require_once '../config/config.php';
require_once '../models/Anuncio.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != 'admin') {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}

$anuncioModel = new Anuncio($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'titulo' => $_POST['titulo'],
        'descripcion' => $_POST['descripcion'],
        'categoria_id' => $_POST['categoria_id'],
        'estado' => $_POST['estado'],
    ];

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $anuncioModel->updateAnuncio($_POST['id'], $data);
    } else {
        $data['id_usuario'] = $_SESSION['user']['id'];
        $anuncioModel->createAnuncio($data);
    }

    header('Location: ' . BASE_URL . 'admin/anuncios_list.php');
    exit;
} elseif (isset($_GET['delete'])) {
    $anuncioModel->deleteAnuncio($_GET['delete']);
    header('Location: ' . BASE_URL . 'admin/anuncios_list.php');
    exit;
}
?>