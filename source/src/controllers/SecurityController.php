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

    public function register(): void {
        if (!$this->isPost()) {
            $this->render('register');
            return;
        }

        if (! (isset($_POST['email']) &&
            isset($_POST['password']) &&
            isset($_POST['username']) &&
            isset($_POST['password_confirm']))) {
            $this->render('register', ['message' => "Missing needed data for new account!"]);
            return;
        }
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];
        $username = $_POST['username'];

        if(! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->render('register', ['message' => "Bad email!"]);
            return;
        }

        if($password !== $password_confirm || strlen($password) < 8) {
            $this->render('register', ['message' => "Password shorter than 8 or not matching!"]);
            return;
        }

        if (strlen($username) < 3) {
            $this->render('register', ['message' => "Username too short (<3)!"]);
            return;
        }

        $uuid = $this->userRepository->addUser($username, $password, $email);

        if ($uuid === null) {
            $this->render('register', ['message' => "Email already taken!"]);
            return;
        }

        $sessionUUID = $this->userRepository->startSession($uuid);

        $url = "http://$_SERVER[HTTP_HOST]";

        setcookie("session_id", $sessionUUID, time()+60*60*24*7);

        header("Location: {$url}/editor");

    }

    public function nickname() {
        if (!$this->userRepository->authorize())
        {
            return;
        }

        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

        if ($contentType === "application/json") {
            $content = json_decode(trim(file_get_contents("php://input")));

            header('Content-type: application/json');
            http_response_code(200);

            if (strlen($content->nickname) < 3) {
                echo json_encode(['result' => "Nickname too short!"]);
                return;
            }

            $user = $this->userRepository->getUserBySessionUUID($_COOKIE['session_id']);

            echo json_encode(['result' => $this->userRepository->changeNickname($user->getUuid(),
                $content->nickname)]);
        }
    }

    public function login(): void {

        if (!$this->isPost()) {
            $this->render('login');
            return;
        }

        if (! (isset($_POST['email']) &&
            isset($_POST['password']) )){
            $this->render('login', ['message' => "Bad email or password!"]);
            return;
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->userRepository->getUserByEmail($email);

        if ( $user === null || $user->getEmail() !== $email || ! password_verify($password, $user->getPassword())) {
            $this->render('login', ['message' => "Bad email or password!"]);
            return;
        }

        if (!isset($_COOKIE['session_id']) || ! $this->userRepository->validateSession($_COOKIE['session_id'])) {
            $session_id = $this->userRepository->startSession($user->getUuid());
        }
        else {
            $session_id = $this->userRepository->refreshSession($user->getUuid());
        }

        $url = "http://$_SERVER[HTTP_HOST]";

        setcookie("session_id", $session_id, time()+60*60*24*7);
        
        header("Location: {$url}/editor");
    }

    public function logout(): void {
        if (isset($_COOKIE['session_id'])) {
            setcookie("session_id", "", time()-60*60*24*7);
        }
        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/login");
    }

}