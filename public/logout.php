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

// Cerrar sesión
$auth->logout();

// Redirigir al login
header('Location: login.php');
exit; 