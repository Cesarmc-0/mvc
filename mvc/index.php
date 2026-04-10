<?php
// session_start();
require_once 'controllers/loginController.php';
require_once 'controllers/registerController.php';
require_once 'config/config.php';

$controllerBase = new loginController();
$contollerRegister = new registerController();

if(isset($_GET['action'])){

    if($_GET['action']== 'getFormRegister'){
        $contollerRegister->getFormRegister('views/html/auth/register.php');
    }

    if($_GET['action']== 'getFormLogin'){
        $controllerBase->getFormLogin('views/html/login.php');
    }

    if($_GET['action']== 'getFormCreateUser'){
        $contollerRegister->getFormCreateUser();
    }
   
}else{
    $controllerBase->getFormLogin('views/html/home.php');
}


