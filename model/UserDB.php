<?php

namespace model;

use ServerConfig;

class UserDB
{
    private $databaseConnection;

    public function __construct()
    {
        try {
            $this->databaseConnection = mysqli_connect(ServerConfig::$dbServerName, ServerConfig::$dbUsername, ServerConfig::$dbPassword, ServerConfig::$dbName);
        } catch (Exception $e) {
            exit('Database connection could not be established.');
        }
    }

    public function addUser(User $user): void
    {
        $username = $user->getUsername();
        $password = $user->getPassword();

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        $sqlSearchString = "INSERT INTO users (user_username, user_pwd) VALUES ('$username', '$passwordHash');";
        mysqli_query($this->databaseConnection, $sqlSearchString);
    }

    private function getUser(User $user): User
    {
        $username = $user->getUsername();

        $sqlSearchString = "SELECT * FROM users WHERE BINARY user_username='$username';";
        $result = mysqli_query($this->databaseConnection, $sqlSearchString);

        $userArr = mysqli_fetch_assoc($result);

        $user = new User($userArr['user_username'], $userArr['user_pwd']);

        return $user;
    }

    public function hasUser(User $user): bool
    {
        $username = $user->getUsername();

        $sqlSearchString = "SELECT * FROM users WHERE BINARY user_username='$username';";

        $result = mysqli_query($this->databaseConnection, $sqlSearchString);

        // If user was found, result is larger than 0
        $resultCheck = mysqli_num_rows($result);
        if ($resultCheck > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function verifyPassword(User $user): bool
    {
        $databaseUser = $this->getUser($user);

        return password_verify($user->getPassword(), $databaseUser->getPassword());
    }
}
