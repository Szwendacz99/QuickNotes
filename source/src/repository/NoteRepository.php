<?php

require_once 'Repository.php';
require_once __DIR__.'./../models/User.php';
require_once __DIR__.'./../models/Note.php';
require_once __DIR__.'./../models/Tag.php';
require_once __DIR__.'./../models/NoteShare.php';
require_once __DIR__."./../../core/Utils.php";

class NoteRepository extends Repository
{
   public function getUserNotes($userUUID): Array {
       $notes = [];
       $query = $this->database->connect()->prepare('SELECT * FROM quicknotes_schema.note n INNER JOIN 
                quicknotes_schema.session s on n.user_id = s.user_id WHERE s.user_id = :uuid');
       $query->bindParam(':uuid', $userUUID, PDO::PARAM_STR);
       $query->execute();

       $result = $query->fetchAll(PDO::FETCH_ASSOC);

       if ($result == false) {
           return $notes;
       }

       foreach ($result as $note) {
           $notes[] = new Note($note['note_id'], $note['title'], $note['text']);
       }

       return $notes;
   }

    public function getNotesSharedByUser($userUUID): Array {
        $notes = [];
        $query = $this->database->connect()->prepare('SELECT * FROM 
              (SELECT ns.user_id, n.title, n.note_id FROM quicknotes_schema.note n INNER JOIN
                                              quicknotes_schema.session s on n.user_id = s.user_id INNER JOIN
                                              quicknotes_schema.note_share ns on n.note_id = ns.note_id
               WHERE s.user_id =  :uuid) shares INNER JOIN
              quicknotes_schema.user u on shares.user_id = u.user_id');
        $query->bindParam(':uuid', $userUUID, PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($result == false) {
            return $notes;
        }
        foreach ($result as $note) {
            $notes[$note['username']][] = new NoteShare($note['user_id'], $note['title'], $note['note_id']);
        }

        return $notes;
    }

    public function getNoteInfo($noteUUID) {
        $query = $this->database->connect()->prepare('SELECT * FROM quicknotes_schema.note
                                                                WHERE note_id = :note_id');
        $query->bindParam(':note_id', $noteUUID, PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result == false) {
            return null;
        }
        $note = new Note($result['note_id'], $result['title'], $result['text']);
        $note->setTimeCreated(new DateTime( $result['creation_datetime']));
        $note->setTimeLastEdit(new DateTime($result['last_edit']));

        return $note;
    }

    public function getNoteTags($noteUUID): array {
        $tags = [];

        $query = $this->database->connect()->prepare('SELECT * FROM quicknotes_schema.note n INNER JOIN
                                                quicknotes_schema.note_tag nt on n.note_id = nt.note_id INNER JOIN
                                                quicknotes_schema.tag t on nt.tag_id = t.tag_id
                                                                WHERE n.note_id = :note_id');
        $query->bindParam(':note_id', $noteUUID, PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($result == false) {
            return $tags;
        }

        foreach ($result as $tag) {
            $tags[] = new Tag($tag['tag_id'], $tag['tag_name']);
        }
        return $tags;
    }

    public function addTagToNote($tagUUID, $noteUUID) {
        $query = $this->database->connect()->prepare('INSERT INTO quicknotes_schema.note_tag 
                                                    (note_id, tag_id) VALUES (:note_id, :tag_id)');
        $query->bindParam(':note_id', $noteUUID, PDO::PARAM_STR);
        $query->bindParam(':tag_id', $tagUUID, PDO::PARAM_STR);
        $query->execute();
    }

    public function removeTagFromNote($tagUUID, $noteUUID) {
        $query = $this->database->connect()->prepare('DELETE FROM quicknotes_schema.note_tag 
                                                    WHERE note_id = :note_id AND tag_id = :tag_id');
        $query->bindParam(':note_id', $noteUUID, PDO::PARAM_STR);
        $query->bindParam(':tag_id', $tagUUID, PDO::PARAM_STR);
        $query->execute();
    }

    public function getNotesSharedForUser($userUUID): Array {
        $notes = [];
        $query = $this->database->connect()->prepare('SELECT * FROM quicknotes_schema.note n INNER JOIN
              quicknotes_schema.note_share ns on n.note_id = ns.note_id INNER JOIN
              quicknotes_schema.user u on n.user_id = u.user_id WHERE ns.user_id = :uuid');
        $query->bindParam(':uuid', $userUUID, PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($result == false) {
            return $notes;
        }
        foreach ($result as $note) {
            $notes[$note['username']][] = new NoteShare($note['user_id'], $note['title'], $note['note_id']);
        }

        return $notes;
    }

    public function getNoteByUUID($uuid): ?Note {
        $query = $this->database->connect()->prepare('SELECT * FROM quicknotes_schema.note
                                                                WHERE note_id = :note_id');
        $query->bindParam(':note_id', $uuid, PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result == false) {
            return null;
        }
        return new Note($result['note_id'], $result['title'], $result['text']);
    }

    public function saveNote($uuid, $title, $text): void {
        $query = $this->database->connect()->prepare('UPDATE quicknotes_schema.note
                                                                SET text = :text, title = :title 
                                                                WHERE note_id = :note_id');
        $query->bindParam(':title', $title, PDO::PARAM_STR);
        $query->bindParam(':text', $text, PDO::PARAM_STR);
        $query->bindParam(':note_id', $uuid, PDO::PARAM_STR);
        $query->execute();
    }

    public function newNote($userUUID, $title, $text): string {
        $note_id = Utils::uuid();

        $query = $this->database->connect()->prepare('INSERT INTO quicknotes_schema.note
                                                            (note_id, title, text, user_id) VALUES 
                                                            (:note_id, :title, :text, :user_id)');
        $query->bindParam(':user_id', $userUUID, PDO::PARAM_STR);
        $query->bindParam(':title', $title, PDO::PARAM_STR);
        $query->bindParam(':text', $text, PDO::PARAM_STR);
        $query->bindParam(':note_id', $note_id, PDO::PARAM_STR);
        $query->execute();

        return $note_id;
    }

    public function deleteNote($noteUUID) {
        $query = $this->database->connect()->prepare('DELETE FROM quicknotes_schema.note
                                                            WHERE note_id = :note_id');
        $query->bindParam(':note_id', $noteUUID, PDO::PARAM_STR);
        $query->execute();
    }

    public function getUserTags($userUUID): Array {
        $tags = [];
        $query = $this->database->connect()->prepare('SELECT * FROM quicknotes_schema.tag n WHERE user_id = :uuid');
        $query->bindParam(':uuid', $userUUID, PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($result == false) {
            return $tags;
        }
        foreach ($result as $tag) {
            $tags[] = new Tag($tag['tag_id'], $tag['tag_name']);
        }

        return $tags;
    }

    public function createTag($userUUID, $tagName): ?string {
        $tag_id = Utils::uuid();

        $currentTags = $this->getUserTags($userUUID);
        foreach ($currentTags as $tag) {
            if ($tag->getName() === $tagName) {
                return null;
            }
        }

        $query = $this->database->connect()->prepare('INSERT INTO quicknotes_schema.tag
                                                            (tag_id, tag_name, user_id) VALUES 
                                                            (:tag_id, :tag_name, :user_id)');
        $query->bindParam(':tag_id', $tag_id, PDO::PARAM_STR);
        $query->bindParam(':tag_name', $tagName, PDO::PARAM_STR);
        $query->bindParam(':user_id', $userUUID, PDO::PARAM_STR);
        $query->execute();

        return $tag_id;
    }

}