<?php

session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

include '../views/templates/header.php';
?>

<div class="container">
    <h1>Panel de Administración</h1>
    <ul class="list-group">
        <li class="list-group-item"><a href="events_list.php">Gestión de Eventos</a></li>
        <li class="list-group-item"><a href="comments_list.php">Gestión de Comentarios</a></li>
        <li class="list-group-item"><a href="users_list.php">Gestión de Usuarios</a></li>
    </ul>
</div>

<?php include '../views/templates/footer.php'; 
