<?php
// /views/anuncio_detalle.php

session_start(); // Start the session to access user data
require_once '../config/config.php'; 
require_once '../models/Anuncio.php'; 

// Check if 'id' is passed in the URL, if not redirect to the anuncios page
if (!isset($_GET['id'])) {
    header('Location: anuncios.php'); 
    exit;
}

// Create an instance of the Anuncio model and fetch the anuncio by its ID
$anuncioModel = new Anuncio($pdo);
$anuncio = $anuncioModel->getAnuncioById($_GET['id']); 

// Check if the anuncio exists, is published, and is not marked for soft delete
if (!$anuncio || $anuncio['estado'] != 'publicado' || $anuncio['soft_delete'] == 1) {
    echo "Anuncio no encontrado o no disponible."; 
    exit;
}

// Include the header template
include 'templates/header.php';
?>

<!-- Display the details of the anuncio -->
<div class="container mt-5">
    <div class="card border-0 shadow" style="background-color: #A5D6A7;">
        <div class="card-body">
            <!-- Display the title of the anuncio -->
            <h1 class="card-title" style="color: #0A2E0A;"><?= htmlspecialchars($anuncio['titulo']) ?></h1>
            <!-- Display the description of the anuncio -->
            <p class="card-text"><?= htmlspecialchars($anuncio['descripcion']) ?></p>
            <!-- Display the category of the anuncio -->
            <p class="card-text"><strong>Categoría:</strong> <?= htmlspecialchars($anuncio['categoria_nombre']) ?></p>
        </div>
    </div>
</div>

<!-- Include the footer template -->
<?php include 'templates/footer.php'; ?>
