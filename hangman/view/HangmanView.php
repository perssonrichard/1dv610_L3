<?php

namespace hangmanView;

class HangmanView
{
    private static $guess = "HangmanView::Guess";
    private static $submit = "HangmanView::SubmitGuess";
    private static $message = "HangmanView::Message";
    private static $restart = "HangmanView::Restart";

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
            <pre>' . $this->game->getHangedMan() . '</pre>
            <p>' . $this->showWord() . '</p>
            <p>Guessed letters: ' . $this->showGuessedLetters() . '</p>
            ' . $this->showForm() . '
            <p>' . $_SESSION[self::$message] . '</p>
        </div>
        ';
    }

    public function setWinMessage(): void
    {
        $_SESSION[self::$message] = 'You win! You had ' . $this->game->getAttemptsLeft() . ' attempts left!';
    }

    public function setLoseMessage(): void
    {
        $_SESSION[self::$message] = 'Sorry, you lost. The correct word is ' . $this->game->getWord() . '.';
    }

    public function setOnlyOneLetterMessage(): void
    {
        $_SESSION[self::$message] = "Only one letter is allowed.";
    }

    public function setLetterAlreadyGuessedMessage(): void
    {
        $_SESSION[self::$message] = "Letter has already been guessed.";
    }

    public function unsetMessage(): void
    {
        $_SESSION[self::$message] = "";
    }

    public function playerIsTryingToGuessLetter(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST[self::$submit])) {
            return true;
        } else {
            return false;
        }
    }

    public function playerIsTryingToRestart(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST[self::$restart])) {
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

    private function showForm(): string
    {
        if ($this->game->isGameOver() || $this->game->isWin()) {
            return '
        <form  method="post" >
			<input type="submit" name="' . self::$restart . '" value="restart"/>
		</form>
        ';
        } else {
            return '
        <form method="post" > 
            <input type="text" id="' . self::$guess . '" name="' . self::$guess . '" value="" />
            <input type="submit" name="' . self::$submit . '" value="Guess" />
        </form>
        ';
        }
    }
}
