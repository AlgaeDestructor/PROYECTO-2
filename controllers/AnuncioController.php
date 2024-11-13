<?php


session_start();
require_once '../config/config.php';
require_once '../models/Anuncio.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../views/user/login.php');
    exit;
}

$anuncioModel = new Anuncio($pdo);

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

    if (isset($_POST['id']) && !empty($_POST['id'])) {

        $anuncioModel->updateAnuncio($_POST['id'], $data);
    } else {

        $anuncioModel->createAnuncio($data);
    }

    header('Location: ../user/mis_anuncios.php');
    exit;
} elseif (isset($_GET['delete'])) {

    $anuncioModel->DeleteAnuncio($_GET['delete']);
    header('Location: ../user/mis_anuncios.php');
    exit;
}
?>