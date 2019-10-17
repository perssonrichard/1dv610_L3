<?php

namespace model;

class LoggedInState
{
    private static $_loggedIn = "loggedIn";
    private static $_sessionValidation = "sessionValidation";

    public function __construct()
    {
        if (isset($_SESSION[self::$_loggedIn]) == false) {
            $_SESSION[self::$_loggedIn] = false;
        }
    }

    public function setState(bool $value): void
    {
        $_SESSION[self::$_loggedIn] = $value;
    }

    public function getState(): bool
    {
        return $_SESSION[self::$_loggedIn];
    }

    public function setSessionValidation(\model\ValidationString $validationString): void
    {
        $_SESSION[self::$_sessionValidation] = $validationString->getValidation();
    }

    public function doValidateSession(\model\ValidationString $validationString): void
    {
        if (isset($_SESSION[self::$_sessionValidation])) {
            if ($_SESSION[self::$_sessionValidation] == $validationString->getValidation()) {
                $this->setState(true);
            } else {
                $this->setState(false);
            }
        }
    }
}