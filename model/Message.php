<?php

namespace model;

class Message
{
    private $message = "";
    private $formUsername = "";

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage($value): void
    {
        $this->message = $value;
    }

    public function getFormUsername(): string
    {
        return $this->formUsername;
    }

    public function setFormUsername($value): void
    {
        $this->formUsername = $value;
    }
}
