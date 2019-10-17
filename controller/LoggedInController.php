<?php

namespace controller;

class LoggedInController
{
    private $loginView;
    private $userDB;

    public function __construct(\view\LoginView $lv, \model\UserDB $db)
    {
        $this->loginView = $lv;
        $this->userDB = $db;
    }

    public function refreshCookies(): void
    {
        $userCredentials = $this->loginView->getCookieUser();

        $this->userDB->rehashCookiePassword($userCredentials);

        $updatedUser = $this->userDB->getUser($userCredentials);

        $this->loginView->setCookies($updatedUser);
    }
}
