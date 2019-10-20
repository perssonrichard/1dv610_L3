<?php

namespace view;


class LayoutView
{
  public function render($isLoggedIn, LoginView $lv, RegisterView $rv, DateTimeView $dtv, \hangman\Application $hangman)
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
        <div class="container border-left border-right p-2">
          <h1>The Hangman Game</h1>
          ' . $this->linkToRender($isLoggedIn, $lv, $rv) . '
          ' . $this->renderIsLoggedIn($isLoggedIn) . '
        </div>
          <div class="container border-left border-right border-bottom p-2  ">
              ' . $this->viewToRender($lv, $rv) . '
              ' . $this->renderHangman($isLoggedIn, $hangman) . '
              <div class="container fixed-bottom border">
              ' . $dtv->show() . '
              </div>
          </div>
         </body>
      </html>
    ';
  }

  /**
   * Decide what link to render
   */
  private function linkToRender($isLoggedIn, LoginView $loginView, RegisterView $registerView)
  {
    if (isset($_GET[UrlView::$registerQueryString])) {
      return $registerView->generateBackToLoginHTML();
    } else if ($isLoggedIn == false) {
      return $loginView->generateRegisterUserHTML(UrlView::$registerQueryString);
    }
  }

  /**
   * Decide what view to render
   */
  private function viewToRender(LoginView $loginView, RegisterView $registerView)
  {
    if (isset($_GET[UrlView::$registerQueryString])) {
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

  private function renderHangman($isLoggedIn, \hangman\Application $hangman)
  {
    if ($isLoggedIn) {
      return $hangman->play();
    }
  }
}
