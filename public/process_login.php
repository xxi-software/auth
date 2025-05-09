<?php
require_once __DIR__ . '/../vendor/autoload.php';

use EPayco\Api\Auth\Auth;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

session_start();

// Configurar el logger
$logger = new Logger('EPayco\Api');
$logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/app.log', Logger::DEBUG));

// Obtener la instancia de Auth
$auth = Auth::getInstance($logger);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = 'Por favor, complete todos los campos';
        header('Location: login.php');
        exit;
    }

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
    header('Location: login.php');
    exit;
} 