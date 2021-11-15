<?php
require_once 'AppController.php';

class DefaultController extends AppController {

    public function login()
    {
        // TODO read data from database etc...
        $this->render('login');
    }

    public function editor()
    {
        $animals = ["bird", "dog"];
        $this->render('editor', ["animals" => $animals] );
    }
}