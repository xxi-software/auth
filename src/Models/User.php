<?php

namespace EPayco\Api\Models;

class User
{
    private $id;
    private $email;
    private $password;
    private $name;
    private $isHashed;

    public function __construct($email, $password, $name = '', $isHashed = false)
    {
        $this->email = $email;
        $this->password = $isHashed ? $password : password_hash($password, PASSWORD_DEFAULT);
        $this->name = $name;
        $this->isHashed = $isHashed;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }

} 