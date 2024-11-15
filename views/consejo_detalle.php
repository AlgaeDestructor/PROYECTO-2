<?php
// /views/consejo_detalle.php

session_start(); // Start the session to access session variables
require_once '../config/config.php'; 
require_once '../models/Consejo.php'; 

// Check if 'id' parameter is present in the URL, if not, redirect to the consejos list page
if (!isset($_GET['id'])) {
    header('Location: consejos.php'); 
    exit;
}

// Create an instance of the Consejo model and fetch the specific consejo by id
$consejoModel = new Consejo($pdo);
$consejo = $consejoModel->getConsejoById($_GET['id']);

// If the consejo is not found, display an error message and exit
if (!$consejo) {
    echo "Consejo no encontrado."; 
    exit;
}

// Include the header template for the page
include 'templates/header.php';
?>

<div class="container mt-5">
    <div class="card border-0 shadow" style="background-color: #A5D6A7;">
        <div class="card-body">
            <!-- Display the title of the consejo -->
            <h1 class="card-title" style="color: #0A2E0A;"><?= htmlspecialchars($consejo['titulo']) ?></h1>
            <!-- Display the detailed text of the consejo with newlines converted to <br> tags -->
            <p class="card-text"><?= nl2br(htmlspecialchars($consejo['texto_explicativo'])) ?></p>
            <!-- Display the tags related to the consejo -->
            <p class="card-text"><strong>Etiquetas:</strong> <?= htmlspecialchars($consejo['etiquetas']) ?></p>
        </div>
    </div>
</div>

<!-- Include the footer template -->
<?php include 'templates/footer.php'; ?>
