<?php

namespace controller;

use Config;

class LogoutController
{
    private $loginView;
    private $handleSession;

    public function __construct(\view\LoginView $lv, \model\HandleSession $hs)
    {
        $this->loginView = $lv;
        $this->handleSession = $hs;
    }

    public function doLogout(): void
    {
        if ($this->loginView->hasCookieUser()) {
            $this->loginView->deleteCookies();
        }

        session_unset();
        $this->handleSession->setShowBye(true);

        header(Config::$redirectUrl);
        exit();
    }
}
