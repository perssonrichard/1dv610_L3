<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('view/LoginView.php');
require_once('view/RegisterView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

require_once('model/UserDB.php');
require_once('model/User.php');
require_once('model/Message.php');

require_once('controller/AuthController.php');
require_once('controller/MessageController.php');

require_once('config/config.php');
require_once('config/serverConfig.php');

$userDB = new \model\UserDB();
$message = new \model\Message();

$loginView = new \view\LoginView($message);
$registerView = new \view\RegisterView($message);
$dtv = new \view\DateTimeView();
$view = new \view\LayoutView();

$messageController = new \controller\MessageController($message, $loginView, $userDB);
$authController = new \controller\AuthController($userDB, $loginView, $registerView, $messageController);


session_start();

if (isset($_SESSION["loggedin"]) == false) {
    $_SESSION["loggedin"] = false;
}

$authController->run();

// Render content
$view->render($_SESSION['loggedin'], $loginView, $registerView, $dtv);
