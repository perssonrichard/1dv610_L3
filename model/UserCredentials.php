<?php

namespace model;

class UserCredentials
{
    private $username = "";
    private $password = "";
    private $keepLoggedIn = false;

    public function __construct($username, $password, $keepLoggedIn)
    {
        $this->username = $username;
        $this->password = $password;
        $this->keepLoggedIn = $keepLoggedIn;
    }

    public function getUsername() : string
    {
        return $this->username;
    }

    public function getPassword() : string
    {
        return $this->password;
    }

    public function getKeepLoggedIn() : bool
    {
        return $this->keepLoggedIn;
    }
}