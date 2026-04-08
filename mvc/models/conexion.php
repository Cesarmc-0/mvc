<?php
class Database {

    private static $conexion = null;

    public static function getConnection() {
        if (self::$conexion === null) {
            self::$conexion = new PDO(  
                'mysql:host=localhost;dbname=hotel_lumiere;charset=utf8',
                'root', ''
            );
        }
        return self::$conexion;
    }
}
?>
