<?php

include_once "src/controllers/DefaultController.php";

class Router {

    public static function run(string $path) {

        $controller = new DefaultController();

        if ($path === "login") {
            $controller->login();
        }
        else if ($path === "editor") {
            $controller->editor();
        }
    }
}