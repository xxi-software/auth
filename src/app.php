<?php

namespace EPayco\Api;

require_once __DIR__ . '/../vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use EPayco\Api\Auth\Auth;

class App
{
    private $logger;
    private $auth;

    public function __construct()
    {
        $this->logger = new Logger('EPayco\Api');
        // Agregamos un handler que escribirá los logs en un archivo
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/app.log', Logger::DEBUG));
        $this->auth = Auth::getInstance($this->logger);
    }

    public function run()
    {
        // Ejemplo de registro
        $user = $this->auth->register('cami@usuario.com', 'cami', 'Camilo Usuga');
        $this->logger->info('Usuario registrado', ['email' => $user->getEmail()]);

        // Ejemplo de inicio de sesión
        if ($this->auth->login('cami@usuario.com', 'cami')) {
            $this->logger->info('Inicio de sesión exitoso');
        } else {
            $this->logger->warning('Inicio de sesión fallido');
        }

        // Ejemplo de cierre de sesión
        $this->auth->logout();
        $this->logger->info('Sesión cerrada');
    }
}

$app = new App();
$app->run();


