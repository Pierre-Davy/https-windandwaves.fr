<?php

namespace App\Models\dao;

// On "importe" PDO
use PDO;
use PDOException;

class Db extends PDO
{
    // Instance unique de la classe
    protected $instance;

    // Informations de connexion
    private const DBHOST = '######';
    private const DBUSER = '######';
    private const DBPASS = '######';
    private const DBNAME = '######';

    public function __construct()
    {
        // DSN de connexion
        $_dsn = 'mysql:dbname=' . self::DBNAME . ';host=' . self::DBHOST;

        // On appelle le constructeur de la classe PDO
        try {
            $this->instance = new PDO($_dsn, self::DBUSER, self::DBPASS);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}
