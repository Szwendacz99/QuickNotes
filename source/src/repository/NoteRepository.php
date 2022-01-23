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
           $notes[] = new Note($note['title'], $note['text']);
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

}