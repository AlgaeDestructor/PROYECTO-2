<?php
// /controllers/UserController.php

session_start();
require_once '../config/config.php';
require_once '../models/User.php';

$userModel = new User($pdo);

// Handle user registration and login requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['register'])) {
        // Handle user registration
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $nombre_usuario = $_POST['nombre_usuario'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $imagen_perfil = null;

        // Handle profile image upload (if provided)
        if (isset($_FILES['imagen_perfil']) && $_FILES['imagen_perfil']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['imagen_perfil']['tmp_name'];
            $fileName = $_FILES['imagen_perfil']['name'];
            $fileSize = $_FILES['imagen_perfil']['size'];
            $fileType = $_FILES['imagen_perfil']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // Allowed file extensions
            $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');

            // Check if the file extension is allowed
            if (in_array($fileExtension, $allowedfileExtensions)) {
                // Clean up the file name
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

                // Upload directory
                $uploadFileDir = '../uploads/profile_images/';
                $dest_path = $uploadFileDir . $newFileName;

                // Move the uploaded file to the target directory
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $imagen_perfil = $newFileName;
                } else {
                    $_SESSION['error'] = 'Hubo un error al subir la imagen. Por favor, inténtalo de nuevo.';
                    header('Location: ../views/user/register.php');
                    exit;
                }
            } else {
                $_SESSION['error'] = 'Formato de imagen no permitido. Sólo se permiten archivos JPG, JPEG, PNG y GIF.';
                header('Location: ../views/user/register.php');
                exit;
            }
        }

        // Register the user
        $resultado = $userModel->register($nombre, $apellidos, $nombre_usuario, $email, $password, $imagen_perfil);

        // Check registration result
        if ($resultado) {
            $_SESSION['success'] = 'Registro exitoso. Ahora puedes iniciar sesión.';
            header('Location: ../views/user/login.php');
        } else {
            $_SESSION['error'] = 'El nombre de usuario o el correo electrónico ya están en uso. Por favor, elige otros.';
            header('Location: ../views/user/register.php');
        }
        exit;
    } elseif (isset($_POST['login'])) {
        // Handle user login
        $nombre_usuario = $_POST['nombre_usuario'];
        $password = $_POST['password'];

        // Attempt to log in the user
        $user = $userModel->login($nombre_usuario, $password);
        if ($user) {
            // Set user session data and redirect to the homepage
            $_SESSION['user'] = $user;
            header('Location: ../index.php');
        } else {
            // Login failed, show error
            $_SESSION['error'] = 'Nombre de usuario o contraseña incorrectos';
            header('Location: ../views/user/login.php');
        }
    }
}
?>
