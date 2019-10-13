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
            // If no session but valid cookies
        } else if ($_SESSION['loggedin'] == false && $this->loginView->hasCookieUser()) {
            $this->handleCookieUser();
        } else {
            $this->handleIsNotLoggedIn();
        }
    }

    private function handleIsLoggedIn(): void
    {
        if ($this->loginView->userWantsToLogOut()) {
            $this->doLogout();
        }

        if ($this->loginView->hasCookieUser()) {
            $userCredentials = $this->loginView->getCookieUser();

            $this->userDB->rehashCookiePassword($userCredentials);

            $updatedUser = $this->userDB->getUser($userCredentials);

            $this->loginView->setCookies($updatedUser);
        }

        $this->messageController->setLoggedInMsg();
    }

    private function handleCookieUser(): void
    {
        $user = $this->loginView->getCookieUser();

        if ($this->userDB->validateCookies($user)) {
            $_SESSION['loggedinWithCookie'] = true;
            $_SESSION['loggedin'] = true;

            $this->messageController->setLoggedInMsg();
        } else {
            $_SESSION['manipulatedCookie'] = true;

            $this->messageController->setNotLoggedInMsg();
        }
    }

    private function handleIsNotLoggedIn(): void
    {
        if ($this->loginView->userWantsToLogIn()) {
            $this->doLoginAttempt($this->loginView->getUserCredentials());
        } else if ($this->registerView->userWantsToRegister()) {
            $this->doRegisterAttempt($this->registerView->getRegisterInput());
        }

        $this->messageController->setNotLoggedInMsg();
    }

    private function validateSession(): void
    {
        if (isset($_SESSION["loggedin"]) == false) {
            $_SESSION["loggedin"] = false;
        }

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

    public function doLoginAttempt(\model\UserCredentials $userCredentials): void
    {
        if ($this->loginInputIsCorrect($userCredentials)) {
            $this->successfulLogin($userCredentials);
        } else {
            $this->messageController->setUnsuccessfulLoginMsg($userCredentials);
        }
    }

    private function loginInputIsCorrect(\model\UserCredentials $userCredentials): bool
    {
        if (empty($userCredentials->getUsername()) || empty($userCredentials->getPassword()) || $this->userDB->hasUser($userCredentials->getUsername()) == false) {
            return false;
        } else if ($this->userDB->verifyPassword($userCredentials)) {
            return true;
        } else {
            return false;
        }
    }

    private function successfulLogin(\model\UserCredentials $userCredentials): void
    {
        // If keep me logged in is checked
        if ($userCredentials->getKeepLoggedIn()) {
            $user = $this->userDB->getUser($userCredentials);

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

    public function doRegisterAttempt(\model\RegisterInput $registerInput): void
    {
        if ($this->registrationInputIsCorrect($registerInput)) {
            $this->successfulRegistration($registerInput);
        } else {
            $this->messageController->setUnsuccessfulRegisterMsg($registerInput);
        }
    }

    private function registrationInputIsCorrect(\model\RegisterInput $registerInput): bool
    {
        $username = $registerInput->getUsername();
        $password = $registerInput->getPassword();
        $repeatPassword = $registerInput->getRepeatPassword();

        if (
            empty($username) ||
            empty($password) ||
            strlen($username) < 3 ||
            strlen($password) < 6 ||
            $username != strip_tags($username) ||
            $this->userDB->hasUser($username) ||
            $password != $repeatPassword
        ) {
            return false;
        } else {
            return true;
        }
    }

    private function successfulRegistration(\model\RegisterInput $registerInput): void
    {
        $this->userDB->addUser($registerInput);

        $_SESSION['registeredNewUser'] = true;
        $_SESSION['registeredNewUserName'] = $registerInput->getUsername();

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
