<?php


session_start();
require_once '../config/config.php';
require_once '../models/User.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../views/user/login.php');
    exit;
}

$userModel = new User($pdo);
$userId = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $email = $_POST['email'];

    
    if ($userModel->nombreUsuarioExiste($nombre_usuario, $userId)) {
        $_SESSION['error'] = 'El nombre de usuario ya está en uso.';
        header('Location: ../views/user/profile.php');
        exit;
    }

    
    if (isset($_FILES['imagen_perfil']) && $_FILES['imagen_perfil']['error'] == 0) {
        $imagen = $_FILES['imagen_perfil'];
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
        $extension = pathinfo($imagen['name'], PATHINFO_EXTENSION);

        if (in_array(strtolower($extension), $extensionesPermitidas)) {
            $nombreArchivo = uniqid() . '.' . $extension;
            $rutaDestino = '../uploads/profile_images/' . $nombreArchivo;

            if (!move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
                $_SESSION['error'] = 'Error al subir la imagen.';
                header('Location: ../views/user/profile.php');
                exit;
            }
        } else {
            $_SESSION['error'] = 'Formato de imagen no válido.';
            header('Location: ../views/user/profile.php');
            exit;
        }
    } else {
        
        $nombreArchivo = $userModel->getUserById($userId)['imagen_perfil'];
    }

    
    $userModel->updateUser($userId, [
        'nombre' => $nombre,
        'apellidos' => $apellidos,
        'nombre_usuario' => $nombre_usuario,
        'email' => $email,
        'imagen_perfil' => $nombreArchivo
    ]);

    
    if (!empty($_POST['contrasena_actual']) && !empty($_POST['nueva_contrasena']) && !empty($_POST['confirmar_contrasena'])) {
        $contrasenaActual = $_POST['contrasena_actual'];
        $nuevaContrasena = $_POST['nueva_contrasena'];
        $confirmarContrasena = $_POST['confirmar_contrasena'];

        if ($nuevaContrasena !== $confirmarContrasena) {
            $_SESSION['error'] = 'Las nuevas contraseñas no coinciden.';
            header('Location: ../views/user/profile.php');
            exit;
        }

        if (!$userModel->verificarContrasena($userId, $contrasenaActual)) {
            $_SESSION['error'] = 'La contraseña actual es incorrecta.';
            header('Location: ../views/user/profile.php');
            exit;
        }

        
        $userModel->updatePassword($userId, $nuevaContrasena);
    }

    $_SESSION['success'] = 'Perfil actualizado correctamente.';
    
    $_SESSION['user'] = $userModel->getUserById($userId);
    header('Location: ../views/user/profile.php');
    exit;
}
?>