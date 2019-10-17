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

    private $loggedInState;

    public function __construct(\model\LoggedInState $lis, \model\UserDB $db, \view\LoginView $lv, \view\RegisterView $rv)
    {
        $this->loginView = $lv;
        $this->registerView = $rv;
        $this->loggedInState = $lis;

        $this->loginController = new LoginController($lv, $db, $lis);
        $this->loggedInController = new LoggedInController($lv, $db);
        $this->logoutController = new LogoutController($lv);
        $this->registerController = new RegisterController($rv, $lv, $db);
    }

    public function run()
    {
        $this->loggedInState->doValidateSession($this->loginView->getValidationString());

        if ($this->loggedInState->getState()) {
            $this->handleLoggedIn();
        } else {
            $this->handleNotLoggedIn();
        }
    }

    private function handleNotLoggedIn(): void
    {
        if ($this->loginView->userTriesToLogIn()) {
            try {
                $this->loginController->doLogInAttempt();
            } catch (EmptyUsernameException $e) {
                $this->loginView->setEmptyUsernameMessage();
            } catch (EmptyPasswordException $e) {
                $this->loginView->setEmptyPasswordMessage();
            }
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
