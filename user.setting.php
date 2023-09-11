<?php
include '_head.php';

if (isPost()) {
    $_POST['image'] = user()->image;
    if ($_FILES['image']['size'] > 0) {
        $_POST['image'] = uploadRandomName($_FILES['image']['tmp_name']);
        unlink(UPLOADDIR.'/'.user()->image);
    }
    $stmt = db()->prepare('update user set telephone = ?, email = ?,
        address = ?, image = ? where identity = ?');
    $stmt->execute([$_POST['telephone'], $_POST['email'], $_POST['address'],
        $_POST['image'], user()->identity]);
    header('Location: user.setting.php');
    exit;
}

?>
<div class="mt-8 text-white text-2xl">จัดการข้อมูลผู้ใช้งาน</div>

<style type="text/tailwindcss">
form label { @apply text-white text-xl }
form input, form textarea, form button { @apply rounded p-4 bg-white w-full }
</style>

<form action="user.setting.php" method="post" enctype="multipart/form-data"
    class="flex flex-col gap-8 my-8">

    <div class="flex flex-col gap-4">
        <label for="identity">identity</label>
        <input type="text" id="identity" name="identity" value="<?= user()->identity ?>" readonly>
    </div>

    <div class="flex flex-col gap-4">
        <label for="telephone">telephone</label>
        <input type="tel" id="telephone" name="telephone" value="<?= user()->telephone ?>">
    </div>

    <div class="flex flex-col gap-4">
        <label for="email">email</label>
        <input type="email" id="email" name="email" value="<?= user()->email ?>">
    </div>

    <div class="flex flex-col gap-4">
        <label for="address">address</label>
        <textarea rows="4" name="address"><?= user()->address ?></textarea>
    </div>

    <div class="flex flex-col gap-4">
        <label for="image" class="text-white">image</label>
        <img id="previewImage" src="<?= 'upload/'.user()->image ?>" class="h-[256px] w-[256px]">
        <input type="file" id="image" name="image" onchange="changePreviewImage(event)">
        <script>
        function changePreviewImage(event) {
            document.getElementById('previewImage').src
                = window.URL.createObjectURL(event.target.files[0]);
        }
        </script>
    </div>

    <div class="flex gap-4">
        <button type="reset" onclick="location.reload()"
            class="bg-yellow-500 hover:bg-yellow-700 text-white">ยกเลิก</button>
        <button class="bg-blue-500 hover:bg-blue-700 text-white">ส่งข้อมูล</button>
    </div>
</form>
<?php include '_foot.php' ?>
