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

// Verificar si el usuario está logueado
if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user = $auth->getCurrentUser();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - EPayco</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .welcome {
            font-size: 24px;
            color: #333;
        }
        .logout {
            background-color: #dc3545;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .logout:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="welcome">
                Bienvenido, <?php echo htmlspecialchars($user->getName()); ?>
            </div>
            <a href="logout.php" class="logout">Cerrar Sesión</a>
        </div>
        <div class="content">
            <h2>Dashboard</h2>
            <p>Email: <?php echo htmlspecialchars($user->getEmail()); ?></p>
            <!-- Aquí puedes agregar más contenido del dashboard -->
        </div>
    </div>
</body>
</html> 