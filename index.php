<?php
session_start();
require_once __DIR__ . '/models/conexion.php';
require_once __DIR__ . '/models/UserRepository.php';
require_once __DIR__ . '/controllers/loginController.php';
require_once __DIR__ . '/controllers/registerController.php';
require_once __DIR__ . '/config/config.php';

$controllerBase     = new loginController();
$controllerRegister = new registerController();

if (isset($_GET['action'])) {

    if ($_GET['action'] == 'getFormRegister') {
        $controllerRegister->getFormRegister('views/html/auth/register.php');
    }

    if ($_GET['action'] == 'getFormLogin') {
        $controllerBase->getFormLogin('views/html/login.php');
    }

    if ($_GET['action'] == 'getFormCreateUser') {
        $resultado = $controllerRegister->getFormCreateUser();
        $_SESSION['resultado'] = $resultado;
        header('Location: index.php?action=getFormRegister');
        exit;
    }
    if ($_GET['action'] == 'getFormLoginUser') {
    $controllerBase->getFormLoginUser();
}

} else {
    $controllerBase->getFormLogin('views/html/home.php');
}