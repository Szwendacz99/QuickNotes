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

        header("Location: {$url}/editor");
    }
}