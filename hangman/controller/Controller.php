<?php

namespace hangmanController;

use AlreadyGuessedLetterException;
use GuessIsNotOneLetterException;

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
            if ($this->view->playerIsTryingToGuessLetter()) {
                $this->game->doGuessLetter($this->view->getGuessedLetter());

                if ($this->game->guessIsCorrect()) {
                    $this->view->setCorrectGuessMessage();
                } else {
                    $this->view->setWrongGuessMessage();
                }
            }
        } catch (GuessIsNotOneLetterException $e) {

        } catch (AlreadyGuessedLetterException $e) {

        }
    }
}
