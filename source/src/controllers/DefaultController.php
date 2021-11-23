<?php
require_once 'AppController.php';
require_once __DIR__ . '/../models/Note.php';

class DefaultController extends AppController {

    public function login(): void
    {
        $this->render('login');
    }

    public function editor(): void
    {
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