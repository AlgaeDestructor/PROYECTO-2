<?php 
// /models/User.php

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Register a new user
    public function register($nombre, $apellidos, $nombre_usuario, $email, $password, $imagen_perfil = null) {
        // Check if the username or email already exists
        $sql = "SELECT COUNT(*) FROM users WHERE nombre_usuario = ? OR email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$nombre_usuario, $email]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            // The username or email already exists
            return false;
        }

        // Insert the new user
        $sql = "INSERT INTO users (nombre, apellidos, nombre_usuario, email, contrasena, imagen_perfil) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        return $stmt->execute([$nombre, $apellidos, $nombre_usuario, $email, $hashed_password, $imagen_perfil]);
    }


    // LogIn
    public function login($nombre_usuario, $password) {
        $sql = "SELECT * FROM users WHERE nombre_usuario = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$nombre_usuario]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['contrasena'])) {
            return $user;
        }
        return false;
    }

    // Get User by ID
    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Check if the username already exists (excluding the current user)
    public function nombreUsuarioExiste($nombre_usuario, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM users WHERE nombre_usuario = ?";
        $params = [$nombre_usuario];

        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    // Update user information (for both the user and the administrator)
    public function updateUser($user_id, $data) {
        $sql = "UPDATE users SET nombre = ?, apellidos = ?, nombre_usuario = ?, email = ?, imagen_perfil = ?";

        // If the administrator is updating, they can change the role
        if (isset($data['rol'])) {
            $sql .= ", rol = ?";
            $params = [
                $data['nombre'],
                $data['apellidos'],
                $data['nombre_usuario'],
                $data['email'],
                $data['imagen_perfil'],
                $data['rol'],
                $user_id
            ];
        } else {
            $params = [
                $data['nombre'],
                $data['apellidos'],
                $data['nombre_usuario'],
                $data['email'],
                $data['imagen_perfil'],
                $user_id
            ];
        }

        $sql .= " WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    // Verify current password
    public function verificarContrasena($id, $contrasena) {
        $user = $this->getUserById($id);
        return password_verify($contrasena, $user['contrasena']);
    }

    // Update password
    public function updatePassword($id, $nuevaContrasena) {
        $hashedPassword = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET contrasena = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$hashedPassword, $id]);
    }

    // Admin creates a new user
    public function adminCreateUser($data, $password) {
        $sql = "INSERT INTO users (nombre, apellidos, nombre_usuario, email, contrasena, rol, imagen_perfil) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        return $stmt->execute([
            $data['nombre'],
            $data['apellidos'],
            $data['nombre_usuario'],
            $data['email'],
            $hashed_password,
            $data['rol'],
            $data['imagen_perfil']
        ]);
    }

    // Admin updates a user (including role)
    public function adminUpdateUser($user_id, $data) {
        $sql = "UPDATE users SET nombre = ?, apellidos = ?, nombre_usuario = ?, email = ?, rol = ?, imagen_perfil = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nombre'],
            $data['apellidos'],
            $data['nombre_usuario'],
            $data['email'],
            $data['rol'],
            $data['imagen_perfil'],
            $user_id
        ]);
    }

    // Delete a user
    public function deleteUser($user_id) {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$user_id]);
    }

    // Get all users
    public function getAllUsers() {
        $sql = "SELECT * FROM users";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}
?>
