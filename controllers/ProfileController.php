<?php
// /controllers/ProfileController.php

session_start();
require_once '../config/config.php';
require_once '../models/User.php';

// Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION['user'])) {
    header('Location: ../views/user/login.php');
    exit;
}

$userModel = new User($pdo);
$userId = $_SESSION['user']['id']; // Get the logged-in user's ID

// Handle profile update form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the updated user data from the form
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $email = $_POST['email'];

    // Check if the username already exists (for another user)
    if ($userModel->nombreUsuarioExiste($nombre_usuario, $userId)) {
        $_SESSION['error'] = 'El nombre de usuario ya está en uso.'; // Set error if the username exists
        header('Location: ../views/user/profile.php');
        exit;
    }

    // Handle the profile image upload
    if (isset($_FILES['imagen_perfil']) && $_FILES['imagen_perfil']['error'] == 0) {
        $imagen = $_FILES['imagen_perfil'];
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
        $extension = pathinfo($imagen['name'], PATHINFO_EXTENSION);

        // Check if the image extension is allowed
        if (in_array(strtolower($extension), $extensionesPermitidas)) {
            $nombreArchivo = uniqid() . '.' . $extension;
            $rutaDestino = '../uploads/profile_images/' . $nombreArchivo;

            // Try to move the uploaded file to the target location
            if (!move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
                $_SESSION['error'] = 'Error al subir la imagen.'; // Set error if file upload fails
                header('Location: ../views/user/profile.php');
                exit;
            }
        } else {
            $_SESSION['error'] = 'Formato de imagen no válido.'; // Set error if the image format is invalid
            header('Location: ../views/user/profile.php');
            exit;
        }
    } else {
        // If no new image is uploaded, keep the existing profile image
        $nombreArchivo = $userModel->getUserById($userId)['imagen_perfil'];
    }

    // Update the user's basic information (name, surname, username, email, and profile image)
    $userModel->updateUser($userId, [
        'nombre' => $nombre,
        'apellidos' => $apellidos,
        'nombre_usuario' => $nombre_usuario,
        'email' => $email,
        'imagen_perfil' => $nombreArchivo
    ]);

    // Handle password update (if provided)
    if (!empty($_POST['contrasena_actual']) && !empty($_POST['nueva_contrasena']) && !empty($_POST['confirmar_contrasena'])) {
        $contrasenaActual = $_POST['contrasena_actual'];
        $nuevaContrasena = $_POST['nueva_contrasena'];
        $confirmarContrasena = $_POST['confirmar_contrasena'];

        // Check if the new passwords match
        if ($nuevaContrasena !== $confirmarContrasena) {
            $_SESSION['error'] = 'Las nuevas contraseñas no coinciden.'; // Set error if passwords don't match
            header('Location: ../views/user/profile.php');
            exit;
        }

        // Check if the current password is correct
        if (!$userModel->verificarContrasena($userId, $contrasenaActual)) {
            $_SESSION['error'] = 'La contraseña actual es incorrecta.'; // Set error if the current password is wrong
            header('Location: ../views/user/profile.php');
            exit;
        }

        // Update the password in the database
        $userModel->updatePassword($userId, $nuevaContrasena);
    }

    // Set a success message and update the session with the new user data
    $_SESSION['success'] = 'Perfil actualizado correctamente.';
    $_SESSION['user'] = $userModel->getUserById($userId);

    // Redirect the user back to the profile page
    header('Location: ../views/user/profile.php');
    exit;
}
?>
