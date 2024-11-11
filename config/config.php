<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'proyecto2');
define('DB_USER', 'root');
define('DB_PASS', '');

// Conexión PDO
try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}

define('BASE_URL', '/lemax1/');
?>
