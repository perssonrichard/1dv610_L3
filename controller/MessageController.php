<?php

namespace controller;

class MessageController
{
    private $message;
    private $loginView;
    private $userDB;

    public function __construct(\model\Message $m, \view\LoginView $lv, \model\UserDB $db)
    {
        $this->message = $m;
        $this->loginView = $lv;
        $this->userDB = $db;
    }

    public function setLoggedInMsg(): void
    {
        if (isset($_SESSION['loggedinWithCookie']) && $_SESSION['loggedinWithCookie']) {
            $this->message->setMessage("Welcome back with cookie");

            $_SESSION['loggedinWithCookie'] = false;
        }
        if (isset($_SESSION['showWelcomeKeep']) && $_SESSION["showWelcomeKeep"]) {
            $this->message->setMessage("Welcome and you will be remembered");

            $_SESSION['showWelcomeKeep'] = false;
        }
        if (isset($_SESSION["showWelcome"]) && $_SESSION["showWelcome"]) {
            $this->message->setMessage("Welcome");

            $_SESSION['showWelcome'] = false;
        }
    }

    public function setNotLoggedInMsg(): void
    {
        // If recently registered
        if (isset($_SESSION['registeredNewUser']) && $_SESSION['registeredNewUser'] == true) {
            $this->message->setMessage("Registered new user.");
            $this->message->setFormUsername($_SESSION['registeredNewUserName']);

            $_SESSION['registeredNewUser'] = false;
        }

        // If recently logged out
        if (isset($_SESSION['showBye']) && $_SESSION['showBye'] == true) {
            $this->message->setMessage("Bye bye!");

            $_SESSION['showBye'] = false;
        }

        if (isset($_SESSION['manipulatedCookie']) && $_SESSION['manipulatedCookie']) {
            $this->message->setMessage("Wrong information in cookies");
            $this->loginView->deleteCookies();

            $_SESSION['manipulatedCookie'] = false;
        }
    }

    public function setUnsuccessfulLoginMsg(\model\UserCredentials $userCredentials): void
    {
        $msg = "";

        if (empty($userCredentials->getUsername())) {
            $msg .= "Username is missing";
        } else if (empty($userCredentials->getPassword())) {
            $msg .= "Password is missing";
        } else {
            $msg .= "Wrong name or password";
        }

        $this->message->setMessage($msg);
        $this->message->setFormUsername($userCredentials->getUsername());
    }

    public function setUnsuccessfulRegisterMsg(\model\RegisterInput $registerInput): void
    {
        $username = $registerInput->getUsername();
        $password = $registerInput->getPassword();
        $repeatPassword = $registerInput->getRepeatPassword();

        $msg = "";

        if (empty($username) || strlen($username) < 3) {
            $msg .= "Username has too few characters, at least 3 characters.<br>";
        }
        if ($username != strip_tags($username)) {
            $msg .= "Username contains invalid characters.<br>";
        }
        if (empty($password) || strlen($password) < 6) {
            $msg .= "Password has too few characters, at least 6 characters.<br>";
        }
        if ($this->userDB->hasUser($username)) {
            $msg .= "User exists, pick another username.<br>";
        }
        if ($password != $repeatPassword) {
            $msg .= "Passwords do not match.<br>";
        }

        $this->message->setMessage($msg);
        $this->message->setFormUsername(strip_tags($registerInput->getUsername()));
    }
}
