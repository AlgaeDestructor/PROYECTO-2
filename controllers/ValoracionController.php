<?php

session_start();
require_once '../config/config.php';
require_once '../models/Valoracion.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../views/user/login.php');
    exit;
}

$valoracionModel = new Valoracion($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_evento = $_POST['id_evento'];
    $id_usuario = $_SESSION['user']['id'];
    $puntuacion = intval($_POST['puntuacion']);

    if ($puntuacion >= 1 && $puntuacion <= 5) {
        $valoracionModel->addOrUpdateValoracion($id_evento, $id_usuario, $puntuacion);
    }

    header('Location: ../event.php?id=' . $id_evento);
    exit;
}
?>