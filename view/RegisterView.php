<?php

namespace view;


class RegisterView
{
    // Define HTML ID's
    private static $_repeat = 'RegisterView::PasswordRepeat';
    private static $_username = 'RegisterView::UserName';
    private static $_password = 'RegisterView::Password';
    private static $_message = 'RegisterView::Message';
    private static $_register = 'RegisterView::Register';

    private $userDB;

    private $message;

    public function __construct(\model\UserDB $db)
    {
        $this->userDB = $db;
    }

    /**
     * The response on what to render
     */
    public function response(): string
    {
        $response = $this->generateRegisterFormHTML();

        return $response;
    }

    /**
     * TODO: These rules should not be dependant on magic numbers and
     * should exist in the model, possibly caught as an exception.
     */
    public function setWrongInputMessage(): void
    {
        $input = $this->getRegisterInput();
        $username = $input->getUsername();
        $password = $input->getPassword();
        $repeatPassword = $input->getRepeatPassword();

        if (empty($username) || strlen($username) < 3) {
            $this->message .= "Username has too few characters, at least 3 characters.<br>";
        }
        if ($username != strip_tags($username)) {
            $this->message .= "Username contains invalid characters.<br>";
        }
        if (empty($password) || strlen($password) < 6) {
            $this->message .= "Password has too few characters, at least 6 characters.<br>";
        }
        if ($this->userDB->hasUser($username)) {
            $this->message .= "User exists, pick another username.<br>";
        }
        if ($password != $repeatPassword) {
            $this->message .= "Passwords do not match.<br>";
        }
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
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST[self::$_register])) {
            return true;
        } else {
            return false;
        }
    }

    public function getRegisterInput(): \model\RegisterInput
    {
        return new \model\RegisterInput($_POST[self::$_username], $_POST[self::$_password], $_POST[self::$_repeat]);
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
					<p id="' . self::$_message . '">' . $this->message . '</p>
					<label for="' . self::$_username . '">Username :</label>
					<input type="text" size="20" name="' . self::$_username . '" id="' . self::$_username . '" value="' . $this->getFormUsernameInput() . '">
					<br>
					<label for="' . self::$_password . '">Password  :</label>
					<input type="password" size="20" name="' . self::$_password . '" id="' . self::$_password . '" value="">
					<br>
					<label for="' . self::$_password . 'Repeat">Repeat password  :</label>
					<input type="password" size="20" name="' . self::$_password . 'Repeat" id="' . self::$_password . 'Repeat" value="">
					<br>
					<input id="submit" type="submit" name="' . self::$_register . '" value="Register">
					<br>
				</fieldset>
            </form>
            ';
    }

    private function getFormUsernameInput(): string
	{
		if (isset($_POST[self::$_username])) {
			return strip_tags($_POST[self::$_username]);
		} else {
			return "";
		}
	}
}
