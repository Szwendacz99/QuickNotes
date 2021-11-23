<?php

$path = trim($_SERVER["REQUEST_URI"], "/");

require_once "Routing.php";

Router::run($path);
