<?php

namespace controller;

use Config;

class LogoutController
{
    private $loginView;

    public function __construct(\view\LoginView $lv)
    {
        $this->loginView = $lv;
    }

    public function doLogout(): void
    {
        if ($this->loginView->hasCookieUser()) {
            $this->loginView->deleteCookies();
        }

        session_unset();

        $this->loginView->setLogoutSession();

        header(Config::$redirectUrl);
        exit();
    }
}
