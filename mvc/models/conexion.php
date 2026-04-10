<?php
class Database {

    private static $conexion = null;

    public static function getConnection() {
        if (self::$conexion === null) {
            self::$conexion = new PDO(  
                'mysql:host=localhost;dbname=usuarios_prueba;charset=utf8',
                'root', ''
            );
        }
        return self::$conexion;
    }
}
?>
