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
        <div class="d-flex justify-content-center">
        <div class="mt-3 w-50">
            <div class="border p-1">
                <div class="bg-dark p-0">
                    <pre class="text-white p-2">' . $this->game->getHangedMan() . '</pre>
                </div>
            
                <p class="text-center">' . $this->showWord() . '</p>
                <p class="text-center">' . $_SESSION[self::$message] . '</p>


                <p class="text-center">Guessed letters</p> 
                <p class="text-center">' . $this->showGuessedLetters() . '</p>
                ' . $this->showForm() . '
            </div>
        </div>
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
        <form method="post" >
			<input type="submit" class="btn btn-outline-dark mt-2" name="' . self::$restart . '" value="Restart"/>
		</form>
        ';
        } else {
            return '
        <form class="text-center" method="post" > 
            <input type="text" class="form-control" id="' . self::$guess . '" name="' . self::$guess . '" value="" />
            <input type="submit" class="btn btn-outline-dark mt-2" name="' . self::$submit . '" value="Guess" />
        </form>
        ';
        }
    }
}
