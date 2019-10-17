<?php

namespace view;


class RegisterView
{
    // Define HTML ID's
    private static $repeat = 'RegisterView::PasswordRepeat';
    private static $username = 'RegisterView::UserName';
    private static $password = 'RegisterView::Password';
    private static $message = 'RegisterView::Message';
    private static $register = 'RegisterView::Register';

    private $modelMessage;

    public function __construct(\model\Message $m)
    {
        $this->modelMessage = $m;
    }

    /**
     * The response on what to render
     */
    public function response(): string
    {
        $response = $this->generateRegisterFormHTML();

        return $response;
    }

    public function userTriesToRegister(): bool
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
        return new \model\RegisterInput($_POST[self::$username], $_POST[self::$password], $_POST[self::$repeat]);
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
					<p id="' . self::$message . '">' . $this->modelMessage->getMessage() . '</p>
					<label for="' . self::$username . '">Username :</label>
					<input type="text" size="20" name="' . self::$username . '" id="' . self::$username . '" value="' . $this->modelMessage->getFormUsername() . '">
					<br>
					<label for="' . self::$password . '">Password  :</label>
					<input type="password" size="20" name="' . self::$password . '" id="' . self::$password . '" value="">
					<br>
					<label for="' . self::$password . 'Repeat">Repeat password  :</label>
					<input type="password" size="20" name="' . self::$password . 'Repeat" id="' . self::$password . 'Repeat" value="">
					<br>
					<input id="submit" type="submit" name="' . self::$register . '" value="Register">
					<br>
				</fieldset>
            </form>
            ';
    }
}
