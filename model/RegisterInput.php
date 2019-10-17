<?php

namespace model;

class RegisterInput
{
    private $username;
    private $password;
    private $repeatPassword;

    public function __construct($username, $password, $repeatPassword)
    {
        $this->username = $username;
        $this->password = $password;
        $this->repeatPassword = $repeatPassword;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRepeatPassword(): string
    {
        return $this->repeatPassword;
    }
}
