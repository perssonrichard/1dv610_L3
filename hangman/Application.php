<?php

namespace hangman;


require_once("model/Game.php");
require_once("model/HangedMan.php");
require_once("model/Words.php");
require_once("view/HangmanView.php");

class Application
{
    private $game;
    private $view;

    public function __construct()
    {
        $this->game = new \hangmanModel\HangmanGame();
        $this->view = new \hangmanView\HangmanView($this->game);
    }

    public function play(): string
    {
        return $this->view->showGame();
    }
}
