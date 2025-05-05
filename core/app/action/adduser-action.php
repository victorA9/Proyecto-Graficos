<?php
// core/app/action/adduser-action.php
session_start();

// Mejor práctica: Usar __DIR__ para rutas absolutas
$modelPath = __DIR__ . '/../model/UserData.php';
if (!file_exists($modelPath)) {
    die("Error crítico: No se puede encontrar UserData.php");
}
require_once $modelPath;

// Validar datos básicos
if(empty($_POST['username']) || empty($_POST['password'])) {
    $_SESSION['user_creation_error'] = "Usuario y contraseña son obligatorios";
    header("Location: index.php?view=newuser&kind=".$_POST['kind']);
    exit;
}

// Crear instancia de UserData
$user = new UserData();

// Asignar propiedades desde $_POST
$user->name = $_POST['name'] ?? '';
$user->lastname = $_POST['lastname'] ?? '';
$user->username = $_POST['username'];
$user->email = $_POST['email'] ?? '';
$user->password = sha1(md5($_POST['password']));
$user->kind = (int)($_POST['kind'] ?? 0);
$user->stock_id = isset($_POST['stock_id']) ? (int)$_POST['stock_id'] : null;
$user->facial_data = $_POST['facial_data'] ?? null;
$user->image = $_FILES['image']['name'] ?? '';
$user->comision = (float)($_POST['comision'] ?? 0);
$user->status = 1;

// Guardar en base de datos
$base_url = 'http://'.$_SERVER['HTTP_HOST'].'/BUSINESSLIT/';

if($user->add()) {
    $_SESSION['user_created'] = "Usuario creado exitosamente";
    header("Location: ".$base_url."index.php?view=users");
    exit();
} else {
    $_SESSION['user_creation_error'] = "Error al crear usuario";
    header("Location: ".$base_url."index.php?view=newuser&kind=".$_POST['kind']);
    exit();
}