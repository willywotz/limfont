<?php
include '_config.php';

if (user()) {
    header('Location: user.index.php');
    exit;
}

if (isPost()) {
    $_POST['image'] = '256';
    if ($_FILES['image']['size'] > 0) {
        $_POST['image'] = uploadRandomName($_FILES['image']['tmp_name']);
    }
    $stmt = db()->prepare('insert into user (identity, telephone, email, address, image) values (?, ?, ?, ?, ?)');
    $result = $stmt->execute([$_POST['identity'], $_POST['telephone'], $_POST['email'],
        $_POST['address'], $_POST['image']]);
    if (!$result) {
        if ($_POST['image'] != '256')
            unlink(UPLOADDIR.'/'.$_POST['image']);
        goto render;
    }
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
<style type="text/tailwindcss">
form label { @apply text-white text-xl }
form input, form textarea, form button { @apply rounded p-4 bg-white w-full }
</style>
</head>
<body class="bg-[#333] flex flex-col items-center justify-center">
    <form action="user.register.php" method="post" class="p-4 max-w-[600px] w-full">
        <div>
            <img src="<?= site()->logoImage ?>"
                class="w-full h-full max-w-[256px] max-h-[256px] m-auto rounded-full">
        </div>

        <div class="mt-4 text-4xl font-bold text-center text-white">
            <span><?= site()->title ?></span>
        </div>

        <div class="mt-4 flex flex-col gap-4">
            <label for="identity">identity</label>
            <input type="text" id="identity" name="identity">
        </div>

        <div class="mt-4 flex flex-col gap-4">
            <label for="telephone">telephone</label>
            <input type="tel" id="telephone" name="telephone">
        </div>

        <div class="mt-4 flex flex-col gap-4">
            <label for="email">email</label>
            <input type="email" id="email" name="email">
        </div>

        <div class="mt-4 flex flex-col gap-4">
            <label for="address">address</label>
            <textarea rows="4" name="address"></textarea>
        </div>

        <div class="mt-4 flex flex-col gap-4">
            <label for="image" class="text-white">image</label>
            <img id="previewImage" class="h-[256px] w-[256px]">
            <input type="file" id="image" name="image" onchange="changePreviewImage(event)">
            <script>
            function changePreviewImage(event) {
                document.getElementById('previewImage').src
                    = window.URL.createObjectURL(event.target.files[0]);
            }
            </script>
        </div>

        <div class="mt-4 flex flex-col gap-4 text-center">
            <button class="bg-blue-500 hover:bg-blue-700 text-white rounded p-4">register</button>
            <a href="user.login.php" class="bg-gray-500 hover:bg-gray-700 text-white rounded p-4">login</a>
        </div>
    </form>
</body>
</html>
