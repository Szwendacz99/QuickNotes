<?php

$path = trim($_SERVER["REQUEST_URI"], "/");

require_once "Routing.php";

Router::get('', 'DefaultController');
Router::get('editor', 'DefaultController');
Router::post('login', 'SecurityController');

Router::run($path);
