<?php

require_once __DIR__.'/../../core/Database.php';

class Repository {
    protected Database $database;

    public function __construct()
    {
        $this->database = new Database();
    }
}
