<?php
class UserData {
    public static $tablename = "user";

    public $id;
    public $name;
    public $lastname;
    public $username;
    public $email;
    public $password;
    public $kind;
    public $stock_id;
    public $facial_data;
    public $image;
    public $comision;
    public $status;
    public $created_at;

    public function __construct() {
        $this->id = null;
        $this->name = "";
        $this->lastname = "";
        $this->username = "";
        $this->email = "";
        $this->password = "";
        $this->kind = 0;
        $this->stock_id = null;
        $this->facial_data = null;
        $this->image = "";
        $this->comision = 0;
        $this->status = 1;
        $this->created_at = "NOW()";
    }

    public function getStock() {
        if ($this->stock_id) {
            require_once "core/app/model/StockData.php";
            return StockData::getById($this->stock_id);
        }
        return null;
    }

    public function add() {
        require_once 'Database.php';
        require_once 'Executor.php';
        
        $facial_data = "NULL";
        if (!empty($this->facial_data)) {
            $descriptor = $this->normalizeFacialData($this->facial_data);
            
            if ($descriptor !== null) {
                $this->facial_data = json_encode($descriptor);
                $facial_data = "'" . mysqli_real_escape_string(Database::getCon(), $this->facial_data) . "'";
            }
        }
    
        $sql = "INSERT INTO " . self::$tablename . " 
                (name, lastname, username, email, password, kind, stock_id, facial_data, image, comision, status, created_at) 
                VALUES 
                ('" . mysqli_real_escape_string(Database::getCon(), $this->name) . "', 
                '" . mysqli_real_escape_string(Database::getCon(), $this->lastname) . "', 
                '" . mysqli_real_escape_string(Database::getCon(), $this->username) . "', 
                '" . mysqli_real_escape_string(Database::getCon(), $this->email) . "', 
                '" . mysqli_real_escape_string(Database::getCon(), $this->password) . "', 
                " . intval($this->kind) . ", 
                " . ($this->stock_id ? intval($this->stock_id) : "NULL") . ", 
                " . $facial_data . ", 
                '" . mysqli_real_escape_string(Database::getCon(), $this->image) . "', 
                " . floatval($this->comision) . ", 
                " . intval($this->status) . ", 
                " . $this->created_at . ")";

        return Executor::doit($sql);
    }

    private function normalizeFacialData($data) {
        // Si es un string JSON
        if (is_string($data)) {
            $decoded = json_decode($data, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $data = $decoded;
            }
        }
        
        // Asegurar que es un array con exactamente 128 valores
        if (!is_array($data) || count($data) !== 128) {
            return null;
        }
        
        // Normalizar el vector para magnitud unitaria
        $sum = 0.0;
        foreach ($data as $value) {
            $sum += $value * $value;
        }
        $magnitude = sqrt($sum);
        
        if ($magnitude > 0) {
            foreach ($data as &$value) {
                $value /= $magnitude;
            }
        }
        
        return $data;
    }

    public static function getById($id) {
        $sql = "SELECT * FROM " . self::$tablename . " WHERE id = " . intval($id) . " LIMIT 1";
        $query = Executor::doit($sql);
        return Model::one($query[0], new UserData());
    }

    public static function getAll() {
        $sql = "SELECT * FROM " . self::$tablename;
        $query = Executor::doit($sql);
        return Model::many($query[0], new UserData());
    }

    public static function getByFacialData($facialData, $threshold = 0.6) {
        $users = self::getAll();
        $bestMatch = null;
        $minDistance = $threshold;
        
        foreach ($users as $user) {
            if (!empty($user->facial_data)) {
                $storedData = json_decode($user->facial_data, true);
                if (!$storedData) continue;
                
                $distance = self::compareFacialData($facialData, $storedData);
                
                if ($distance < $minDistance) {
                    $minDistance = $distance;
                    $bestMatch = $user;
                }
            }
        }
        
        return $bestMatch;
    }
    
    public static function compareFacialData(array $face1, array $face2): float {
        if (count($face1) !== count($face2) || count($face1) === 0) {
            return PHP_FLOAT_MAX;
        }

        // Normalización mejorada con tolerancia a iluminación
        $sum1 = $sum2 = $dot = 0.0;
        $min1 = min($face1);
        $min2 = min($face2);
        $range1 = max($face1) - $min1;
        $range2 = max($face2) - $min2;

        for ($i = 0; $i < count($face1); $i++) {
            // Normalización robusta contra cambios de iluminación
            $val1 = $range1 != 0 ? ($face1[$i] - $min1) / $range1 : 0;
            $val2 = $range2 != 0 ? ($face2[$i] - $min2) / $range2 : 0;
            
            $sum1 += $val1 * $val1;
            $sum2 += $val2 * $val2;
            $dot += $val1 * $val2;
        }

        $magnitude1 = sqrt($sum1);
        $magnitude2 = sqrt($sum2);

        if ($magnitude1 === 0.0 || $magnitude2 === 0.0) {
            return 1.0;
        }

        // Similaridad coseno con ajuste de iluminación
        $similarity = $dot / ($magnitude1 * $magnitude2);
        return 1 - $similarity;
    }
    
    public static function authenticateWithFace($username, $password, $facialData) {
        $user = self::getByCredentials($username, $password);
        if (!$user) return false;
        
        if (empty($user->facial_data)) {
            return $user;
        }
        
        $storedData = json_decode($user->facial_data, true);
        if (!$storedData) return false;
        
        $distance = self::compareFacialData($facialData, $storedData);
        return $distance <= 0.6 ? $user : false;
    }
    
    public static function getByCredentials($username, $password) {
        $password = sha1(md5($password));
        $username = addslashes($username);
        
        $sql = "SELECT * FROM " . self::$tablename . " 
                WHERE (email = '$username' OR username = '$username') 
                AND password = '$password' 
                AND status = 1 
                LIMIT 1";
        
        $query = Executor::doit($sql);
        return Model::one($query[0], new UserData());
    }

    public function updateFacialData($newFacialData) {
        $descriptor = $this->normalizeFacialData($newFacialData);
        if ($descriptor === null) {
            return false;
        }

        $this->facial_data = json_encode($descriptor);
        $sql = "UPDATE " . self::$tablename . " SET 
                facial_data = '" . mysqli_real_escape_string(Database::getCon(), $this->facial_data) . "'
                WHERE id = " . intval($this->id);
        
        return Executor::doit($sql);
    }

    public function hasFacialData() {
        return !empty($this->facial_data);
    }

    public function getFacialDescriptor() {
        if (empty($this->facial_data)) {
            return null;
        }
        return json_decode($this->facial_data, true);
    }
}
?>