<?php
require_once 'AppController.php';
require_once __DIR__ . './../repository/NoteRepository.php';
require_once __DIR__ . '/../models/Note.php';
require_once __DIR__.'./../models/User.php';

class DefaultController extends AppController {

    private UserRepository $userRepository;
    private NoteRepository $noteRepository;
    private ?User $user;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
        $this->noteRepository = new NoteRepository();
    }

    public function finduser() {
        if (!$this->userRepository->authorize())
        {
            return;
        }

        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

        if ($contentType === "application/json") {
            $content = json_decode(trim(file_get_contents("php://input")));

            header('Content-type: application/json');
            http_response_code(200);

            $users = $this->userRepository->getUsersByNickname($content->username);
            $usersArray = [];

            if ($users == null) {
                echo json_encode($usersArray);
                return;
            }

            foreach ($users as $user) {
                $usersArray[] = $user->getUsername();
            }
            echo json_encode($usersArray);
        }
    }

    public function editor(): void
    {
        if (!$this->userRepository->authorize())
        {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/login");
        }

        $this->user = $this->userRepository->getUserBySessionUUID($_COOKIE['session_id']);
        
        $notes = $this->noteRepository->getUserNotes($this->user->getUuid());
        $shared_notes = $this->noteRepository->getNotesSharedByUser($this->user->getUuid());
        $shared_notes_from_others = $this->noteRepository->getNotesSharedForUser($this->user->getUuid());
        $user_tags = $this->noteRepository->getUserTags($this->user->getUuid());

        $this->render('editor',
            [   'notes' => $notes,
                'user' => $this->user,
                'shared_notes' => $shared_notes,
                'shared_notes_from_others' => $shared_notes_from_others,
                'user_tags' => $user_tags]);
    }

    public function shares() {
        if (!$this->userRepository->authorize())
        {
            return;
        }
        $this->user = $this->userRepository->getUserBySessionUUID($_COOKIE['session_id']);
        $shared_notes = $this->noteRepository->getNotesSharedByUser($this->user->getUuid());
        $shared_notes_from_others = $this->noteRepository->getNotesSharedForUser($this->user->getUuid());

        $this->render('shares',
            [
                'shared_notes' => $shared_notes,
                'shared_notes_from_others' => $shared_notes_from_others,
                ]);
    }

}