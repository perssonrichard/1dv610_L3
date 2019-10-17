<?php

namespace model;

use emptyPasswordException;
use emptyUsernameException;

class UserCredentials
{
    private $username;
    private $password;
    private $keepLoggedIn;

    public function __construct(string $username, string $password, bool $keepLoggedIn)
    {
        if (empty($username)) { throw new emptyUsernameException(); }
        if (empty($password)) { throw new emptyPasswordException(); }

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