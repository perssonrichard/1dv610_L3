<?php

namespace model;

class User
{
    private $username;
    private $password;
    private $cookiePassword;

    public function __construct(string $username, string $password, string $cookiePassword)
    {
        $this->username = $username;
        $this->password = $password;
        $this->cookiePassword = $cookiePassword;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getCookiePassword(): string
    {
        return $this->cookiePassword;
    }
}
