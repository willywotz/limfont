<?php
include '_adminhead.php';

$stmt = db()->prepare('delete from user where identity = ?');
$stmt->execute([$_GET['identity']]);
header('Location: admin.user.index.php');
