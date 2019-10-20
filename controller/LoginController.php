<?php

namespace controller;

class LoginController
{
    private $loginView;
    private $userDB;
    private $loggedInState;

    /**
     * @var \model\UserCredentials
     */
    private $userCredentials;

    public function __construct(\view\LoginView $lv, \model\UserDB $db, \model\LoggedInState $lis)
    {
        $this->loginView = $lv;
        $this->userDB = $db;
        $this->loggedInState = $lis;
    }

    public function doLoginAttempt(): void
    {
        $this->userCredentials = $this->loginView->getUserCredentials();

        if ($this->loginInputIsCorrect()) {
            $this->loginView->setWelcomeSession();

            $this->loggedInState->setState(true);
            $this->loggedInState->setSessionValidation($this->loginView->getValidationString());

            header(\view\UrlView::$redirect);
            exit();
        } else {
            $this->loginView->setWrongUsernameOrPasswordMessage();
        }
    }

    /**
     * Called when a user has cookies but no login state
     */
    public function handleCookieUser(): void
    {
        $user = $this->loginView->getCookieUser();

        if ($this->userDB->hasUser($user->getUsername()) && $this->userDB->isValidatedCookies($user)) {
            $this->loginView->setLoggedInWithCookieSession();
            $this->loggedInState->setState(true);
        } else {
            $this->loginView->setManipulatedCookiesMessage();
            $this->loginView->deleteCookies();
        }
    }

    private function loginInputIsCorrect(): bool
    {
        if ($this->userDB->hasUser($this->userCredentials->getUsername())) {
            if ($this->userDB->verifyPassword($this->userCredentials)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
