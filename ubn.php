<?php

session_start();

function redirectTo($path) {
  header('Location: '.$path);
  echo '<script>location.href = \''.$path.'\';</script>';
  exit;
}

function login($identity) {
  return true;
}
