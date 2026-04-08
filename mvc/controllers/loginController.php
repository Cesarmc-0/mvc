<?php
class loginController{
    public function getFormLogin($pagina){
        include_once $pagina;
    }

    // public function verLogin($login){
    //     include_once $login;
    // }

    // Iniciar sesion
    public function getFormLoginUser(){}

    // Recuperar Contrasena
    public function getFormForgetPassword(){}

    // Verificar Usuario
    public function getFormVerifyUser(){}
}
?>