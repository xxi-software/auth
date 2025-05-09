<?php

namespace EPayco\Api\Conn;

class DB
{
    private static $instance = null;
    private $conn;

    private function __construct()
    {
        $this->conn = mysqli_connect(
            'localhost',
            'root',
            '',
            'crud_php'
        );

        if (!$this->conn) {
            die("Error de conexión: " . mysqli_connect_error());
        }
    }

    /**
     * Obtiene la única instancia de la conexión a la base de datos.
     * Este método implementa el patrón Singleton para asegurar que solo exista
     * una conexión a la base de datos durante toda la ejecución de la aplicación.
     * 
     * @return DB Retorna la instancia única de la conexión
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getUserByEmail($email)
    {
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        // Retorna un array asociativo con los datos del usuario
        // Si no encuentra el usuario retorna null
        if (mysqli_num_rows($result) === 0) {
            return null;
        }
        return mysqli_fetch_assoc($result);
    }

    public function saveUser($user)
    {
        $query = "INSERT INTO users (email, password, name) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $query);
        
        // Guardamos los valores en variables antes de pasarlos
        $email = $user->getEmail();
        $password = $user->getPassword();
        $name = $user->getName();
        
        mysqli_stmt_bind_param($stmt, "sss", $email, $password, $name);
        mysqli_stmt_execute($stmt);
    }
}