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

    public function startSession(string $userUUID): string {

        $query = $this->database->connect()->prepare('DELETE FROM quicknotes_schema.session 
                        WHERE user_id = :userUUID');
        $query->bindParam(':userUUID', $userUUID, PDO::PARAM_STR);
        $query->execute();

        $session_id = Utils::uuid();

        $query = $this->database->connect()->prepare('INSERT INTO quicknotes_schema.session (
                     session_id, user_id, last_active) VALUES
                     (:session_id, :user_id, NOW())');
        $query->bindParam(':session_id', $session_id, PDO::PARAM_STR);
        $query->bindParam(':user_id', $userUUID, PDO::PARAM_STR);
        $query->execute();

        return $session_id;
    }

    public function getUserBySessionUUID(string $sessionUUID): ?User {
        $query = $this->database->connect()->prepare('SELECT * FROM quicknotes_schema.user u 
            JOIN quicknotes_schema.session s on u.user_id = s.user_id
            WHERE session_id = :session_id');
        $query->bindParam(':session_id', $sessionUUID, PDO::PARAM_STR);
        $query->execute();

        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user == false) {
            return null;
        }

        return new User($user['user_id'], $user['username'], $user['email'], $user['password_hash']);
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

        try {
            $time = new DateTime($result['last_active']);
        } catch (Exception $e) {
            return false;
        }
        $time = $time->add(new DateInterval('P7D'));

        return (new DateTime() < $time );
    }

    public function refreshSession(string $userUUID): ?string {

        $time = (new DateTime)->format(DATE_FORMAT);
        $query = $this->database->connect()->prepare('UPDATE quicknotes_schema.session 
                        SET last_active = :last_active WHERE user_id = :user_id');
        $query->bindParam(':last_active', $time, PDO::PARAM_STR);
        $query->bindParam(':user_id', $userUUID, PDO::PARAM_STR);
        $query->execute();

        $query = $this->database->connect()->prepare('SELECT session_id FROM quicknotes_schema.session 
                        WHERE user_id = :user_id');
        $query->bindParam(':user_id', $userUUID, PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result == false) {
            return null;
        }

        return $result['session_id'];

    }

    public function getUserByEmail(string $email): ?User {
        $query = $this->database->connect()->prepare('SELECT * FROM quicknotes_schema.user WHERE email = :email');
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();

        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user == false) {
            return null;
        }

        return new User($user['user_id'], $user['username'], $user['email'], $user['password_hash']);
    }
}