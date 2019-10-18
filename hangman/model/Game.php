<?php

namespace hangmanModel;

require_once("HangedMan.php");
require_once("Words.php");

class HangmanGame
{
    // Define session variables
    private static $_word = "wordToBeGuessed";
    private static $_correctLetters = "correctLetters";
    private static $_wrongLetters = "wrongLetters";
    private static $_allLetters = "allLetters";
    private static $_numberOfGuesses = "numberOfGuesses";

    private static $maxNumberOfGuesses = 10;

    private $wordToBeGuessed;

    private $correctGuessedLetters;
    private $wrongGuessedLetters;
    private $allGuessedLetters;

    private $numberOfGuesses;

    public function __construct()
    {
        // Get word if game is running, else get a new word
        if (isset($_SESSION[self::$_word])) {
            $this->wordToBeGuessed = $_SESSION[self::$_word];
        } else {
            $words = new Words();

            $this->wordToBeGuessed = $words->getRandomWord();
            $_SESSION[self::$_word] = $this->wordToBeGuessed;
        }

        // Get correct guessed letters if game is running
        if (isset($_SESSION[self::$_correctLetters])) {
            $this->correctGuessedLetters = $_SESSION[self::$_correctLetters];
        } else {
            $this->correctGuessedLetters = array();
        }

        // Get wrong guessed letters if game is running
        if (isset($_SESSION[self::$_wrongLetters])) {
            $this->wrongGuessedLetters = $_SESSION[self::$_wrongLetters];
        } else {
            $this->wrongGuessedLetters = array();
        }

        // Get all guessed letters if game is running
        if (isset($_SESSION[self::$_allLetters])) {
            $this->allGuessedLetters = $_SESSION[self::$_allLetters];
        } else {
            $this->allGuessedLetters = array();
        }

        // Get number of guesses if game is running
        if (isset($_SESSION[self::$_numberOfGuesses])) {
            $this->numberOfGuesses = $_SESSION[self::$_numberOfGuesses];
        } else {
            $this->numberOfGuesses = 0;
        }
    }

    public function addLetter(string $letter): void {
        
    }

    public function getWord(): string
    {
        return $this->wordToBeGuessed;
    }

    public function getCorrectGuessedLetters(): array
    {
        return $this->correctGuessedLetters;
    }

    public function getWrongGuessedLetters(): array
    {
        return $this->wrongGuessedLetters;
    }

    public function getAllGuessedLetters(): array
    {
        return $this->allGuessedLetters;
    }

    public function isAlreadyGuessed(string $letter): bool
    {
        foreach ($this->allGuessedLetters as $value) {
            if ($value == $letter) {
                return true;
            }
        }

        return false;
    }
}
