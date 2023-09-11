<?php
include '_config.php';

if (user()) {
    header('Location: user.index.php');
    exit;
}

if (isPost()) {
    $stmt = db()->prepare('select 1 from user where identity = ?');
    $stmt->execute([$_POST['identity']]);
    if (!$stmt->fetch()) goto render;

    $_SESSION['identity'] = $_POST['identity'];
    header('Location: user.index.php');
    exit;
}

render:

?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen w-screen bg-[#333] flex flex-col items-center justify-center">
    <form action="user.login.php" method="post" class="p-4 max-w-[600px] w-full">
        <div>
            <img src="<?= site()->logoImage ?>"
                class="w-full h-full max-w-[256px] max-h-[256px] m-auto rounded-full">
        </div>

        <div class="mt-4 text-4xl font-bold text-center text-white">
            <span><?= site()->title ?></span>
        </div>

        <div class="mt-4 flex flex-col gap-2">
            <label for="identity" class="text-white">ชื่อผู้ใช้งาน</label>
            <input type="text" id="identity" name="identity" class="rounded p-4">
        </div>

        <div class="mt-4">
            <button class="bg-blue-500 text-white p-4 text-center w-full rounded">login</button>
        </div>
    </form>
</body>
</html>
