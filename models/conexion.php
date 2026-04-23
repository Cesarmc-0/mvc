<?php

class Database {

    private static $conexion = null;

    public static function getConnection() {

        if (self::$conexion === null) {

            try {
                self::$conexion = new PDO(
                    'mysql:host=localhost;dbname=usuarios_prueba;charset=utf8',
                    'root',
                    ''
                );
                // Si hay un error, PHP lanza una excepción
                self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (PDOException $e) {
                die('Error de conexión: ' . $e->getMessage());
            }
        }

        return self::$conexion;
    }
}
?>