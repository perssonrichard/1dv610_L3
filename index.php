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

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userDB = new \model\UserDB();
$loggedInState = new \model\LoggedInState();

$loginView = new \view\LoginView($loggedInState, $userDB);
$registerView = new \view\RegisterView($userDB);
$dateTimeView = new \view\DateTimeView();
$layoutView = new \view\LayoutView();

$masterController = new \controller\MasterController($loggedInState, $userDB, $loginView, $registerView);

$masterController->run();

$layoutView->render($loggedInState->getState(), $loginView, $registerView, $dateTimeView);
