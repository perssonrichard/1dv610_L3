<?php

namespace view;

class LoginView
{
	// Define HTML ID's
	private static $_cookieName = 'LoginView::CookieName';
	private static $_cookiePassword = 'LoginView::CookiePassword';
	private static $_username = 'LoginView::UserName';
	private static $_password = 'LoginView::Password';
	private static $_keep = 'LoginView::KeepMeLoggedIn';
	private static $_login = 'LoginView::Login';
	private static $_logout = 'LoginView::Logout';
	private static $_message = 'LoginView::Message';

	private $modelMessage;
	private $userDB;

	private $message;

	public function __construct(\model\Message $m, \model\UserDB $db)
	{
		$this->modelMessage = $m;
		$this->userDB = $db;
	}

	/**
	 * The response given on what to render
	 */
	public function response()
	{
		$this->setWelcomeMessage();

		if ($_SESSION["loggedIn"]) {
			return $this->generateLogoutButtonHTML();
		} else {
			return $this->generateLoginFormHTML();
		}
	}

	private function setWelcomeMessage()
	{
		if (isset($_SESSION['showWelcome']) && $_SESSION['showWelcome']) {
			$this->message = "Welcome";
		} else if (isset($_SESSION['showWelcomeKeep']) && $_SESSION['showWelcomeKeep']) {
			$this->message = "Welcome and you will be remembered";
		} else if (isset($_SESSION['loggedInWithCookie']) && $_SESSION['loggedInWithCookie']) {
			$this->message = "Welcome back with cookie";
		}

		// Prevent message from showing twice
		$_SESSION['showWelcome'] = false;
		$_SESSION['showWelcomeKeep'] = false;
		$_SESSION['loggedInWithCookie'] = false;
	}

	public function setEmptyUsernameMessage(): void
	{
		$this->message = "Username is missing";
	}

	public function setEmptyPasswordMessage(): void
	{
		$this->message = "Password is missing";
	}

	public function userTriesToLogIn(): bool
	{
		if ($this->userClicksLoginButton()) {
			return true;
		} else {
			return false;
		}
	}

	private function userClicksLoginButton(): bool
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST[self::$_login])) {
			return true;
		} else {
			return false;
		}
	}

	public function setWelcomeSession(\model\UserCredentials $userCredentials): void
	{
		// If keep me logged in is checked
		if ($userCredentials->getKeepLoggedIn()) {
			$user = $this->userDB->getUser($userCredentials);

			$this->setCookies($user);

			$_SESSION['showWelcomeKeep'] = true;
		} else {
			$_SESSION['showWelcome'] = true;
		}
	}

	public function setLoggedInWithCookieSession(): void
	{
		$_SESSION['loggedInWithCookie'] = true;
	}

	public function userWantsToLogOut(): bool
	{
		if ($this->userClicksLogoutButton()) {
			return true;
		} else {
			return false;
		}
	}

	private function userClicksLogoutButton(): bool
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['LoginView::Logout'])) {
			return true;
		} else {
			return false;
		}
	}

	public function getUserCredentials(): \model\UserCredentials
	{
		return new \model\UserCredentials($_POST[self::$_username], $_POST[self::$_password], isset($_POST[self::$_keep]));
	}

	public function getCookieUser(): \model\UserCredentials
	{
		return new \model\UserCredentials($_COOKIE[self::$_cookieName], $_COOKIE[self::$_cookiePassword], true);
	}

	public function getValidationString(): \model\ValidationString
	{
		return new \model\ValidationString($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
	}

	public function hasCookieUser(): bool
	{
		if (isset($_COOKIE[self::$_cookieName]) && isset($_COOKIE[self::$_cookiePassword])) {
			return true;
		} else {
			return false;
		}
	}

	public function setCookies(\model\User $user): void
	{
		// 86400 * 30 = 24 hours
		setcookie(self::$_cookieName, $user->getUsername(), time() + (86400 * 30));
		setcookie(self::$_cookiePassword, $user->getCookiePassword(), time() + (86400 * 30));
	}

	public function deleteCookies(): void
	{
		setcookie(self::$_cookieName, "", time() - 3600);
		setcookie(self::$_cookiePassword, "", time() - 3600);
	}

	public function generateRegisterUserHTML($queryString)
	{
		return '<a href="?' . $queryString . '" name="register">Register a new user</a>';
	}

	private function generateLogoutButtonHTML()
	{
		return '
			<form  method="post" >
				<p id="' . self::$_message . '">' . $this->message . '</p>
				<input type="submit" name="' . self::$_logout . '" value="logout"/>
			</form>
		';
	}

	private function generateLoginFormHTML()
	{
		return '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$_message . '">' . $this->message . '</p>
					
					<label for="' . self::$_username . '">Username :</label>
					<input type="text" id="' . self::$_username . '" name="' . self::$_username . '" value="' . $this->modelMessage->getFormUsername() . '" />

					<label for="' . self::$_password . '">Password :</label>
					<input type="password" id="' . self::$_password . '" name="' . self::$_password . '" />

					<label for="' . self::$_keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$_keep . '" name="' . self::$_keep . '" />
					
					<input type="submit" name="' . self::$_login . '" value="login" />
				</fieldset>
			</form>
		';
	}
}
