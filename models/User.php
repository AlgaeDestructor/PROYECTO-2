<?php
class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function register($nombre, $apellidos, $nombre_usuario, $email, $password) {
        $sql = "INSERT INTO users (nombre, apellidos, nombre_usuario, email, contrasena) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        return $stmt->execute([$nombre, $apellidos, $nombre_usuario, $email, $hashed_password]);
    }
}
