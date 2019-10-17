<?php

namespace controller;

use emptyPasswordException;
use emptyUsernameException;

class MasterController
{
    private $loginView;
    private $registerView;

    private $loginController;
    private $loggedInController;
    private $logoutController;
    private $registerController;

    private $handleSession;

    public function __construct(\model\UserDB $db, \view\LoginView $lv, \view\RegisterView $rv, \model\Message $m)
    {
        $this->loginView = $lv;
        $this->registerView = $rv;

        $this->handleSession = new \model\HandleSession();

        $this->loginController = new LoginController($lv, $db, $this->handleSession);
        $this->loggedInController = new LoggedInController($lv, $db);
        $this->logoutController = new LogoutController($lv, $this->handleSession);
        $this->registerController = new RegisterController($rv, $this->handleSession, $db);
    }

    public function run()
    {
        try {
            $this->handleSession->doSetInitialLoggedIn();
            $this->handleSession->doValidateSession($this->loginView->getValidationString());

            if ($this->handleSession->isLoggedIn()) {
                $this->handleLoggedIn();
            } else {
                $this->handleNotLoggedIn();
            }
        } catch (emptyUsernameException $e) {
            $this->loginView->setEmptyUsernameMessage();
        } catch (emptyPasswordException $e) {
            $this->loginView->setEmptyPasswordMessage();
        }
    }

    private function handleNotLoggedIn(): void
    {
        if ($this->loginView->userTriesToLogIn()) {
            $this->loginController->doLogInAttempt();
        } else if ($this->loginView->hasCookieUser()) {
            $this->loginController->handleCookieUser();
        } else if ($this->registerView->userTriesToRegister()) {
            $this->registerController->doRegisterAttempt();
        }
    }

    private function handleLoggedIn(): void
    {
        if ($this->loginView->hasCookieUser()) {
            $this->loggedInController->refreshCookies();
        } else if ($this->loginView->userWantsToLogOut()) {
            $this->logoutController->doLogOut();
        }
    }
}
