<?php

namespace hangmanController;

class controller
{
    private $view;
    private $game;

    public function __construct(\hangmanView\HangmanView $view, \hangmanModel\HangmanGame $game)
    {
        $this->view = $view;
        $this->game = $game;
    }

    public function run()
    {
        try {
            $this->guessLetter();
        } catch (\hangmanModel\GuessIsNotOneLetterException $e) {
            $this->view->setOnlyOneLetterMessage();
        } catch (\hangmanModel\AlreadyGuessedLetterException $e) {
            $this->view->setLetterAlreadyGuessedMessage();
        }

        if ($this->view->playerIsTryingToRestart()) {
            $this->game->doRestartGame();
            $this->view->unsetMessage();
        }
    }

    private function guessLetter(): void
    {
        if ($this->view->playerIsTryingToGuessLetter()) {
            $this->game->doGuessLetter($this->view->getGuessedLetter());

            if ($this->game->isGuessCorrect()) {
                if ($this->game->isWin()) {
                    $this->view->setWinMessage();
                }
            } else if ($this->game->isGameOver()) {
                $this->view->setLoseMessage();
            }
        }
    }
}
