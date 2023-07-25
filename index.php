<?php

class App {
  static $memory;

  public $page;
  public $user;

  function __construct() {
    session_start();

    $this->page = $_GET['p'] ?? 'index';
    $this->user = $this->user();
  }

  function redirectTo($page) {
    header("Location: index.php?p=$page");
    echo "<script>window.location.href = 'index.php?p=$page';</script>";
    exit;
  }

  function user($newuser = null) {
    if ($newuser != null) { $this->user = $newuser; }
    if ($this->user != null) { return $this->user; }

    return false;
  }

  static function memory() {
    if (!static::$memory) {
      static::$memory = new static;
    }

    return static::$memory;
  }
}

function app() {
  return App::memory();
}
app();

// login check
if (app()->page != 'login' && !app()->user) {
  app()->redirectTo('login');
} elseif (app()->page == 'login' && app()->user != false) {
  app()->redirectTo('index');
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style></style>
  </head>
  <body>
    <script></script>
  </body>
</html>
