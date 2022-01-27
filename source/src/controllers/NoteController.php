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
            return;
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

    public function noteinfo() {
        if (!$this->userRepository->authorize())
        {
            return;
        }

        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

        if ($contentType === "application/json") {
            $content = json_decode(trim(file_get_contents("php://input")));

            header('Content-type: application/json');
            http_response_code(200);

            $note = $this->noteRepository->getNoteInfo($content->note_id);
            $noteTagsObj = $this->noteRepository->getNoteTags($note->getUuid());
            $noteTags = [];
            foreach ($noteTagsObj as $tag) {
                $noteTags[] = ['tag_name' => $tag->getName(), 'tag_id' => $tag->getUuid()];
            }

            $allTags = $this->noteRepository->getUserTags($this->userRepository->getUserBySessionUUID($_COOKIE['session_id'])->getUuid());

            $otherTags = [];
            foreach ($allTags as $tag) {
                $isNoteTag = false;
                foreach ($noteTagsObj as $noteTag) {
                    if ($tag->getUuid() === $noteTag->getUuid()){
                        $isNoteTag = true;
                        break;
                    }
                }
                if (!$isNoteTag) {
                    $otherTags[] = ['tag_name' => $tag->getName(), 'tag_id' => $tag->getUuid()];
                }
            }

            echo json_encode(['title' => $note->getTitle(),
                'creation_datetime' => $note->getTimeCreated()->format(DATE_FORMAT),
                'last_edit' => $note->getTimeLastEdit()->format(DATE_FORMAT),
                'tags' => $noteTags,
                'other_tags' => $otherTags]);
        }
    }

    public function tagnote() {
        if (!$this->userRepository->authorize())
        {
            return;
        }

        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

        if ($contentType === "application/json") {
            $content = json_decode(trim(file_get_contents("php://input")));

            header('Content-type: application/json');
            http_response_code(200);

            $this->noteRepository->addTagToNote($content->tag_id, $content->note_id);
        }
    }

    public function newtag() {
        if (!$this->userRepository->authorize())
        {
            return;
        }

        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

        if ($contentType === "application/json") {
            $content = json_decode(trim(file_get_contents("php://input")));

            header('Content-type: application/json');
            http_response_code(200);

            $userUUID = $this->userRepository->getUserBySessionUUID($_COOKIE['session_id'])->getUuid();

            $tag_id = $this->noteRepository->createTag($userUUID, $content->tag_name);
            if ($tag_id === null) {
                echo json_encode(['result' => 'notok']);
                return;
            }
            echo json_encode(['result' => 'ok', 'tag_id' => $tag_id]);
        }
    }

    public function untagnote() {
        if (!$this->userRepository->authorize())
        {
            return;
        }

        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

        if ($contentType === "application/json") {
            $content = json_decode(trim(file_get_contents("php://input")));

            header('Content-type: application/json');
            http_response_code(200);

            $this->noteRepository->removeTagFromNote($content->tag_id, $content->note_id);
        }
    }

    public function save() {
        if (!$this->userRepository->authorize())
        {
            return;
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
            return;
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

    public function delete() {
        if (!$this->userRepository->authorize())
        {
            return;
        }

        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

        if ($contentType === "application/json") {
            $content = json_decode(trim(file_get_contents("php://input")));

            header('Content-type: application/json');
            http_response_code(200);

            $this->noteRepository->deleteNote($content->note_id);
        }
    }

}