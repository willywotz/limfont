<?php
include '_adminhead.php';

$stmt = db()->prepare('delete from product where id = ?');
$stmt->execute([$_GET['id']]);
header('Location: admin.product.index.php');
