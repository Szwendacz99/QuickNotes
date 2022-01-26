<?php
require_once 'AppController.php';
require_once __DIR__ . './../repository/NoteRepository.php';
require_once __DIR__ . '/../models/Note.php';
require_once __DIR__.'./../models/User.php';

class NoteController extends AppController {

    private UserRepository $userRepository;
    private NoteRepository $noteRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
        $this->noteRepository = new NoteRepository();
    }


    public function note() {
        if (!$this->userRepository->authorize())
        {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/login");
        }

        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

        if ($contentType === "application/json") {
            $content = trim(file_get_contents("php://input"));

            header('Content-type: application/json');
            http_response_code(200);

            $note = $this->noteRepository->getNoteByUUID($content);
            echo json_encode(['note_id' => $note->getUuid(), 'title' => $note->getTitle(), 'text' => $note->getText()]);
        }
    }

    public function save() {
        if (!$this->userRepository->authorize())
        {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/login");
        }

        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

        if ($contentType === "application/json") {
            $content = json_decode(trim(file_get_contents("php://input")));

            header('Content-type: application/json');
            http_response_code(200);

            $this->noteRepository->saveNote($content->note_id, $content->title, $content->text);
        }
    }
    public function new() {
        if (!$this->userRepository->authorize())
        {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/login");
        }

        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

        $userUUID = $this->userRepository->getUserBySessionUUID($_COOKIE['session_id'])->getUuid();

        if ($contentType === "application/json") {
            $content = json_decode(trim(file_get_contents("php://input")));

            header('Content-type: application/json');
            http_response_code(200);

            $note_id = $this->noteRepository->newNote($userUUID, $content->title, $content->text);
            echo json_encode(['note_id' => $note_id]);
        }
    }

}