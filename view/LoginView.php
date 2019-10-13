<?php

namespace view;

use Config;

class LoginView
{
	private $message;

	public function __construct(\model\Message $m)
	{
		$this->message = $m;
	}

	/**
	 * The response given on what to render
	 */
	public function response()
	{
		if ($_SESSION["loggedin"]) {
			return $this->generateLogoutButtonHTML();
		} else {
			return $this->generateLoginFormHTML();
		}
	}

	public function userWantsToLogIn(): bool
	{
		if ($this->userClicksLoginButton()) {
			return true;
		} else {
			return false;
		}
	}

	private function userClicksLoginButton(): bool
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['LoginView::Login'])) {
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
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['LoginView::Logout']) && $_SESSION["loggedin"] == true) {
			return true;
		} else {
			return false;
		}
	}

	public function getLoginUser(): \model\User
	{
		$user = new \model\User($_POST[Config::$loginName], $_POST[Config::$loginPassword]);

		// If keep logged in is checked
		$user->setKeepLoggedIn(isset($_POST[Config::$loginKeep]));

		return $user;
	}

	public function getCookieUser(): \model\User
	{
		return new \model\User($_COOKIE[Config::$loginCookieName], $_COOKIE[Config::$loginCookiePassword]);
	}

	public function hasCookieUser(): bool
	{
		if (isset($_COOKIE[Config::$loginCookieName]) && isset($_COOKIE[Config::$loginCookiePassword])) {
			return true;
		} else {
			return false;
		}
	}

	public function generateRegisterUserHTML($queryString)
	{
		return '<a href="?' . $queryString . '" name="register">Register a new user</a>';
	}

	private function generateLogoutButtonHTML()
	{
		return '
			<form  method="post" >
				<p id="' . Config::$loginMessage . '">' . $this->message->getMessage() . '</p>
				<input type="submit" name="' . Config::$loginLogout . '" value="logout"/>
			</form>
		';
	}

	private function generateLoginFormHTML()
	{
		return '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . Config::$loginMessage . '">' . $this->message->getMessage() . '</p>
					
					<label for="' . Config::$loginName . '">Username :</label>
					<input type="text" id="' . Config::$loginName . '" name="' . Config::$loginName . '" value="' . $this->message->getFormUsername() . '" />

					<label for="' . Config::$loginPassword . '">Password :</label>
					<input type="password" id="' . Config::$loginPassword . '" name="' . Config::$loginPassword . '" />

					<label for="' . Config::$loginKeep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . Config::$loginKeep . '" name="' . Config::$loginKeep . '" />
					
					<input type="submit" name="' . Config::$loginLogin . '" value="login" />
				</fieldset>
			</form>
		';
	}

	public function setCookies(\model\User $user): void
	{
		$hashedPassword = password_hash($user->getPassword(), PASSWORD_BCRYPT);

		// 86400 * 30 = 24 hours
		setcookie(Config::$loginCookieName, $user->getUsername(), time() + (86400 * 30));
		setcookie(Config::$loginCookiePassword, $hashedPassword, time() + (86400 * 30));
	}

	public function deleteCookies(): void
	{
		setcookie(Config::$loginCookieName, "", time() - 3600);
		setcookie(Config::$loginCookiePassword, "", time() - 3600);
	}

	public function validateCookies(\model\User $user): bool
	{
		// TODO: VALIDATE HERE OR IN CONTROLLER?
		if ($_COOKIE[Config::$loginCookiePassword] == $_SESSION['oldCookiePassword']) {
			return true;
		} else {
			return false;
		}
	}
}
