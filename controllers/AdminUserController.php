<?php


session_start();
require_once '../config/config.php';
require_once '../models/User.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

$userModel = new User($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $data = [
        'nombre' => $_POST['nombre'],
        'apellidos' => $_POST['apellidos'],
        'nombre_usuario' => $_POST['nombre_usuario'],
        'email' => $_POST['email'],
        'rol' => $_POST['rol']
    ];

    if (isset($_POST['user_id'])) {

        $user_id = $_POST['user_id'];
        $userModel->updateUser($user_id, $data);
    } else {

        $password = $_POST['password'];
        $userModel->adminCreateUser($data, $password);
    }

    header('Location: ../admin/users_list.php');
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['delete'])) {

    $user_id = $_GET['delete'];
    $userModel->deleteUser($user_id);
    header('Location: ../admin/users_list.php');
    exit;
}
?>