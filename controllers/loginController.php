<?php
require_once __DIR__ . '/../models/Users.php';

class loginController {

    private UserRepository $userRepository;

    public function __construct() {
        $this->userRepository = new UserRepository();
    }

    // Ver formulario login
    public function getFormLogin($pagina) {
        include_once $pagina;
    }

    // Procesar login
    public function getFormLoginUser() {

        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');

        // =========================
        // VALIDAR CAMPOS VACÍOS
        // =========================
        if (empty($email) || empty($password)) {
            $_SESSION['resultado'] = ['error' => 'Correo y contraseña son obligatorios.'];
            header('Location: ' . SITE_URL . 'index.php?action=getFormLogin');
            exit;
        }

        // =========================
        // BUSCAR USUARIO EN BD
        // =========================
        $usuario = $this->userRepository->buscarPorEmail($email);

        // =========================
        // VERIFICAR CONTRASEÑA
        // =========================
        if (!$usuario || !password_verify($password, $usuario['contrasena'])) {
            $_SESSION['resultado'] = ['error' => 'Correo o contraseña incorrectos.'];
            header('Location: ' . SITE_URL . 'index.php?action=getFormLogin');
            exit;
        }

        // =========================
        // INICIAR SESIÓN
        // =========================
        $_SESSION['usuario'] = [
            'id'     => $usuario['id_usuarios'],
            'nombre' => $usuario['nombre'],
            'email'  => $usuario['email']
        ];

        header('Location: ' . SITE_URL . 'index.php');
        exit;
    }

    // Recuperar contraseña
    public function getFormForgetPassword() {}
}