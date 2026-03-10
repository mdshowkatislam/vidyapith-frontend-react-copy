<?php

namespace App\Container;

class AuthInstance
{
    private static $instance = null;
    private $data = [];
    private $user_key = 'user_auth_data';

    private function __construct()
    {
        // Private constructor to prevent direct instantiation
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function setUser($value)
    {
        $this->data[$this->user_key] = $value;
    }

    public function getUser()
    {
        return $this->data[$this->user_key] ?? null;
    }
}