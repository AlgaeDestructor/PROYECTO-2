<!-- /views/user/register.php -->

<?php
require_once dirname(__FILE__, 3) . '/config/config.php';
include dirname(__FILE__, 2) . '/templates/header.php'; // Include the header template
?>

<div class="container mt-4">
    <h2>Registro</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <!-- Show any error message from the session -->
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?> <!-- Clear error session after displaying -->
    <?php endif; ?>

    <form id="registerForm" method="post" action="../../controllers/UserController.php" enctype="multipart/form-data">
        <input type="hidden" name="register" value="1">
        <!-- Name input field -->
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" required class="form-control">
        </div>
        <!-- Surname input field -->
        <div class="form-group">
            <label for="apellidos">Apellidos:</label>
            <input type="text" name="apellidos" id="apellidos" required class="form-control">
        </div>
        <!-- Username input field -->
        <div class="form-group">
            <label for="nombre_usuario">Nombre de Usuario:</label>
            <input type="text" name="nombre_usuario" id="nombre_usuario" required class="form-control">
        </div>
        <!-- Email input field -->
        <div class="form-group">
            <label for="email">Correo Electrónico:</label>
            <input type="email" name="email" id="email" required class="form-control">
        </div>
        <!-- Password input field -->
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" required class="form-control">
        </div>
        <!-- Profile picture input field -->
        <div class="form-group">
            <label for="imagen_perfil">Foto de Perfil:</label>
            <input type="file" name="imagen_perfil" id="imagen_perfil" class="form-control-file" accept="image/*">
        </div>
        <!-- Submit button to register -->
        <button type="submit" class="btn btn-primary">Registrarse</button>
    </form>
</div>

<!-- Client-side validation script -->
<script>
document.getElementById('registerForm').addEventListener('submit', function(event) {
    // Get form input values
    var nombre = document.getElementById('nombre').value.trim();
    var apellidos = document.getElementById('apellidos').value.trim();
    var nombre_usuario = document.getElementById('nombre_usuario').value.trim();
    var email = document.getElementById('email').value.trim();
    var password = document.getElementById('password').value;
    var imagen_perfil = document.getElementById('imagen_perfil').value;

    var errorMessages = []; // Array to store validation error messages

    // Validate required fields
    if (nombre === '') {
        errorMessages.push('El campo "Nombre" es obligatorio.');
    }

    if (apellidos === '') {
        errorMessages.push('El campo "Apellidos" es obligatorio.');
    }

    if (nombre_usuario === '') {
        errorMessages.push('El campo "Nombre de Usuario" es obligatorio.');
    }

    if (email === '') {
        errorMessages.push('El campo "Correo Electrónico" es obligatorio.');
    } else {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            errorMessages.push('Ingresa un correo electrónico válido.');
        }
    }

    if (password === '') {
        errorMessages.push('El campo "Contraseña" es obligatorio.');
    } else if (password.length < 6) {
        errorMessages.push('La contraseña debe tener al menos 6 caracteres.');
    }

    // Add more validations as needed

    if (errorMessages.length > 0) {
        event.preventDefault(); // Prevent form submission

        // Create an error container to display error messages
        var errorContainer = document.createElement('div');
        errorContainer.className = 'alert alert-danger mt-3';
        var errorList = document.createElement('ul');

        // Append each error message to the error list
        errorMessages.forEach(function(message) {
            var listItem = document.createElement('li');
            listItem.textContent = message;
            errorList.appendChild(listItem);
        });

        errorContainer.appendChild(errorList);

        // If there are already existing error messages, replace them
        var existingErrors = document.querySelector('.alert.alert-danger');
        if (existingErrors) {
            existingErrors.parentNode.replaceChild(errorContainer, existingErrors);
        } else {
            var form = document.getElementById('registerForm');
            form.parentNode.insertBefore(errorContainer, form);
        }
    }
});
</script>

<?php include dirname(__FILE__, 2) . '/templates/footer.php'; // Include the footer template ?>
