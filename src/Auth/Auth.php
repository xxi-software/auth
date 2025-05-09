<?php

namespace EPayco\Api\Auth;

use EPayco\Api\Models\User;
use Monolog\Logger;
use EPayco\Api\Conn\DB;

class Auth
{
    private $logger;
    private static $instance = null;
    private $currentUser = null;
    private $db;

    private function __construct(Logger $logger)
    {
        $this->logger = $logger;
        session_start();
        $this->db = DB::getInstance();
    }

    public static function getInstance(Logger $logger)
    {
        if (self::$instance === null) {
            self::$instance = new self($logger);
        }   
        return self::$instance;
    }

    public function login($email, $password)
    {
        try {
            // 1. Buscamos el usuario en la base de datos por su email
            $userData = $this->db->getUserByEmail($email);
            
            // 2. Registramos la información obtenida para debugging
            $this->logger->info("Datos obtenidos de la BD", [
                'userData' => $userData,
                'email_buscado' => $email
            ]);

            // 3. Verificamos si encontramos el usuario
            if (!$userData) {
                $this->logger->warning('Usuario no encontrado en la BD', ['email' => $email]);
                return false;
            }

            // 4. Creamos instancia de User con los datos de la BD
            // Nota: isHashed = true porque la contraseña en BD ya está hasheada
            $user = new User(
                $userData['email'],
                $userData['password'],
                $userData['name'],
                true
            );

            // 5. Verificamos si la contraseña proporcionada coincide
            $passwordMatch = $user->verifyPassword($password);
            
            // 6. Registramos el resultado de la verificación
            $this->logger->info('Resultado verificación contraseña', [
                'email' => $email,
                'coincide' => $passwordMatch
            ]);

            if ($passwordMatch) {
                // 7. Si todo es correcto, establecemos la sesión
                $_SESSION['user'] = $user;
                $this->currentUser = $user;
                $this->logger->info('Inicio de sesión exitoso', ['email' => $email]);
                return true;
            }

            // 8. Si la contraseña no coincide
            $this->logger->warning('Contraseña incorrecta', ['email' => $email]);
            return false;

        } catch (\Exception $e) {
            // 9. Capturamos cualquier error inesperado
            $this->logger->error('Error en el proceso de login', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function register($email, $password, $name)
    {
        // Creamos el usuario con la contraseña en texto plano (isHashed=false por defecto)
        $user = new User($email, $password, $name);
        $this->db->saveUser($user);
        $this->logger->info('Nuevo usuario registrado', ['email' => $email]);
        return $user;
    }

    public function logout()
    {
        if ($this->isLoggedIn()) {
            $this->logger->info('Usuario cerró sesión', ['email' => $this->currentUser->getEmail()]);
        }
        session_destroy();
        $this->currentUser = null;
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['user']);
    }

    public function getCurrentUser()
    {
        return $this->currentUser;
    }
} 