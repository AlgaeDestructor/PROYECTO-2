<?php
// /views/consejos.php

session_start(); // Start the session to access session variables
require_once '../config/config.php'; 
require_once '../models/Consejo.php'; 

// Create an instance of the Consejo model and fetch all consejos from the database
$consejoModel = new Consejo($pdo);
$consejos = $consejoModel->getAllConsejos(); 

// Include the header template for the page
include 'templates/header.php';
?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Consejos Sostenibles</h1>
    <div class="row">
        <!-- Loop through all the consejos and display them in cards -->
        <?php foreach($consejos as $consejo): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow" style="background-color: #A5D6A7;">
                    <div class="card-body d-flex flex-column">
                        <!-- Display the title of the consejo -->
                        <h2 class="card-title" style="color: #0A2E0A;"><?= htmlspecialchars($consejo['titulo']) ?></h2>
                        <!-- Display a brief description of the consejo (first 100 characters) -->
                        <p class="card-text"><?= htmlspecialchars(substr($consejo['descripcion_breve'], 0, 100)) ?>...</p>
                        <!-- Link to the full detail of the consejo -->
                        <a href="consejo_detalle.php?id=<?= $consejo['id'] ?>" class="btn btn-primary mt-auto">Leer más</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Include the footer template -->
<?php include 'templates/footer.php'; ?>
