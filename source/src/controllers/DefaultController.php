<?php
require_once 'AppController.php';
require_once __DIR__ . '/../models/Note.php';

class DefaultController extends AppController {

    private UserRepository $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    public function editor(): void
    {
        if (! isset($_COOKIE['session_id']) || $this->userRepository->getUserByUUID() ){
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/login");
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
}