<?php

namespace model;

use EmptyPasswordException;
use EmptyUsernameException;

class UserCredentials
{
    private $username;
    private $password;
    private $keepLoggedIn;

    public function __construct(string $username, string $password, bool $keepLoggedIn)
    {
        if (empty($username)) {
            throw new EmptyUsernameException();
        }
        if (empty($password)) {
            throw new EmptyPasswordException();
        }

        $this->username = $username;
        $this->password = $password;
        $this->keepLoggedIn = $keepLoggedIn;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getKeepLoggedIn(): bool
    {
        return $this->keepLoggedIn;
    }
}
