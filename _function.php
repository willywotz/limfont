<?php

function db() {
    static $conn;
    if (!$conn) $conn = new PDO(DBHOST, DBUSER, DBPASS);
    return $conn;
}

function isPost() {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function user() {
    if (empty($_SESSION['identity'])) {
        return false;
    }
    $stmt = db()->prepare('select * from user where identity = ?');
    $stmt->execute([$_SESSION['identity']]);
    return $stmt->fetch(PDO::FETCH_OBJ);
}

function site() {
    return db()->query('select * from site')->fetch(PDO::FETCH_OBJ);
}
