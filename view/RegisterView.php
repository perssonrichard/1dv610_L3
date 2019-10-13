<?php

namespace view;

use Config;

class RegisterView
{
    private $message;

    public function __construct(\model\Message $m)
    {
        $this->message = $m;
    }

    /**
     * The response on what to render
     */
    public function response(): string
    {
        $response = $this->generateRegisterFormHTML();

        return $response;
    }

    public function userWantsToRegister(): bool
    {
        if ($this->userClicksRegisterButton()) {
            return true;
        } else {
            return false;
        }
    }

    private function userClicksRegisterButton(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['RegisterView::Register'])) {
            return true;
        } else {
            return false;
        }
    }

    public function getRegisterInput(): \model\RegisterInput
    {
        return new \model\RegisterInput($_POST[Config::$registerName], $_POST[Config::$registerPassword], $_POST[Config::$registerRepeatPassword]);
    }

    public function generateBackToLoginHTML()
    {
        return '<a href="?">Back to login</a>';
    }

    private function generateRegisterFormHTML()
    {
        return '
        <h2>Register new user</h2>
        <form action="?register" method="post" enctype="multipart/form-data">
				<fieldset>
				<legend>Register a new user - Write username and password</legend>
					<p id="' . Config::$registerMessage . '">' . $this->message->getMessage() . '</p>
					<label for="' . Config::$registerName . '">Username :</label>
					<input type="text" size="20" name="' . Config::$registerName . '" id="' . Config::$registerName . '" value="' . $this->message->getFormUsername() . '">
					<br>
					<label for="' . Config::$registerPassword . '">Password  :</label>
					<input type="password" size="20" name="' . Config::$registerPassword . '" id="' . Config::$registerPassword . '" value="">
					<br>
					<label for="' . Config::$registerPassword . 'Repeat">Repeat password  :</label>
					<input type="password" size="20" name="' . Config::$registerPassword . 'Repeat" id="' . Config::$registerPassword . 'Repeat" value="">
					<br>
					<input id="submit" type="submit" name="' . Config::$registerRegistration . '" value="Register">
					<br>
				</fieldset>
            </form>
            ';
    }
}
