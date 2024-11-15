<?php
// /config/config.php

// Database configuration
$host = 'localhost';  
$db   = 'proyecto2';  
$user = 'root';       
$pass = '';           
$charset = 'utf8mb4'; 

// PDO options for error handling and fetch mode
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,     
];

// Try to establish a connection to the MySQL database using PDO
try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass, $options);
} catch (\PDOException $e) {
    // If an error occurs, throw an exception with the error message and code
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Define the base URL for the project (used for routing and generating links)
define('BASE_URL', '/PR2/'); 
?>
