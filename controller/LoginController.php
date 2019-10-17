<?php

namespace controller;

use Config;

class LoginController
{
    private $loginView;
    private $userDB;
    private $handleSession;

    /**
     * @var \model\UserCredentials
     */
    private $userCredentials;

    public function __construct(\view\LoginView $lv, \model\UserDB $db, \model\HandleSession $hs)
    {
        $this->loginView = $lv;
        $this->userDB = $db;
        $this->handleSession = $hs;
    }

    public function doLoginAttempt(): void
    {
        $this->userCredentials = $this->loginView->getUserCredentials();

        if ($this->loginInputIsCorrect()) {
            $this->loginView->setWelcomeSession($this->userCredentials);

            $this->handleSession->setLoggedIn(true);
            $this->handleSession->setValidationString($this->loginView->getValidationString());

            header(Config::$redirectUrl);
            exit();
        }
    }

    public function handleCookieUser(): void
    {
        $user = $this->loginView->getCookieUser();

        if ($this->userDB->validateCookies($user)) {
            $this->loginView->setLoggedInWithCookieSession();
            $this->handleSession->setLoggedIn(true);
        } else {
            $this->handleSession->setManipulatedCookie(true);
        }
    }

    private function loginInputIsCorrect(): bool
    {
        // $usernameIsEmpty = empty($this->userCredentials->getUsername());
        // $passwordIsEmpty = empty($this->userCredentials->getPassword());
        // $databaseHasUser = $this->userDB->hasUser($this->userCredentials->getUsername());

        // if ($usernameIsEmpty || $passwordIsEmpty || $databaseHasUser == false) {
        //     return false;
        // } else if ($this->userDB->verifyPassword($this->userCredentials)) {
        //     return true;
        // } else {
        //     return false;
        // }

        if ($this->userDB->verifyPassword($this->userCredentials)) {
            return true;
        } else {
            return false;
        }
    }
}
