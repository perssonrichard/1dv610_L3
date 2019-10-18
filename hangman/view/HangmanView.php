<?php

namespace hangmanView;

class HangmanView
{
    private static $guess = "HangmanView::Guess";
    private static $submit = "HangmanView::SubmitGuess";
    private static $message = "HangmanView::Message";

    private $game;

    public function __construct(\hangmanModel\HangmanGame $game)
    {
        if (isset($_SESSION[self::$message]) == false) {
            $_SESSION[self::$message] = "";
        }

        $this->game = $game;
    }

    public function showGame(): string
    {
        return '
        <div class="container">

        <form method="post" > 
        <fieldset>            
            <label for="' . self::$guess . '">Your Guess :</label>
            <input type="text" id="' . self::$guess . '" name="' . self::$guess . '" value="" />
            <input type="submit" name="' . self::$submit . '" value="Submit Guess" />
        </fieldset>
    </form>
        <p>' . $_SESSION[self::$message] . '</p>
        <p>' . $this->showWord() . '</p>

        </div>
        ';
    }

    public function setCorrectGuessMessage(): void {
        $_SESSION[self::$message] = "Correct guess!";
    }

    public function setWrongGuessMessage(): void {
        $_SESSION[self::$message] = "Wrong guess! :(";
    }

    public function playerIsTryingToGuessLetter(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST[self::$submit])) {
            return true;
        } else {
            return false;
        }
    }

    public function getGuessedLetter(): \hangmanModel\GuessedLetter
    {
        return new \hangmanModel\GuessedLetter($_POST[self::$guess]);
    }

    private function showGuessedLetters(): string
    {
        $letters = $this->game->getAllGuessedLetters();
        $letterString = "";

        foreach ($letters as $letter) {
            $letterString .= "$letter, ";
        }

        return $letterString;
    }

    private function showWord(): string
    {
        $word = $this->game->getWord();
        $correctGuessedLetters = $this->game->getCorrectGuessedLetters();

        $wordString = "";

        foreach (str_split($word) as $letter) {
            if (in_array($letter, $correctGuessedLetters, true)) {
                $wordString .= $letter;
            } else {
                $wordString .= " _ ";
            }
        }

        return $wordString;
    }
}
