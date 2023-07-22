<?php

session_start();

function db() {
  static $conn;

  if (!$conn) {
    $conn = new PDO('mysql:dbname=limfont;', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }

  return $conn;
}

function redirectTo($path) {
  header('Location: '.$path);
  echo '<script>location.href = \''.$path.'\';</script>';
  exit;
}

function user($newuser = null) {
  static $user;

  if ($newuser) { $user = $newuser; }
  if ($user) { return (object) $user; }

  if (!empty($_SESSION['user-identity'])) {
    return login($_SESSION['user-identity']);
  }

  return false;
}

function login($identity) {
  $sth = db()->prepare('select * from user where username = :username');
  $sth->execute(['username' => $identity]);

  if ($sth->rowCount() == 0) { return false; }

  $_SESSION['user-identity'] = $identity;

  return user($sth->fetch(PDO::FETCH_ASSOC));
}

function logout() {
  $_SESSION['user-identity'] = null;
  session_destroy();
}

function loginCheck() {
  if (!user()) redirectTo('login.php');
}
