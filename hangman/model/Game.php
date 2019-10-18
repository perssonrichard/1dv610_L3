<?php

namespace hangmanModel;

use AlreadyGuessedLetterException;

class HangmanGame
{
    // Define session variables
    private static $wordToBeGuessed = "wordToBeGuessed";
    private static $correctGuessedLetters = "correctLetters";
    private static $allGuessedLetters = "allLetters";
    private static $numberOfGuesses = "numberOfGuesses";
    private static $guessIsCorrect = "guessIsRight";

    private static $maxNumberOfGuesses = 10;

    public function __construct()
    {
        /**
         * Set sessions if they do not exist
         */
        if (isset($_SESSION[self::$wordToBeGuessed]) == false) {
            $words = new Words();
            $_SESSION[self::$wordToBeGuessed] = $words->getRandomWord();
        }
        if (isset($_SESSION[self::$correctGuessedLetters]) == false) {
            $_SESSION[self::$correctGuessedLetters] = array();
        }
        if (isset($_SESSION[self::$allGuessedLetters]) == false) {
            $_SESSION[self::$allGuessedLetters] = array();
        }
        if (isset($_SESSION[self::$numberOfGuesses]) == false) {
            $_SESSION[self::$numberOfGuesses] = 0;
        }
        if (isset($_SESSION[self::$guessIsCorrect]) == false) {
            $this->guessIsRight = $_SESSION[self::$guessIsCorrect] = false;
        }
    }

    public function getWord(): string
    {
        return $_SESSION[self::$wordToBeGuessed];
    }

    public function getCorrectGuessedLetters(): array
    {
        return $_SESSION[self::$correctGuessedLetters];
    }

    public function getAllGuessedLetters(): array
    {
        return $_SESSION[self::$allGuessedLetters];
    }

    public function doGuessLetter(GuessedLetter $guessedLetter): void
    {
        $letter = $guessedLetter->getLetter();

        if ($this->isAlreadyGuessed($letter)) {
            throw new AlreadyGuessedLetterException();
        }

        if ($this->isCorrectLetter($letter)) {
            $this->doCorrectLetter($letter);
        } else {
            $this->doWrongLetter();
        }

        array_push($_SESSION[self::$allGuessedLetters], $letter);
    }

    public function guessIsCorrect(): bool
    {
        return $_SESSION[self::$guessIsCorrect];
    }

    private function isAlreadyGuessed(string $letter): bool
    {
        foreach ($_SESSION[self::$allGuessedLetters] as $value) {
            if ($value == $letter) {
                return true;
            }
        }
        return false;
    }

    private function isCorrectLetter(string $letter): bool
    {
        if (stristr($_SESSION[self::$wordToBeGuessed], $letter)) {
            return true;
        } else {
            return false;
        }
    }

    private function doCorrectLetter(string $letter): void
    {
        array_push($_SESSION[self::$correctGuessedLetters], $letter);
        $_SESSION[self::$guessIsCorrect] = true;
    }

    private function doWrongLetter(): void
    {
        $_SESSION[self::$numberOfGuesses] += 1;
        $_SESSION[self::$guessIsCorrect] = false;
    }
}
