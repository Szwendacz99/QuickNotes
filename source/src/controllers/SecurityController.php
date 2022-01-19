<?php

require_once __DIR__."/AppController.php";
require_once __DIR__."/../models/User.php";

class SecurityController extends AppController
{
    public function login() {
        $exampleUser = new User("user", "email", "passwd");

        if (!$this->isPost()) {
            $this->render('login');
            return;
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        if ($exampleUser->getEmail() !== $email || $exampleUser->getPassword() !== $password) {
            $this->render('login', ['message' => "Bad email or password!"]);
            return;
        }
        $url = "http://$_SERVER[HTTP_HOST]";

        setcookie("session_id", "aaa", time()+60*60*24);
        
        header("Location: {$url}/editor");
    }

    public function register() {

        if (!$this->isPost()) {
            $this->render('register');
            return;
        }

    }
}