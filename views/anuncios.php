<?php
// /views/anuncios.php

session_start(); // Start the session to access session variables
require_once '../config/config.php'; // Include the configuration file for the database connection
require_once '../models/Anuncio.php'; // Include the Anuncio model to interact with the database

// Create an instance of the Anuncio model and fetch public anuncios
$anuncioModel = new Anuncio($pdo);
$anuncios = $anuncioModel->getPublicAnuncios(); // Fetch all public anuncios from the database

// Include the header template
include 'templates/header.php';
?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Anuncios</h1>
    <?php if (count($anuncios) > 0): ?>
        <!-- If there are anuncios, display them in a grid -->
        <div class="row">
            <?php foreach($anuncios as $anuncio): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <!-- Display each anuncio inside a card -->
                    <div class="card h-100 border-0 shadow" style="background-color: #A5D6A7;">
                        <div class="card-body d-flex flex-column">
                            <!-- Display the title of the anuncio as a clickable link -->
                            <h2 class="card-title">
                                <a href="anuncio_detalle.php?id=<?= $anuncio['id'] ?>" class="text-decoration-none" style="color: #0A2E0A;">
                                    <?= htmlspecialchars($anuncio['titulo']) ?>
                                </a>
                            </h2>
                            <!-- Display the truncated description of the anuncio -->
                            <p class="card-text"><?= htmlspecialchars(substr($anuncio['descripcion'], 0, 100)) ?>...</p>
                            <!-- Display the category of the anuncio -->
                            <p class="card-text"><strong>Categoría:</strong> <?= htmlspecialchars($anuncio['categoria_nombre']) ?></p>
                            <!-- Provide a button to view the details of the anuncio -->
                            <a href="anuncio_detalle.php?id=<?= $anuncio['id'] ?>" class="btn btn-primary mt-auto">Ver Detalles</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- Display a message if no anuncios are available -->
        <p class="text-center">No hay anuncios disponibles.</p>
    <?php endif; ?>
</div>

<!-- Include the footer template -->
<?php include 'templates/footer.php'; ?>
