<?php

$path = trim($_SERVER["REQUEST_URI"], "/");

require_once "routing.php";

Router::run($path);
