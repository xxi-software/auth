<?php
// Incluye el archivo de autoload de Composer para cargar las clases automáticamente
require_once __DIR__ . '/../vendor/autoload.php';

// Importa las clases necesarias
use EPayco\Api\Auth\Auth;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Inicia la sesión
session_start();

// Configurar el logger
$logger = new Logger('EPayco\Api');
$logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/app.log', Logger::DEBUG));

// Obtener la instancia de Auth
$auth = Auth::getInstance($logger);

// Verifica si el método de solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene el email y la contraseña del formulario
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Verifica si los campos están vacíos
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = 'Por favor, complete todos los campos';
        header('Location: login.php');
        exit;
    }

    // Intenta iniciar sesión
    if ($auth->login($email, $password)) {
        $_SESSION['success'] = 'Inicio de sesión exitoso';
        header('Location: dashboard.php');
        exit;
    } else {
        $_SESSION['error'] = 'Email o contraseña incorrectos';
        header('Location: login.php');
        exit;
    }
} else {
    // Redirige a la página de inicio de sesión si el método de solicitud no es POST
    header('Location: login.php');
    exit;
} 