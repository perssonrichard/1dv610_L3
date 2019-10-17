<?php

namespace model;

class ValidationString
{
    private $validation;

    public function __construct(string $IP, string $userAgent)
    {
        $this->validation = $IP . $userAgent;
    }

    public function getValidation(): string
    {
        return $this->validation;
    }
}