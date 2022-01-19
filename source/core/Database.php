<?php

require_once "config.php";

class Database {
    private string $username;
    private string $password;
    private string $hostname;
    private string $database;

    public function __construct()
    {
        $this->username = USERNAME;
        $this->password = PASSWORD;
        $this->hostname = HOSTNAME;
        $this->database = DATABASE;
    }

    public function connect()
    {
        try {
            $conn = new PDO(
                "pgsql:host=$this->hostname;port=5432;dbname=$this->database",
                $this->username,
                $this->password
            );

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        }
        catch(PDOException $e) {
            die("Cannot connect to db: " . $e->getMessage());
        }
    }
}