<?php

define('DBDSN', 'mysql:dbname=limfont');
define('DBUSER', 'root');
define('DBPASS', '');

class Application {
  public $page;
  public $user;

  private static $instance;
  private static $connection;

  function __construct() {
    $this->page = $_GET['p'] ?? 'home';

    $this->db(); // pre-check db (select 1)
    $this->dispatch();
  }

  function dispatch() {
    function m($x) { return $_SERVER['REQUEST_METHOD'] == $x; }
    function p($x) { return preg_match('/'.$x.'/', $_GET['p'] ?? 'home') != false; }

    // if (false) {}
    // elseif (p('home')) { $this->redirector('home'); }
  }

  function redirector($uri) {
    header("Location: index.php?p=$uri");
    echo "<script>location.href = 'index.php?=$uri'</script>";
    exit;
  }

  static function getInstance() {
    if (static::$instance == null) {
      static::$instance = new static;
    }
    return static::$instance;
  }

  static function getDatabase() {
    if (static::$connection == null) {
      static::$connection = new PDO(DBDSN, DBUSER, DBPASS);
      static::$connection->query('select 1');
    }
    return static::$connection;
  }

  function db() {
    return static::getDatabase();
  }
}

function app() {
  return Application::getInstance();
}
app();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
    * { box-sizing: border-box; }
    html, body { font: 16px/1.5 sans-serif; color: #333; }
    body { margin: 0; }

    <?php if (app()->page == 'login'): ?>
    .login-block { position: fixed; left: 0; right: 0; top: 0; bottom: 0; }
    .login-block { display: flex; justify-content: center; align-items: center; }
    .login-body { width: 100%; max-width: 330px; }
    .login-img img { max-width: 100%; height: auto; }
    .login-input input { font: inherit; width: 100%; }
    .login-error { padding-top: 1em; color: red; display: none; }
    .login-error.has-error { display: block; }
    .login-btn { padding-top: 1em; }
    .login-btn button { font: inherit; width: 100%; }
    <?php endif; ?>
    </style>
  </head>
  <body>
    <?php if (app()->page == 'login'): ?>
    <div class="login-block">
      <form class="login-body" action="index.php?p=login" method="POST">
        <div class="login-img"><img src="https://placehold.co/400" alt=""></div>
        <div class="login-title"><a href="index.php?p=home"><h1>KOREA.SHOP</h1></a></div>
        <div class="login-input"><input type="text" name="identity" required="" placeholder="รหัสผู้ใช้งาน"></div>
        <div class="login-error"><span>ข้อมูลไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง</span></div>
        <div class="login-btn"><button>เข้าใช้งาน</button></div>
      </form>
    </div>
    <?php endif; ?>

  </body>
</html>
