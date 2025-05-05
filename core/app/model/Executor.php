<?php
class Executor {
    public static function doit($sql) {
        $con = Database::getCon();
        $result = mysqli_query($con, $sql);
        
        if(mysqli_error($con)) {
            error_log("Error en consulta SQL: " . mysqli_error($con));
            return false;
        }
        
        return array($result, mysqli_insert_id($con));
    }
}
?>