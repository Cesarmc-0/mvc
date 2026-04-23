<?php
require_once __DIR__ . '/../models/conexion.php';
require_once __DIR__ . '/../models/UserRepository.php';

class registerController {

    private UserRepository $userRepository;

    public function __construct() {
        $this->userRepository = new UserRepository();
    }

    // Ver formulario
    public function getFormRegister($pagina) {
        include_once $pagina;
    }

    // Procesar registro
    public function getFormCreateUser() {

        $errores = [];

        $nombre           = trim($_POST['nombre']           ?? '');
        $email            = trim($_POST['email']            ?? '');
        $password         = trim($_POST['password']         ?? '');
        $tipoDocumento    = trim($_POST['tipo_documento_id'] ?? '');
        $numeroDocumento  = trim($_POST['numero_documento'] ?? '');

        // =========================
        // VALIDAR NOMBRE
        // =========================
        if (empty($nombre)) {
            $errores[] = "El nombre es obligatorio.";
        } elseif (strlen($nombre) < 3) {
            $errores[] = "El nombre debe tener mínimo 3 caracteres.";
        } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) {
            $errores[] = "El nombre solo debe contener letras y espacios.";
        }

        // =========================
        // VALIDAR TIPO Y NÚMERO DE DOCUMENTO
        // =========================
        if (empty($tipoDocumento)) {
            $errores[] = "El tipo de documento es obligatorio.";
        }

        if (empty($numeroDocumento)) {
            $errores[] = "El número de documento es obligatorio.";
        } elseif (!preg_match('/^[a-zA-Z0-9]+$/', $numeroDocumento)) {
            $errores[] = "El número de documento no es válido.";
        }

        // =========================
        // VALIDAR EMAIL
        // =========================
        if (empty($email)) {
            $errores[] = "El correo es obligatorio.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El correo no es válido.";
        }

        // =========================
        // VALIDAR CONTRASEÑA
        // =========================
        if (empty($password)) {
            $errores[] = "La contraseña es obligatoria.";
        } elseif (strlen($password) < 8) {
            $errores[] = "Debe tener mínimo 8 caracteres.";
        } elseif (!preg_match('/[A-Z]/', $password)) {
            $errores[] = "Debe tener una mayúscula.";
        } elseif (!preg_match('/[a-z]/', $password)) {
            $errores[] = "Debe tener una minúscula.";
        } elseif (!preg_match('/[0-9]/', $password)) {
            $errores[] = "Debe tener un número.";
        } elseif (!preg_match('/[\W_]/', $password)) {
            $errores[] = "Debe tener un carácter especial.";
        }

        // =========================
        // VALIDAR DUPLICADOS (solo si no hay errores previos)
        // =========================
        if (empty($errores)) {

            if ($this->userRepository->emailExiste($email)) {
                $errores[] = "Este correo ya está registrado.";
            }

            if ($this->userRepository->documentoExiste($numeroDocumento)) {
                $errores[] = "Este documento ya está registrado.";
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
        $creado = $this->userRepository->crear([
            'nombre'           => $nombre,
            'tipo_documento_id'=> $tipoDocumento,
            'numero_documento' => $numeroDocumento,
            'email'            => $email,
            'contrasena'       => $password
        ]);

        if ($creado) {
            return ['success' => 'Usuario creado correctamente'];
        }

        return ['error' => 'Error al registrar usuario.'];
    }
}