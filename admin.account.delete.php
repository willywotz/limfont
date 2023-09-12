<?php
include '_adminhead.php';

$stmt = db()->prepare('delete from account where id = ?');
$stmt->execute([$_GET['id']]);
header('Location: admin.account.index.php');
