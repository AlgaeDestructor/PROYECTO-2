<?php


session_start();
require_once '../config/config.php';
require_once '../models/Consejo.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

$consejoModel = new Consejo($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'titulo' => $_POST['titulo'],
        'descripcion_breve' => $_POST['descripcion_breve'],
        'texto_explicativo' => $_POST['texto_explicativo'],
        'etiquetas' => $_POST['etiquetas']
    ];

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        
        $consejoModel->updateConsejo($_POST['id'], $data);
    } else {
        
        $consejoModel->createConsejo($data);
    }

    header('Location: ../admin/consejos_list.php');
    exit;
} elseif (isset($_GET['delete'])) {
    
    $consejoModel->deleteConsejo($_GET['delete']);
    header('Location: ../admin/consejos_list.php');
    exit;
}
?>