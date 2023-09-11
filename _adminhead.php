<?php
include '_head.php';

if (!user()->canAdmin) {
    header('Location: user.index.php');
    exit;
}
