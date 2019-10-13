<?php

namespace model;

class User
{
    private $username;
    private $password;
    private $repeatPassword = "";
    private $keepLoggedIn = false;
    private $loggedInWithCookie = false;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function getUsername() : string
    {
        return $this->username;
    }

    public function getPassword() : string
    {
        return $this->password;
    }

    public function getRepeatPassword() : string
    {
        return $this->repeatPassword;
    }

    public function setRepeatPassword($value) : void
    {
        $this->repeatPassword = $value;
    }

    public function getKeepLoggedIn() : bool
    {
        return $this->keepLoggedIn;
    }

    public function setKeepLoggedIn($value) : void
    {
        $this->keepLoggedIn = $value;
    }

    public function getLoggedInWithCookie() : bool
    {
        return $this->loggedInWithCookie;
    }

    public function setLoggedInWithCookie($value) : void
    {
        $this->loggedInWithCookie = $value;
    }
}