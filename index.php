<?php
session_start();
require_once __DIR__ . '/models/conexion.php';
require_once __DIR__ . '/models/Users.php';
require_once __DIR__ . '/controllers/loginController.php';
require_once __DIR__ . '/controllers/registerController.php';
require_once __DIR__ . '/controllers/reservaController.php';
require_once __DIR__ . '/controllers/exportarPDFController.php';
require_once __DIR__ . '/config/config.php';


$controllerBase     = new loginController();
$controllerRegister = new registerController();
$controllerReserva = new reservaController();
$controllerExportarPDF = new exportarPDFController();

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
        header('Location: index.php?action=getFormLogin');
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
    if ($_GET['action'] == 'getHabitacionesPorCategoria') {
    $controllerReserva->getHabitacionesPorCategoria();
}

    if ($_GET['action'] == 'createReserva') {
    $controllerReserva->createReserva();
}

    if ($_GET['action'] == 'updateReserva') {

    $controllerReserva->updateReserva();
}
    // Ver formulario
if ($_GET['action'] == 'getFormUpdateReserva') {
    $controllerReserva->getFormUpdateReserva('views/html/update-reserva.php');
}



    if ($_GET['action'] == 'getMisReservas') {
    $controllerReserva->getMisReservas('views/html/mis-reservas.php');
}

    if ($_GET['action'] == 'cancelarReserva') {
    $controllerReserva->cancelarReserva();
}
    if ($_GET['action'] == 'exportarPDF') {
    $controllerExportarPDF->exportarPDF();
}
    if ($_GET['action'] == 'exportarPDFPorReserva') {
    $controllerExportarPDF->exportarPDFPorReserva();
}

} else {
    $controllerBase->getFormLogin('views/html/home.php');
}
?>