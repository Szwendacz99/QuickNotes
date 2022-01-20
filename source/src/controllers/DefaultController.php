<?php
require_once 'AppController.php';
require_once __DIR__ . '/../models/Note.php';
require_once __DIR__.'./../models/User.php';

class DefaultController extends AppController {

    private UserRepository $userRepository;
    private User $user;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    public function editor(): void
    {
        if (! isset($_COOKIE['session_id']) ){
            $this->unauthorizedExit();
        }
        $this->user = $this->userRepository->getUserBySessionUUID($_COOKIE['session_id']);

        if ($this->user === null) {
            $this->unauthorizedExit();
        }
        
        // TODO read data from database etc...
        $notes = [
            new Note("title 1", "text"),
            new Note("title 2", "text"),
            new Note("title 3", "text"),
            new Note("title 4", "text"),
            new Note("title 5", "text"),
        ];
        $this->render('editor', ['notes' => $notes]);
    }


    private function unauthorizedExit(){
        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/login");
    }
}