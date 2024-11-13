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


    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }


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


    public function updateUser($user_id, $data) {
        $sql = "UPDATE users SET nombre = ?, apellidos = ?, nombre_usuario = ?, email = ?, imagen_perfil = ?";


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


    public function verificarContrasena($id, $contrasena) {
        $user = $this->getUserById($id);
        return password_verify($contrasena, $user['contrasena']);
    }


    public function updatePassword($id, $nuevaContrasena) {
        $hashedPassword = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET contrasena = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$hashedPassword, $id]);
    }


    public function adminCreateUser($data, $password) {
        $sql = "INSERT INTO users (nombre, apellidos, nombre_usuario, email, contrasena, rol) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        return $stmt->execute([
            $data['nombre'],
            $data['apellidos'],
            $data['nombre_usuario'],
            $data['email'],
            $hashed_password,
            $data['rol']
        ]);
    }


    public function adminUpdateUser($user_id, $data) {
        $sql = "UPDATE users SET nombre = ?, apellidos = ?, nombre_usuario = ?, email = ?, rol = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nombre'],
            $data['apellidos'],
            $data['nombre_usuario'],
            $data['email'],
            $data['rol'],
            $user_id
        ]);
    }


    public function deleteUser($user_id) {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$user_id]);
    }


    public function getAllUsers() {
        $sql = "SELECT * FROM users";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}
?>
