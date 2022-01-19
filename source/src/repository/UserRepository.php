<?php

require_once 'Repository.php';
require_once __DIR__.'./../models/User.php';
require_once __DIR__."./../../core/Utils.php";

class UserRepository extends Repository
{
    public function getUserByUUID(string $uuid): ?User {
        $query = $this->database->connect()->prepare('SELECT * FROM quicknotes_schema.user WHERE uuid = :uuid');
        $query->bindParam(':uuid', $uuid, PDO::PARAM_STR);
        $query->execute();

        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user == false) {
            return null;
        }

        return new User($user['user_id'], $user['username'], $user['email'], $user['password_hash']);
    }

    public function startSession(string $uuid): string {
        $session_id = Utils::uuid();

        $query = $this->database->connect()->prepare('INSERT INTO quicknotes_schema.session (
                     session_id, user_id, last_active) VALUES
                     (:session_id, :user_id, NOW())');
        $query->bindParam(':session_id', $session_id, PDO::PARAM_STR);
        $query->bindParam(':user_id', $uuid, PDO::PARAM_STR);
        $query->execute();

        return $session_id;
    }

    public function validateSession(string $sessionUUID): ?string {

        $query = $this->database->connect()->prepare('SELECT * FROM quicknotes_schema.session 
                        WHERE session_id = :session_id');
        $query->bindParam(':session_id', $sessionUUID, PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result == false) {
            return null;
        }

        $time = DateTime::createFromFormat(DATE_FORMAT, $result['last_active']);
        $time = $time->add(new DateInterval('P7D'));

        return (new DateTime() < $time );
    }

    public function getUserByEmail(string $email): ?User {
        $stmt = $this->database->connect()->prepare('SELECT * FROM quicknotes_schema.user WHERE email = :email');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user == false) {
            return null;
        }

        return new User($user['user_id'], $user['username'], $user['email'], $user['password_hash']);
    }
}