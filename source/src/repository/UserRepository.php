<?php

require_once 'Repository.php';
require_once __DIR__.'./../models/User.php';
require_once __DIR__."./../../core/Utils.php";

class UserRepository extends Repository
{
    public function getUserByUUID(string $uuid): ?User {
        $stmt = $this->database->connect()->prepare('SELECT * FROM quicknotes_schema.user WHERE uuid = :uuid');
        $stmt->bindParam(':uuid', $uuid, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user == false) {
            return null;
        }

        return new User($user['user_id'], $user['username'], $user['email'], $user['password_hash']);
    }

    public function startSession(string $uuid): string {
        $session_id = Utils::uuid();

        $stmt = $this->database->connect()->prepare('INSERT INTO quicknotes_schema.session (
                     session_id, user_id, last_active) VALUES
                     (:session_id, :user_id, NOW())');
        $stmt->bindParam(':session_id', $session_id, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $uuid, PDO::PARAM_STR);
        $stmt->execute();

        return $session_id;
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