<?php

require_once('view/LoginView.php');
require_once('view/RegisterView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('view/UrlView.php');

require_once('model/UserDB.php');
require_once('model/User.php');
require_once('model/RegisterInput.php');
require_once('model/UserCredentials.php');
require_once('model/ValidationString.php');
require_once('model/LoggedInState.php');
require_once('model/Exceptions.php');

require_once('controller/LoggedInController.php');
require_once('controller/LoginController.php');
require_once('controller/LogoutController.php');
require_once('controller/MasterController.php');
require_once('controller/RegisterController.php');

require_once('hangman/Application.php');


class Application
{
    // Models
    private $db;
    private $lis;

    // Views
    private $lv;
    private $rv;
    private $dtv;
    private $view;

    // Controller
    private $mc;

    // Hangman game
    private $hm;

    public function __construct()
    {
        $this->db = new \model\UserDB();
        $this->lis = new \model\LoggedInState();

        $this->lv = new \view\LoginView($this->lis, $this->db);
        $this->rv = new \view\RegisterView($this->db);
        $this->dtv = new \view\DateTimeView();
        $this->view = new \view\LayoutView();

        $this->mc = new \controller\MasterController($this->lis, $this->db, $this->lv, $this->rv);

        $this->hm = new \hangman\Application();
    }

    public function run()
    {
        $this->mc->run();
        $this->view->render($this->lis->getState(), $this->lv, $this->rv, $this->dtv, $this->hm);
    }
}
