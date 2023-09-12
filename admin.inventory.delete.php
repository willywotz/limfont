<?php
include '_adminhead.php';

$stmt = db()->prepare('delete from inventory where id = ?');
$stmt->execute([$_GET['id']]);
header('Location: admin.inventory.index.php');
