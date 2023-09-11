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

function checkUploadDirectory() {
    if (file_exists(UPLOADDIR)) { return true; }
    return mkdir(UPLOADDIR, 0777, true);
}

checkUploadDirectory();

function randomString($length = 4) {
    $sample = 'abcdefghijklmnopqrstuvwxyz';
    for ($ret = ''; strlen($ret) < $length;)
        $ret .= $sample[strlen($sample)-1-rand(0,strlen($sample))];
    return $ret;
}

function uploadRandomName($tempName) {
    do { $a = randomString(); $b = UPLOADDIR.'/'.$a; } while (is_file($b));
    return move_uploaded_file($tempName, $b) ? $a : uploadRandomName($tempName);
}
