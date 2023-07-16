<?php

session_start();

function redirectTo($path) {
  header('Location: '.$path);
  echo '<script>location.href = \''.$path.'\';</script>';
  exit;
}

function user() {
  if (empty($_SESSION['user-identity'])) {
    return false;
  }

  $user = [];
  $user['displayName'] = $_SESSION['user-identity'];

  return (object) $user;
}

function login($identity) {
  $_SESSION['user-identity'] = $identity;

  return user();
}

function logout() {
  $_SESSION['user-identity'] = false;
  session_destroy();
}
