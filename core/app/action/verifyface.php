<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__.'/../model/Database.php';
require_once __DIR__.'/../model/Executor.php';
require_once __DIR__.'/../model/Model.php';
require_once __DIR__.'/../model/UserData.php';

session_set_cookie_params([
    'lifetime' => 86400,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax'
]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

while (ob_get_level()) ob_end_clean();

class FaceVerification {
    private function calculateFaceDistance(array $input, array $stored): float {
        if (count($input) !== 128 || count($stored) !== 128) {
            throw new Exception("Los descriptores faciales deben tener 128 valores", 400);
        }

        $sum = 0;
        for ($i = 0; $i < 128; $i++) {
            $diff = $input[$i] - $stored[$i];
            $sum += $diff * $diff;
        }
        return sqrt($sum);
    }

    private function registerAttempt(bool $success): void {
        if (!isset($_SESSION['face_attempts'])) {
            $_SESSION['face_attempts'] = [
                'count' => 0,
                'last_attempt' => time()
            ];
        }
        
        if (!$success) {
            $_SESSION['face_attempts']['count']++;
            $_SESSION['face_attempts']['last_attempt'] = time();
        } else {
            $_SESSION['face_attempts']['count'] = 0;
        }
    }

    private function checkAttempts(): void {
        $maxAttempts = 5;
        $cooldownTime = 600; // 10 minutos en segundos
        
        if (!isset($_SESSION['face_attempts'])) {
            return;
        }
        
        // Resetear intentos si el último fue hace más de 10 minutos
        if ((time() - $_SESSION['face_attempts']['last_attempt']) > $cooldownTime) {
            $_SESSION['face_attempts']['count'] = 0;
            return;
        }
        
        // Lanzar error si se excedieron los intentos
        if ($_SESSION['face_attempts']['count'] >= $maxAttempts) {
            throw new Exception("Demasiados intentos fallidos. Por favor espere 10 minutos.", 429);
        }
    }

    public function processVerification(): void {
        try {
            // Verificar autenticación
            if (!isset($_SESSION['user_id']) && !isset($_SESSION['client_id'])) {
                throw new Exception("Acceso no autorizado. Inicie sesión primero.", 401);
            }

            $this->checkAttempts();

            $userId = $_SESSION['user_id'] ?? $_SESSION['client_id'];
            $user = UserData::getById($userId);
            
            if (!$user) {
                throw new Exception("Usuario no encontrado", 404);
            }

            // Obtener datos de entrada
            $input = json_decode(file_get_contents('php://input'), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Formato JSON inválido", 400);
            }

            if (empty($input['facial_data']) || !is_array($input['facial_data'])) {
                throw new Exception("Se requieren datos faciales válidos", 400);
            }

            // Obtener descriptor facial almacenado
            $storedData = $user->getFacialDescriptor();
            if (!$storedData || count($storedData) !== 128) {
                throw new Exception("El usuario no tiene registro facial válido", 400);
            }

            // Calcular distancia entre descriptores
            $distance = $this->calculateFaceDistance($input['facial_data'], $storedData);
            $threshold = 0.55; // Umbral de coincidencia
            
            $isMatch = $distance <= $threshold;
            $this->registerAttempt($isMatch);

            // Respuesta exitosa
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'match' => $isMatch,
                'distance' => round($distance, 4),
                'threshold' => $threshold,
                'attempts_remaining' => max(0, 5 - ($_SESSION['face_attempts']['count'] ?? 0)),
                'message' => $isMatch ? "Verificación exitosa" : "Rostro no coincide"
            ]);
            exit;

        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 500);
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'attempts_remaining' => max(0, 5 - ($_SESSION['face_attempts']['count'] ?? 0))
            ]);
            exit;
        }
    }
}

$faceVerification = new FaceVerification();
$faceVerification->processVerification();
?>