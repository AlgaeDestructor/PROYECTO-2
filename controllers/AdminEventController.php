<?php


session_start();
require_once '../config/config.php';
require_once '../models/Event.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

$eventModel = new Event($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'titulo' => $_POST['titulo'],
        'descripcion' => $_POST['descripcion'],
        'tipo' => $_POST['tipo'],
        'fecha' => $_POST['fecha'],
        'hora' => $_POST['hora'],
        'latitud' => $_POST['latitud'],
        'longitud' => $_POST['longitud'],
    ];

    $imagenesSubidas = [];
    if (isset($_FILES['imagenes'])) {
        $totalArchivos = count($_FILES['imagenes']['name']);
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];

        for ($i = 0; $i < $totalArchivos; $i++) {
            $nombreArchivo = $_FILES['imagenes']['name'][$i];
            $tmpArchivo = $_FILES['imagenes']['tmp_name'][$i];
            $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);

            if (in_array(strtolower($extension), $extensionesPermitidas)) {
                $nuevoNombre = uniqid() . '.' . $extension;
                $rutaDestino = '../uploads/event_images/' . $nuevoNombre;

                if (move_uploaded_file($tmpArchivo, $rutaDestino)) {
                    $imagenesSubidas[] = $nuevoNombre;
                }
            }
        }
    }


    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $eventoExistente = $eventModel->getEventById($_POST['id']);
        $imagenesExistentes = $eventoExistente['imagenes'] ? explode(',', $eventoExistente['imagenes']) : [];
        $imagenesTotales = array_merge($imagenesExistentes, $imagenesSubidas);
    } else {
        $imagenesTotales = $imagenesSubidas;
    }

    $data['imagenes'] = implode(',', $imagenesTotales);

    if (isset($_POST['id']) && !empty($_POST['id'])) {

        $eventModel->updateEvent($_POST['id'], $data);
    } else {

        $eventModel->createEvent($data);
    }

    header('Location: ../admin/events_list.php');
    exit;
} elseif (isset($_GET['delete'])) {

    $eventModel->deleteEvent($_GET['delete']);
    header('Location: ../admin/events_list.php');
    exit;
}
?>