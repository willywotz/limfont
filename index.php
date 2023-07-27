<?php

class Database {
  static $instance;

  public $dbh;

  function __construct() {
    $dsn = 'mysql:dbname=limfont;host=127.0.0.1';
    $user = 'root';
    $password = '';

    $this->dbh = new PDO($dsn, $user, $password);
    $this->dbh->query('select 1');
  }

  static function getInstance() {
    if (!static::$instance) {
      static::$instance = new static;
    }

    return static::$instance;
  }

  static function __callStatic($name, $arguments) {
    $callback = [static::getInstance()->dbh, $name];
    return call_user_func_array($callback, $arguments);
  }
}

class_alias('Database', 'db'); db::getInstance();

class User {
  public $id;
  public $identity;

  function __construct($input = []) {
    $this->id = $input['id'] ?? 0;
    $this->identity = $input['identity'] ?? '';
  }

  static function create($input = []) {
    return new static($input);
  }

  static function login($identity) {
    $sth = db::prepare('select * from user where identity = ?');
    $sth->execute([$identity]);

    $result = $sth->fetch(PDO::FETCH_ASSOC);
    if ($result == false) { return null; }

    return new static($result);
  }
}

class App {
  static $instance;

  public $page;
  public $isPost;
  public $user;

  function __construct() {
    session_start();

    $this->page = $_GET['p'] ?? 'index';
    $this->isPost = $_SERVER['REQUEST_METHOD'] == 'POST';
    $this->user = $this->user();

    if (!empty($_SESSION['identity'])) {
      $this->login($_SESSION['identity']);
    }
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

  function login($identity) {
    $user = User::login($identity);
    $_SESSION['identity'] = $identity;

    return $this->user($user);
  }

  static function getInstance() {
    if (!static::$instance) {
      static::$instance = new static;
    }

    return static::$instance;
  }
}

function app() { return App::getInstance(); } app();

// login check
if (app()->page != 'login' && !app()->user) {
  app()->redirectTo('login');
} elseif (app()->page == 'login' && app()->user != false) {
  app()->redirectTo('home');
} elseif (app()->page == 'login' && app()->isPost) {
  $user = app()->login($_POST['identity'] ?? '');
  echo json_encode($user);
  exit;
} elseif (app()->page != 'home') {
  app()->redirectTo('home');
}

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
      <form class="login-body" id="login-form">
        <div class="login-img"><img src="https://placehold.co/400" alt=""></div>
        <div class="login-title"><h1>KOREA.SHOP</h1></div>
        <div class="login-input"><input type="text" name="identity" required="" placeholder="รหัสผู้ใช้งาน"></div>
        <div class="login-error" id="login-error"><span>ข้อมูลไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง</span></div>
        <div class="login-btn"><button>เข้าใช้งาน</button></div>
      </form>
    </div>
    <?php endif; ?>

    <script>
    <?php if (app()->page == 'login'): ?>
    const loginForm = document.getElementById('login-form');
    loginForm.onsubmit = async function (el) {
      el.preventDefault();

      const body = new FormData(el.target);
      const response = await fetch('?p=login', { method: 'POST', body });
      const login = await response.json();

      if (login != false) {
        location.href = '?p=index';
        return;
      }

      document.getElementById('login-error').classList.add('has-error');
    };
    <?php endif; ?>
    </script>
  </body>
</html>
