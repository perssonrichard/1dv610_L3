<?php

namespace controller;

use Config;

class RegisterController
{
    private $registerView;
    private $handleSession;
    private $userDB;

    /**
     * @var \model\RegisterInput
     */
    private $registerInput;


    public function __construct(\view\RegisterView $rv, \model\HandleSession $hs, \model\UserDB $db)
    {
        $this->registerView = $rv;
        $this->handleSession = $hs;
        $this->userDB = $db;
    }

    public function doRegisterAttempt(): void
    {
        $this->registerInput = $this->registerView->getRegisterInput();

        if ($this->registerInputIsCorrect()) {
            $this->successfulRegistration();
        }
    }

    private function registerInputIsCorrect(): bool
    {
        $username = $this->registerInput->getUsername();
        $password = $this->registerInput->getPassword();
        $repeatPassword = $this->registerInput->getRepeatPassword();

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

    private function successfulRegistration(): void
    {
        $this->userDB->addUser($this->registerInput);

        $this->handleSession->setRegisteredNewUser(true);
        $this->handleSession->setRegisteredNewUserName($this->registerInput->getUsername());

        header(Config::$redirectUrl);
        exit();
    }
}
