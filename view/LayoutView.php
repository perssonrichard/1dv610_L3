<?php

namespace view;

class LayoutView
{
  private static $registerQueryString = 'register';

  public function render($isLoggedIn, LoginView $loginView, RegisterView $registerView, DateTimeView $dtv, \hangman\Application $hangman)
  {
    echo '<!DOCTYPE html>
      <html lang="en">
        <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
          <title>Login Example</title>
        </head>
        <body>
        <div class="container">
          <h1>Assignment 3</h1>
          ' . $this->linkToRender($isLoggedIn, $loginView, $registerView) . '
          ' . $this->renderIsLoggedIn($isLoggedIn) . '
        </div>
          <div class="container">
              ' . $this->viewToRender($loginView, $registerView) . '
              ' . $dtv->show() . '
          </div>

              ' . $hangman->play() . '

         </body>
      </html>
    ';
  }

  /**
   * Decide what link to render
   */
  private function linkToRender($isLoggedIn, LoginView $loginView, RegisterView $registerView)
  {
    if (isset($_GET[self::$registerQueryString])) {
      return $registerView->generateBackToLoginHTML();
    } else if ($isLoggedIn == false) {
      return $loginView->generateRegisterUserHTML(self::$registerQueryString);
    }
  }

  /**
   * Decide what view to render
   */
  private function viewToRender(LoginView $loginView, RegisterView $registerView)
  {
    if (isset($_GET[self::$registerQueryString])) {
      return $registerView->response();
    } else {
      return $loginView->response();
    }
  }

  private function renderIsLoggedIn($isLoggedIn)
  {
    if ($isLoggedIn) {
      return '<h2>Logged in</h2>';
    } else {
      return '<h2>Not logged in</h2>';
    }
  }
}
