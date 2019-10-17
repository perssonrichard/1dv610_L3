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
	
	// Session vars
	private static $showWelcome = "showWelcome";
	private static $showWelcomeKeep = "showWelcomeKeep";
	private static $loggedInWithCookie = "loggedInWithCookie";
	private static $showBye = "showBye";
	private static $newUser = "newUser";
	private static $newUsersName = "newUsersName";

	private $isLoggedIn;
	private $userDB;

	private $message;

	public function __construct(\model\LoggedInState $lis, \model\UserDB $db)
	{
		$this->userDB = $db;
		$this->isLoggedIn = $lis->getState();
	}

	/**
	 * The response given on what to render
	 */
	public function response()
	{
		$this->setSessionMessage();

		if ($this->isLoggedIn) {
			return $this->generateLogoutButtonHTML();
		} else {
			return $this->generateLoginFormHTML();
		}
	}

	private function setSessionMessage()
	{
		if (isset($_SESSION[self::$showWelcome]) && $_SESSION[self::$showWelcome]) {
			$this->message = "Welcome";
		} else if (isset($_SESSION[self::$showWelcomeKeep]) && $_SESSION[self::$showWelcomeKeep]) {
			$this->message = "Welcome and you will be remembered";
		} else if (isset($_SESSION[self::$loggedInWithCookie]) && $_SESSION[self::$loggedInWithCookie]) {
			$this->message = "Welcome back with cookie";
		} else if (isset($_SESSION[self::$showBye]) && $_SESSION[self::$showBye]) {
			$this->message = "Bye bye!";
		} else if (isset($_SESSION[self::$newUser]) && $_SESSION[self::$newUser]) {
			$this->message = "Registered new user.";
		}

		// Prevent message from showing twice
		$_SESSION[self::$showWelcome] = false;
		$_SESSION[self::$showWelcomeKeep] = false;
		$_SESSION[self::$loggedInWithCookie] = false;
		$_SESSION[self::$showBye] = false;
		$_SESSION[self::$newUser] = false;
	}

	public function setEmptyUsernameMessage(): void
	{
		$this->message = "Username is missing";
	}

	public function setEmptyPasswordMessage(): void
	{
		$this->message = "Password is missing";
	}

	public function setWrongUsernameOrPasswordMessage(): void
	{
		$this->message = "Wrong name or password";
	}

	public function setManipulatedCookiesMessage(): void
	{
		$this->message = "Wrong information in cookies";
	}

	public function userTriesToLogIn(): bool
	{
		if ($this->userClicksLoginButton()) {
			return true;
		} else {
			return false;
		}
	}

	public function setWelcomeSession(): void
	{
		$userCredentials = $this->getUserCredentials();

		// If keep me logged in is checked
		if ($userCredentials->getKeepLoggedIn()) {
			$user = $this->userDB->getUser($userCredentials);

			$this->setCookies($user);

			$_SESSION[self::$showWelcomeKeep] = true;
		} else {
			$_SESSION[self::$showWelcome] = true;
		}
	}

	public function setNewUserSession(): void
	{
		$_SESSION[self::$newUser] = true;
	}

	public function setNewUsersNameSession(string $name): void
	{
		$_SESSION[self::$newUsersName] = $name;
	}


	public function setLoggedInWithCookieSession(): void
	{
		$_SESSION[self::$loggedInWithCookie] = true;
	}

	public function setLogoutSession(): void
	{
		$_SESSION[self::$showBye] = true;
	}

	private function userClicksLoginButton(): bool
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST[self::$_login])) {
			return true;
		} else {
			return false;
		}
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
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST[self::$_logout])) {
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
					<input type="text" id="' . self::$_username . '" name="' . self::$_username . '" value="' . $this->getFormUsernameInput() . '" />

					<label for="' . self::$_password . '">Password :</label>
					<input type="password" id="' . self::$_password . '" name="' . self::$_password . '" />

					<label for="' . self::$_keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$_keep . '" name="' . self::$_keep . '" />
					
					<input type="submit" name="' . self::$_login . '" value="login" />
				</fieldset>
			</form>
		';
	}

	private function getFormUsernameInput(): string
	{
		if (isset($_POST[self::$_username])) {
			return $_POST[self::$_username];
		} else if (isset($_SESSION[self::$newUsersName])) {
			return $_SESSION[self::$newUsersName];
		} else {
			return "";
		}
	}
}
