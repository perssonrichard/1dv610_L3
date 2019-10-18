<?php

namespace controller;

class RegisterController
{
    private static $redirectUrl = 'Location: index.php';

    private $registerView;
    private $userDB;
    private $loginView;

    /**
     * @var \model\RegisterInput
     */
    private $registerInput;


    public function __construct(\view\RegisterView $rv, \view\LoginView $lv, \model\UserDB $db)
    {
        $this->registerView = $rv;
        $this->loginView = $lv;
        $this->userDB = $db;
    }

    public function doRegisterAttempt(): void
    {
        $this->registerInput = $this->registerView->getRegisterInput();

        if ($this->registerInputIsCorrect()) {
            $this->successfulRegistration();
        } else {
            $this->registerView->setWrongInputMessage();
        }
    }

    /**
     * TODO: These rules should not be dependant on magic numbers and
     * should exist in the model, possibly caught as an exception.
     */
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

        $this->loginView->setNewUserSession();
        $this->loginView->setNewUsersNameSession($this->registerInput->getUsername());

        header(self::$redirectUrl);
        exit();
    }
}
