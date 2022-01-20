<?php

$path = trim($_SERVER["REQUEST_URI"], "/");

require_once "core/Routing.php";
require_once "core/Database.php";

Router::get('', 'DefaultController');
Router::get('editor', 'DefaultController');
Router::post('login', 'SecurityController');
Router::post('register', 'SecurityController');

Router::run($path);
