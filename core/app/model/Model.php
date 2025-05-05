<?php
class Model {
    public static function one($query, $class) {
        if(!$query) return null;
        
        $row = mysqli_fetch_assoc($query);
        if(!$row) return null;
        
        $obj = new $class();
        foreach($row as $key => $value) {
            if(property_exists($obj, $key)) {
                $obj->$key = $value;
            }
        }
        return $obj;
    }

    public static function many($query, $class) {
        $array = array();
        if(!$query) return $array;
        
        while($row = mysqli_fetch_assoc($query)) {
            $obj = new $class();
            foreach($row as $key => $value) {
                if(property_exists($obj, $key)) {
                    $obj->$key = $value;
                }
            }
            $array[] = $obj;
        }
        return $array;
    }
}
?>