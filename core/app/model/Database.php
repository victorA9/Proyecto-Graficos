<?php
class Database {
    private static $con = null;
    
    public static function getCon() {
        if(self::$con === null) {
            // Configuración para XAMPP (valores por defecto)
            self::$con = mysqli_connect("localhost", "root", "", "businesslit1");
            
            if(!self::$con) {
                die("Error de conexión: " . mysqli_connect_error());
            }
            
            mysqli_set_charset(self::$con, "utf8");
        }
        
        return self::$con;
    }
}
?>