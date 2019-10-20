<?php

namespace model;

use Exception;

class UserDB
{
    /**
     * Server connection
     */
    private static $dbServerName = "localhost";
    private static $dbUsername = "persglgr_root";
    private static $dbPassword = "pa)gYnW99x*j";
    private static $dbName = "persglgr_loginsystem_L3";

    /**
     * MySQL table information
     */
    private static $sqlTableName = "users";
    private static $sqlNameRow = "user_username";
    private static $sqlPwdRow = "user_pwd";
    private static $sqlPwdCookieRow = "user_pwdCookie";

    /**
     * @var MySQL
     */
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

        $sqlAddUserString =
            "INSERT INTO " . self::$sqlTableName .
            " ("
            . self::$sqlNameRow . ", " . self::$sqlPwdRow . ", " . self::$sqlPwdCookieRow .
            ") " .
            "VALUES ('$username', '$passwordHash', '$passwordHash');";

        mysqli_query($this->databaseConnection, $sqlAddUserString);
    }

    public function getUser(UserCredentials $userCredentials): User
    {
        $username = $userCredentials->getUsername();

        $sqlGetUserString = "SELECT * FROM " . self::$sqlTableName . " WHERE BINARY " . self::$sqlNameRow . "='$username';";
        
        $result = mysqli_query($this->databaseConnection, $sqlGetUserString);
        $userArr = mysqli_fetch_assoc($result);

        return new User($userArr[self::$sqlNameRow], $userArr[self::$sqlPwdRow], $userArr[self::$sqlPwdCookieRow]);
    }

    public function hasUser(string $username): bool
    {
        $sqlSearchString = "SELECT * FROM " . self::$sqlTableName . " WHERE BINARY " . self::$sqlNameRow . "='$username';";

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

    public function isValidatedCookies(UserCredentials $userCredentials): bool
    {
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

        $sqlUpdateString = "UPDATE " . self::$sqlTableName . " SET " . self::$sqlPwdCookieRow . "='$rehash' WHERE " . self::$sqlNameRow . "='$username';";

        mysqli_query($this->databaseConnection, $sqlUpdateString);
    }
}
