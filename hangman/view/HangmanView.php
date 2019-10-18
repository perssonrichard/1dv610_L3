<?php

namespace hangmanView;

class HangmanView
{
    private static $_guess = "HangmanView::Guess";
    private static $_submit = "HangmanView::SubmitGuess";

    private $game;

    public function __construct(\hangmanModel\HangmanGame $game)
    {
        $this->game = $game;
    }

    public function showGame(): string
    {
        return '
        <div class="container">

        <form method="post" > 
        <fieldset>            
            <label for="' . self::$_guess . '">Your Guess :</label>
            <input type="text" id="' . self::$_guess . '" name="' . self::$_guess . '" value="" />
            <input type="submit" name="' . self::$_submit . '" value="Submit Guess" />
        </fieldset>
    </form>

        ' . $this->showWord() . '

        </div>
        ';
    }

    private function showGuessedLetters(): string
    {
        $letters = $this->game->getAllGuessedLetters();
        $letterString = "";

        foreach($letters as $letter) {
            $letterString .= "$letter, ";
        }

        return $letterString;
    } 

    private function showWord(): string
    {
        $word = $this->game->getWord();
        $correctGuessedLetters = $this->game->getCorrectGuessedLetters();

        $wordString = "";

        foreach(str_split($word) as $letter) {
            if (in_array($letter, $correctGuessedLetters, true)) {
                $wordString .= $letter;
            } else {
                $wordString .= " _ ";
            }
        }

        return $wordString;
    }
}
