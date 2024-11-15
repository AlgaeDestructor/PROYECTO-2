<?php
// /admin/dashboard.php

// Start the session
session_start();

// Include configuration file
require_once '../config/config.php';

// Check if the user is an admin, if not, redirect to the homepage
if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != 'admin') {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}

// Include the header template
include '../views/templates/header.php';
?>

<div class="container mt-5">
    <!-- Dashboard Title -->
    <h1 class="text-center mb-4">Panel de Administración</h1>

    <div class="row">
        <!-- Card to manage events -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 text-center border-0 shadow" style="background-color: #A5D6A7;">
                <div class="card-body">
                    <h2 class="card-title"><a href="events_list.php" class="text-decoration-none" style="color: #0A2E0A;">Gestionar Eventos</a></h2>
                    <p class="card-text">Crea, edita o elimina eventos.</p>
                    <a href="events_list.php" class="btn btn-primary">Ir a Eventos</a>
                </div>
            </div>
        </div>
        
        <!-- Card to manage users -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 text-center border-0 shadow" style="background-color: #A5D6A7;">
                <div class="card-body">
                    <h2 class="card-title"><a href="users_list.php" class="text-decoration-none" style="color: #0A2E0A;">Gestionar Usuarios</a></h2>
                    <p class="card-text">Administra los usuarios registrados en la plataforma.</p>
                    <a href="users_list.php" class="btn btn-primary">Ir a Usuarios</a>
                </div>
            </div>
        </div>

        <!-- Card to manage comments -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 text-center border-0 shadow" style="background-color: #A5D6A7;">
                <div class="card-body">
                    <h2 class="card-title"><a href="comments_list.php" class="text-decoration-none" style="color: #0A2E0A;">Gestionar Comentarios</a></h2>
                    <p class="card-text">Modera los comentarios realizados en los eventos.</p>
                    <a href="comments_list.php" class="btn btn-primary">Ir a Comentarios</a>
                </div>
            </div>
        </div>

        <!-- Card to manage sustainability tips -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 text-center border-0 shadow" style="background-color: #A5D6A7;">
                <div class="card-body">
                    <h2 class="card-title"><a href="consejos_list.php" class="text-decoration-none" style="color: #0A2E0A;">Gestionar Consejos</a></h2>
                    <p class="card-text">Administra los consejos de sostenibilidad.</p>
                    <a href="consejos_list.php" class="btn btn-primary">Ir a Consejos</a>
                </div>
            </div>
        </div>

        <!-- Card to manage classified ads -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 text-center border-0 shadow" style="background-color: #A5D6A7;">
                <div class="card-body">
                    <h2 class="card-title"><a href="anuncios_list.php" class="text-decoration-none" style="color: #0A2E0A;">Gestionar Anuncios</a></h2>
                    <p class="card-text">Administra los anuncios clasificados.</p>
                    <a href="anuncios_list.php" class="btn btn-primary">Ir a Anuncios</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
// Include the footer template
include '../views/templates/footer.php'; 
?>
