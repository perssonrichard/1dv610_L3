<?php

namespace model;

class LoggedInState
{
    private static $loggedIn = "_loggedIn";
    private static $sessionValidation = "_sessionValidation";

    public function __construct()
    {
        if (isset($_SESSION[self::$loggedIn]) == false) {
            $_SESSION[self::$loggedIn] = false;
        }
    }

    public function setState(bool $value): void
    {
        $_SESSION[self::$loggedIn] = $value;
    }

    public function getState(): bool
    {
        return $_SESSION[self::$loggedIn];
    }

    public function setSessionValidation(\model\ValidationString $validationString): void
    {
        $_SESSION[self::$sessionValidation] = $validationString->getValidation();
    }

    public function doValidateSession(\model\ValidationString $validationString): void
    {
        if (isset($_SESSION[self::$sessionValidation])) {
            if ($_SESSION[self::$sessionValidation] == $validationString->getValidation()) {
                $this->setState(true);
            } else {
                $this->setState(false);
            }
        }
    }
}