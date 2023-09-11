<?php
include '_config.php';

session_destroy();
header('Location: user.login.php');
