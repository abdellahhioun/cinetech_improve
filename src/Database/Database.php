<?php

namespace Ahiou\CinetechImprove\Database;

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        try {
            $this->connection = new \PDO(
                'mysql:host=localhost;dbname=cinetech_improve;charset=utf8',
                'root',
                ''
            );
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch(\PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
} 