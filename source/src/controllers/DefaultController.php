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

    protected function authorize(): void {
        if (! isset($_COOKIE['session_id']) ){
            $this->unauthorizedExit();
        }
        $this->user = $this->userRepository->getUserBySessionUUID($_COOKIE['session_id']);

        if ($this->user === null) {
            $this->unauthorizedExit();
        }
    }

    private function unauthorizedExit(){
        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/login");
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
}