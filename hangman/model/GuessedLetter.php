<?php

namespace hangmanModel;

class GuessIsNotOneLetterException extends \Exception {}

class GuessedLetter
{
    private $letter;

    public function __construct(string $letter)
    {
        if (!ctype_alpha($letter) || strlen($letter) != 1) {
            throw new GuessIsNotOneLetterException();
        }

        $this->letter = strtolower($letter);
    }

    public function getLetter(): string
    {
        return $this->letter;
    }
}