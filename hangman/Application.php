<?php

namespace hangman;

require_once("model/Game.php");
require_once("model/HangedMan.php");
require_once("model/Words.php");
require_once("model/GuessedLetter.php");
require_once("controller/Controller.php");
require_once("view/HangmanView.php");

class Application
{
    private $game;
    private $view;
    private $controller;

    public function __construct()
    {
        $this->game = new \hangmanModel\HangmanGame();
        $this->view = new \hangmanView\HangmanView($this->game);
        $this->controller = new \hangmanController\controller($this->view, $this->game);
    }

    public function play(): string
    {
        $this->controller->run();

        return $this->view->showGame();
    }
}
