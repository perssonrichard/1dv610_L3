<?php

namespace model;

class HandleSession
{
    private static $sessionValidation = "sessionValidation";
    private static $loggedIn = "loggedIn";
    private static $showBye = "showBye";
    private static $validationString = "sessionValidationString";
    private static $manipulatedCookie = "manipulatedCookie";
    private static $registeredNewUser = "registeredNewUser";
    private static $registeredNewUserName = "registeredNewUserName";

    public function doSetInitialLoggedIn(): void
    {
        if (isset($_SESSION[self::$loggedIn]) == false) {
            $_SESSION[self::$loggedIn] = false;
        }
    }

    public function doValidateSession(ValidationString $validationString): void
    {
        if (isset($_SESSION[self::$sessionValidation])) {
            if ($validationString->getValidation() == $_SESSION[self::$sessionValidation]) {
                $_SESSION[self::$loggedIn] = true;
            } else {
                $_SESSION[self::$loggedIn] = false;
            }
        }
    }

    public function isLoggedIn(): bool
    {
        return $_SESSION[self::$loggedIn];
    }

    public function setLoggedIn(bool $value): void
    {
        $_SESSION[self::$loggedIn] = $value;
    }

    public function setShowBye(bool $value): void
    {
        $_SESSION[self::$showBye] = $value;
    }

    public function setValidationString(\model\ValidationString $validationString): void
    {
        $_SESSION[self::$validationString] = $validationString->getValidation();
    }

    public function setLoggedInWithCookie(bool $value): void
    {
        $_SESSION[self::$loggedInWithCookie] = $value;
    }

    public function setManipulatedCookie(bool $value): void
    {
        $_SESSION[self::$manipulatedCookie] = $value;
    }

    public function setRegisteredNewUser(bool $value): void
    {
        $_SESSION[self::$registeredNewUser] = $value;
    }

    public function setRegisteredNewUserName(string $value): void
    {
        $_SESSION[self::$registeredNewUserName] = $value;
    }
}
