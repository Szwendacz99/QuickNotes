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

    public function changeNickname($userUUID, $nickname): string {

        if ($this->getUserByNickname($nickname) !== null) {
            return "Nickname already taken!";
        }

        $query = $this->database->connect()->prepare('UPDATE quicknotes_schema.user SET username = :username WHERE
                                                                user_id = :user_id');
        $query->bindParam(':user_id', $userUUID, PDO::PARAM_STR);
        $query->bindParam(':username', $nickname, PDO::PARAM_STR);
        $query->execute();

        return "Nickname changed successfully!";
    }

    public function addUser(string $username, string $password, string $email): ?string {

        if ($this->getUserByEmail($email) !== null) {
            return null;
        }

        $uuid = Utils::uuid();
        $password = password_hash($password, PASSWORD_BCRYPT);

        $query = $this->database->connect()->prepare('INSERT INTO quicknotes_schema.user 
            (user_id, username, password_hash, email) VALUES
            (:uuid, :username, :password, :email)');
        $query->bindParam(':uuid', $uuid, PDO::PARAM_STR);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();

        return $uuid;
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

    public function getUserByNickname(string $nickname): ?User {
        $query = $this->database->connect()->prepare('SELECT * FROM quicknotes_schema.user WHERE username = :username');
        $query->bindParam(':username', $nickname, PDO::PARAM_STR);
        $query->execute();

        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user == false) {
            return null;
        }

        return new User($user['user_id'], $user['username'], $user['email'], $user['password_hash']);
    }

    public function authorize(): bool {
        if (! isset($_COOKIE['session_id']) ){
            return false;
        }
        $user = $this->getUserBySessionUUID($_COOKIE['session_id']);

        if ($user === null) {
            return false;
        }

        return true;
    }

}