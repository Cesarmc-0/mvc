<?php
require_once __DIR__ . '/../models/conexion.php';

    class registerController{
        // Ver register
        public function getFormRegister($pagina){
            include_once $pagina;
        }

        // Crear usuario
    public function getFormCreateUser(){
        $errores = [];
        $conexion = Database::getConnection();

        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // =========================
        // VALIDAR NOMBRE
        // =========================
        if (empty($nombre)) {
            $errores[] = "El nombre es obligatorio.";
        }

        if (!empty($nombre) && strlen($nombre) < 3) {
            $errores[] = "El nombre debe tener mínimo 3 caracteres.";
        }

        if (!empty($nombre) && !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) {
            $errores[] = "El nombre solo debe contener letras y espacios.";
        }

        // =========================
        // VALIDAR EMAIL
        // =========================
        if (empty($email)) {
            $errores[] = "El correo es obligatorio.";
        }

        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El correo no es válido.";
        }

        // =========================
        // VALIDAR CONTRASEÑA
        // =========================
        if (empty($password)) {
            $errores[] = "La contraseña es obligatoria.";
        }

        if (!empty($password) && strlen($password) < 8) {
            $errores[] = "Debe tener mínimo 8 caracteres.";
        }

        if (!empty($password) && !preg_match('/[A-Z]/', $password)) {
            $errores[] = "Debe tener una mayúscula.";
        }

        if (!empty($password) && !preg_match('/[a-z]/', $password)) {
            $errores[] = "Debe tener una minúscula.";
        }

        if (!empty($password) && !preg_match('/[0-9]/', $password)) {
            $errores[] = "Debe tener un número.";
        }

        if (!empty($password) && !preg_match('/[\W_]/', $password)) {
            $errores[] = "Debe tener un carácter especial.";
        }

        // =========================
        // VALIDAR EMAIL DUPLICADO
        // =========================
        if (!empty($email)) {
            $sql = "SELECT id_usuario FROM usuario WHERE email = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$email]);

            if ($stmt->fetch()) {
                $errores[] = "Este correo ya está registrado.";
            }
        }

        // =========================
        // RETORNAR ERRORES
        // =========================
        if (!empty($errores)) {
            return $errores;
        }

        // =========================
        // INSERTAR USUARIO
        // =========================
        try {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO usuario(nombre, email, contraseña) VALUES (?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$nombre, $email, $passwordHash]);

            return ["Usuario creado correctamente"];

        } catch (PDOException $e) {
            return ["Error al registrar usuario: " . $e->getMessage()];
        }
    }
}
    
?>