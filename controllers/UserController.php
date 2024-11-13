<?php


session_start();
require_once '../config/config.php';
require_once '../models/User.php';

$userModel = new User($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['register'])) {
        
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $nombre_usuario = $_POST['nombre_usuario'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $userModel->register($nombre, $apellidos, $nombre_usuario, $email, $password);
        header('Location: ../views/user/login.php');
    } elseif (isset($_POST['login'])) {
       
        $nombre_usuario = $_POST['nombre_usuario'];
        $password = $_POST['password'];

        $user = $userModel->login($nombre_usuario, $password);
        if ($user) {
            $_SESSION['user'] = $user;
            header('Location: ../index.php');
        } else {
            $error = 'Nombre de usuario o contraseña incorrectos';
            require '../views/user/login.php';
        }
    }
}
?>