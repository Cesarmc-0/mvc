<?php
session_start();
require_once __DIR__ . '/models/conexion.php';
require_once __DIR__ . '/models/UserRepository.php';
require_once __DIR__ . '/controllers/loginController.php';
require_once __DIR__ . '/controllers/registerController.php';
require_once __DIR__ . '/controllers/reservaController.php';
require_once __DIR__ . '/config/config.php';

$controllerBase     = new loginController();
$controllerRegister = new registerController();
$controllerReserva = new reservaController();

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
    if ($_GET['action'] == 'logout') {
    session_destroy();
    header('Location: ' . SITE_URL . 'index.php?action=getFormLogin');
    exit;
}

    if ($_GET['action'] == 'getFormCreateReserva') {
    $controllerReserva->getFormCreateReserva('views/html/reserva.php');
}

    if ($_GET['action'] == 'createReserva') {
    $controllerReserva->createReserva();
}
    if ($_GET['action'] == 'getMisReservas') {
    $controllerReserva->getMisReservas('views/html/mis-reservas.php');
}

    if ($_GET['action'] == 'cancelarReserva') {
    $controllerReserva->cancelarReserva();
}

} else {
    $controllerBase->getFormLogin('views/html/home.php');
}