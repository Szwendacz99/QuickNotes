<?php

require_once 'Repository.php';
require_once __DIR__.'./../models/User.php';
require_once __DIR__.'./../models/Note.php';
require_once __DIR__.'./../models/Tag.php';
require_once __DIR__.'./../models/NoteShare.php';
require_once __DIR__."./../../core/Utils.php";

class NoteRepository extends Repository
{
   public function getUserNotes(string $userUUID): Array {
       $notes = [];
       $query = $this->database->connect()->prepare('SELECT * FROM quicknotes_schema.note n INNER JOIN 
                quicknotes_schema.session s on n.user_id = s.user_id WHERE s.user_id = :uuid');
       $query->bindParam(':uuid', $userUUID, PDO::PARAM_STR);
       $query->execute();

       $result = $query->fetchAll(PDO::FETCH_ASSOC);

       if ($result === false) {
           return $notes;
       }

       foreach ($result as $note) {
           $notes[] = new Note($note['note_id'], $note['title'], $note['text']);
       }

       return $notes;
   }

    public function getNotesSharedByUser(string $userUUID): Array {
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

        if ($result === false) {
            return $notes;
        }
        foreach ($result as $note) {
            $notes[$note['username']][] = new NoteShare($note['user_id'], $note['title'], $note['note_id']);
        }

        return $notes;
    }

    public function getNoteInfo(string $noteUUID) {
        $query = $this->database->connect()->prepare('SELECT * FROM quicknotes_schema.note
                                                                WHERE note_id = :note_id');
        $query->bindParam(':note_id', $noteUUID, PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            return null;
        }
        $note = new Note($result['note_id'], $result['title'], $result['text']);
        $note->setTimeCreated(new DateTime( $result['creation_datetime']));
        $note->setTimeLastEdit(new DateTime($result['last_edit']));

        return $note;
    }

    public function getNoteTags(string $noteUUID): array {
        $tags = [];

        $query = $this->database->connect()->prepare('SELECT * FROM quicknotes_schema.note n INNER JOIN
                                                quicknotes_schema.note_tag nt on n.note_id = nt.note_id INNER JOIN
                                                quicknotes_schema.tag t on nt.tag_id = t.tag_id
                                                                WHERE n.note_id = :note_id');
        $query->bindParam(':note_id', $noteUUID, PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($result === false) {
            return $tags;
        }

        foreach ($result as $tag) {
            $tags[] = new Tag($tag['tag_id'], $tag['tag_name']);
        }
        return $tags;
    }

    public function addTagToNote(string $tagUUID, string $noteUUID) {
        $query = $this->database->connect()->prepare('INSERT INTO quicknotes_schema.note_tag 
                                                    (note_id, tag_id) VALUES (:note_id, :tag_id)');
        $query->bindParam(':note_id', $noteUUID, PDO::PARAM_STR);
        $query->bindParam(':tag_id', $tagUUID, PDO::PARAM_STR);
        $query->execute();
    }

    public function removeTagFromNote(string $tagUUID, string $noteUUID) {
        $query = $this->database->connect()->prepare('DELETE FROM quicknotes_schema.note_tag 
                                                    WHERE note_id = :note_id AND tag_id = :tag_id');
        $query->bindParam(':note_id', $noteUUID, PDO::PARAM_STR);
        $query->bindParam(':tag_id', $tagUUID, PDO::PARAM_STR);
        $query->execute();
    }

    public function getNotesSharedForUser(string $userUUID): Array {
        $notes = [];
        $query = $this->database->connect()->prepare('SELECT * FROM quicknotes_schema.note n INNER JOIN
              quicknotes_schema.note_share ns on n.note_id = ns.note_id INNER JOIN
              quicknotes_schema.user u on n.user_id = u.user_id WHERE ns.user_id = :uuid');
        $query->bindParam(':uuid', $userUUID, PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($result === false) {
            return $notes;
        }
        foreach ($result as $note) {
            $notes[$note['username']][] = new NoteShare($note['user_id'], $note['title'], $note['note_id']);
        }

        return $notes;
    }

    public function getNoteByUUID(string $uuid): ?Note {
        $query = $this->database->connect()->prepare('SELECT * FROM quicknotes_schema.note
                                                                WHERE note_id = :note_id');
        $query->bindParam(':note_id', $uuid, PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            return null;
        }
        return new Note($result['note_id'], $result['title'], $result['text']);
    }

    public function saveNote(string $uuid, string $title, string $text): void {
        $query = $this->database->connect()->prepare('UPDATE quicknotes_schema.note
                                                                SET text = :text, title = :title, last_edit = NOW()
                                                                WHERE note_id = :note_id');
        $query->bindParam(':title', $title, PDO::PARAM_STR);
        $query->bindParam(':text', $text, PDO::PARAM_STR);
        $query->bindParam(':note_id', $uuid, PDO::PARAM_STR);
        $query->execute();
    }

    public function newNote(string $userUUID, string $title, string $text): string {
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

    public function deleteNote(string $noteUUID) {
        $query = $this->database->connect()->prepare('DELETE FROM quicknotes_schema.note
                                                            WHERE note_id = :note_id');
        $query->bindParam(':note_id', $noteUUID, PDO::PARAM_STR);
        $query->execute();
    }

    public function getNoteSharesUsernames(string $note_id, string $userUUID): ?array {

        $query = $this->database->connect()->prepare('SELECT username FROM quicknotes_schema.note n INNER JOIN 
                                        quicknotes_schema.note_share ns on n.note_id = ns.note_id INNER JOIN 
                                            quicknotes_schema.user u on u.user_id = ns.user_id WHERE 
                                            ns.note_id = :note_id AND n.user_id = :user_id');
        $query->bindParam(':note_id', $note_id, PDO::PARAM_STR);
        $query->bindParam(':user_id', $userUUID, PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($result === false) {
            return [];
        }

        return $result;
    }

    public function shareNote(string $note_id, string $userUUID): bool {
        $query = $this->database->connect()->prepare('INSERT INTO quicknotes_schema.note_share
                                                            (note_id, user_id) VALUES 
                                                            (:note_id, :user_id)');
        $query->bindParam(':user_id', $userUUID, PDO::PARAM_STR);
        $query->bindParam(':note_id', $note_id, PDO::PARAM_STR);
        return $query->execute();
    }

    public function unshareNote(string $note_id, string $userUUID): bool {
        $query = $this->database->connect()->prepare('DELETE FROM quicknotes_schema.note_share WHERE
                                                            note_id = :note_id AND user_id = :user_id');
        $query->bindParam(':user_id', $userUUID, PDO::PARAM_STR);
        $query->bindParam(':note_id', $note_id, PDO::PARAM_STR);
        return $query->execute();
    }

    public function getUserTags(string $userUUID): Array {
        $tags = [];
        $query = $this->database->connect()->prepare('SELECT * FROM quicknotes_schema.tag n WHERE user_id = :uuid');
        $query->bindParam(':uuid', $userUUID, PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($result === false) {
            return $tags;
        }
        foreach ($result as $tag) {
            $tags[] = new Tag($tag['tag_id'], $tag['tag_name']);
        }

        return $tags;
    }

    public function getNotesByTagsReversed(array $tags, string $userUUID): ?array {
        $query = $this->database->connect()->prepare("
                                        SELECT DISTINCT n.note_id as note_id, title, text 
                                            FROM quicknotes_schema.note n FULL OUTER JOIN
                                            quicknotes_schema.note_tag nt on n.note_id = nt.note_id
                                                WHERE user_id = :user_id AND (nt.tag_id IS null OR nt.tag_id NOT IN ('"
                                                     . implode("','", array_map('strval', $tags))
                                                     . "'))");
        $query->bindParam(':user_id', $userUUID, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($result === false) {
            return null;
        }

        $notes = [];
        foreach ($result as $note) {
            $notes[] = new Note($note['note_id'], $note['title'], $note['text']);
        }
        return $notes;
    }

    public function createTag(string $userUUID, string $tagName): ?string {
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