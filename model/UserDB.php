<?php

namespace model;

class UserDB
{
    /**
     * REMOTE SERVER CONNECTION
     */
    private static $dbServerName = "localhost";
    private static $dbUsername = "root";
    private static $dbPassword = "";
    private static $dbName = "loginsystem";

    /**
     * MYSQL TABLE INFORMATION
     */
    private static $sqlTableName = "users";
    private static $sqlNameRow = "user_username";
    private static $sqlPwdRow = "user_pwd";
    private static $sqlPwdCookieRow = "user_pwdCookie";


    private $databaseConnection;

    public function __construct()
    {
        try {
            $this->databaseConnection = mysqli_connect(self::$dbServerName, self::$dbUsername, self::$dbPassword, self::$dbName);
        } catch (Exception $e) {
            exit('Database connection could not be established.');
        }
    }

    public function addUser(RegisterInput $registerInput): void
    {
        $username = $registerInput->getUsername();
        $password = $registerInput->getPassword();

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        $sqlSearchString = "INSERT INTO users (user_username, user_pwd, user_pwdCookie) VALUES ('$username', '$passwordHash', '$passwordHash');";

        mysqli_query($this->databaseConnection, $sqlSearchString);
    }

    public function getUser(UserCredentials $userCredentials): User
    {
        $username = $userCredentials->getUsername();

        $sqlSearchString = "SELECT * FROM users WHERE BINARY user_username='$username';";
        $result = mysqli_query($this->databaseConnection, $sqlSearchString);

        $userArr = mysqli_fetch_assoc($result);

        return new User($userArr['user_username'], $userArr['user_pwd'], $userArr['user_pwdCookie']);
    }

    public function hasUser(string $username): bool
    {
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

    public function verifyPassword(UserCredentials $userCredentials): bool
    {
        $databaseUser = $this->getUser($userCredentials);

        return password_verify($userCredentials->getPassword(), $databaseUser->getPassword());
    }

    public function validateCookies(UserCredentials $userCredentials): bool
    {
        if (!$this->hasUser($userCredentials->getUsername())) {
            return false;
        }

        $databaseUser = $this->getUser($userCredentials);

        if ($databaseUser->getCookiePassword() == $userCredentials->getPassword()) {
            return true;
        } else {
            return false;
        }
    }

    public function rehashCookiePassword(UserCredentials $userCredentials): void
    {
        $dbUser = $this->getUser($userCredentials);

        $username = $dbUser->getUsername();
        $password = $dbUser->getCookiePassword();

        $rehash = password_hash($password, PASSWORD_BCRYPT);

        $sqlUpdateString = "UPDATE users SET user_pwdCookie='$rehash' WHERE user_username='$username';";

        mysqli_query($this->databaseConnection, $sqlUpdateString);
    }
}
