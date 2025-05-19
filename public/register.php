<?php

require_once __DIR__ . '/../vendor/autoload.php';

use EPayco\Api\Auth\Auth;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Configurar el logger
$logger = new Logger('EPayco\Api');
$logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/app.log', Logger::DEBUG));

// Obtener la instancia de Auth
$auth = Auth::getInstance($logger);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $name = $_POST['name'];

    // Registrar el usuario
    $user = $auth->register($email, $password, $name);
    
    // Iniciar sesión automáticamente
    if ($user) {
        $auth->login($email, $password);
        header('Location: dashboard.php');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;

            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        

        .links {
            text-align: center;
            margin-top: 15px;
        }
        .links a {
            text-decoration: none;
            color: #28a745;
        }   
        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registro de Usuario</h2>
        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>   
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>  
            <div class="form-group">
                <label for="name">Nombre:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <button type="submit">Registrar</button>
        </form> 
        
        <div class="links">
            <a href="login.php">¿Ya tienes una cuenta? Inicia sesión</a>
        </div>
    </div>
</body>
</html> 

