<?php

namespace controller;

use Config;

class AuthController
{
    private $userDB;
    private $loginView;
    private $registerView;
    private $messageController;

    public function __construct(\model\UserDB $db, \view\LoginView $lv, \view\RegisterView $rv, MessageController $mc)
    {
        $this->userDB = $db;
        $this->loginView = $lv;
        $this->messageController = $mc;
        $this->registerView = $rv;
    }

    public function run(): void
    {
        $this->validateSession();

        if ($_SESSION['loggedin']) {
            $this->handleIsLoggedIn();
        } else {
            $this->handleIsNotLoggedIn();
        }
    }

    private function handleIsLoggedIn(): void
    {
        if ($this->loginView->hasCookieUser()) {
            $this->handleCookieUser();
        }

        if ($this->loginView->userWantsToLogOut()) {
            $this->doLogout();
        }

        $this->messageController->setLoggedInMsg();
    }

    private function handleCookieUser(): void
    {
        $user = $this->loginView->getCookieUser();

        $this->loginView->setCookies($user);

        if ($this->loginView->validateCookies()) {
            $_SESSION['loggedinWithCookie'] = true;
        } else {
            $_SESSION['manipulatedCookie'] = true;
        }
    }

    private function handleIsNotLoggedIn(): void
    {
        if ($this->loginView->userWantsToLogIn()) {
            $this->doLoginAttempt($this->loginView->getLoginUser());
        } else if ($this->registerView->userWantsToRegister()) {
            $this->doRegisterAttempt($this->registerView->getRegisterUser());
        }

        $this->messageController->setNotLoggedInMsg();
    }

    private function validateSession(): void
    {
        if (isset($_SESSION['sessionValidationString'])) {
            $sessionValidationString = $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'];
        }

        if (isset($sessionValidationString)) {
            if ($sessionValidationString == $_SESSION['sessionValidationString']) {
                $_SESSION['loggedin'] = true;
            } else {
                $_SESSION['loggedin'] = false;
            }
        }
    }

    public function doLoginAttempt(\model\User $user): void
    {
        if ($this->loginInputIsCorrect($user)) {
            $this->successfulLogin($user);
        } else {
            $this->messageController->setUnsuccessfulLoginMsg($user);
        }
    }

    private function loginInputIsCorrect(\model\User $user): bool
    {
        if (empty($user->getUsername()) || empty($user->getPassword()) || $this->userDB->verifyPassword($user) == false) {
            return false;
        } else {
            return true;
        }
    }

    private function successfulLogin(\model\User $user): void
    {
        // If keep me logged in is checked
        if ($user->getKeepLoggedIn()) {
            $this->loginView->setCookies($user);

            $_SESSION['showWelcomeKeep'] = true;
        } else {
            $_SESSION['showWelcome'] = true;
        }

        $_SESSION['loggedin'] = true;

        $_SESSION['sessionValidationString'] = $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'];

        header(Config::$redirectUrl);
        exit();
    }

    public function doRegisterAttempt(\model\User $user): void
    {
        if ($this->registrationInputIsCorrect($user)) {
            $this->successfulRegistration($user);
        } else {
            $this->messageController->setUnsuccessfulRegisterMsg($user);
        }
    }

    private function registrationInputIsCorrect($user): bool
    {
        $username = $user->getUsername();
        $password = $user->getPassword();
        $repeatPassword = $user->getRepeatPassword();

        if (
            empty($username) ||
            empty($password) ||
            strlen($username) < 3 ||
            strlen($password) < 6 ||
            $username != strip_tags($username) ||
            $this->userDB->hasUser($user) ||
            $password != $repeatPassword
        ) {
            return false;
        } else {
            return true;
        }
    }

    private function successfulRegistration(\model\User $user): void
    {
        $this->userDB->addUser($user);

        $_SESSION['registeredNewUser'] = true;
        $_SESSION['registeredNewUserName'] = $user->getUsername();

        header(Config::$redirectUrl);
        exit();
    }

    public function doLogout(): void
    {
        if ($this->loginView->hasCookieUser()) {
            $this->loginView->deleteCookies();
        }

        session_unset();

        $_SESSION['showBye'] = true;

        header(Config::$redirectUrl);
        exit();
    }
}
