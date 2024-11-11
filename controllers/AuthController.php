<?php
session_start();
require_once '../config/config.php';
require_once '../models/User.php';

$userModel = new User($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $password = $_POST['password'];
    
    $user = $userModel->login($nombre_usuario, $password);
    
    if ($user) {
        $_SESSION['user'] = $user;
        header('Location: ../index.php');
    } else {
        echo "Usuario o contraseña incorrectos";
    }
}
?>
