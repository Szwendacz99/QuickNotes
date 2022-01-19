<?php

require_once __DIR__.'./../repository/UserRepository.php';
require_once __DIR__."/AppController.php";
require_once __DIR__."/../models/User.php";

class SecurityController extends AppController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    public function login() {

        if (!$this->isPost()) {
            $this->render('login');
            return;
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->userRepository->getUserByEmail($email);

        if ( $user === null || $user->getEmail() !== $email || ! password_verify($password, $user->getPassword())) {
            $this->render('login', ['message' => "Bad email or password!"]);
            return;
        }

        $session_id = $this->userRepository->startSession($user->getUuid());

        $url = "http://$_SERVER[HTTP_HOST]";

        setcookie("session_id", $session_id, time()+60*60*24*7);
        
        header("Location: {$url}/editor");
    }

    public function register() {

        if (!$this->isPost()) {
            $this->render('register');
            return;
        }

    }
}