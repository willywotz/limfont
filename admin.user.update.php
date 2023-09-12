<?php
include '_adminhead.php';

$stmt = db()->prepare('select * from user where identity = ?');
$stmt->execute([$_GET['identity']]);
$user = $stmt->fetch(PDO::FETCH_OBJ);

if (isPost()) {
    $_POST['image'] = $user->image;
    if ($_FILES['image']['size'] > 0) {
        $_POST['image'] = uploadRandomName($_FILES['image']['tmp_name']);
        if ($user->image != '256')
            unlink(UPLOADDIR.'/'.$user->image);
    }
    $stmt = db()->prepare('update user set identity = ?, canAdmin = ?, telephone = ?, email = ?, address = ?, image = ? where identity = ?');
    $result = $stmt->execute([$_POST['identity'], $_POST['canAdmin'], $_POST['telephone'], $_POST['email'], $_POST['address'], $_POST['image'], $_GET['identity']]);
    if (!$result) {
        if ($_POST['image'] != '256')
            unlink(UPLOADDIR.'/'.$_POST['image']);
        goto render;
    }
    header('Location: admin.user.index.php');
    exit;
}

render:

?>
<div class="mt-8 text-white text-2xl flex justify-between items-center">
    <span>เพิ่มสมาชิกใหม่</span>
    <div>
        <a href="admin.user.index.php" class="bg-yellow-500 hover:bg-yellow-700 text-white rounded px-4 py-2">ย้อนกลับ</a>
    </div>
</div>

<style type="text/tailwindcss">
form label { @apply text-white text-xl }
form input, form textarea, form button { @apply rounded p-4 bg-white w-full }
</style>

<form action="admin.user.update.php?identity=<?= $_GET['identity'] ?>" method="post" enctype="multipart/form-data"
    class="flex flex-col gap-8 my-8">

    <div class="flex flex-col gap-4">
        <label for="identity">identity</label>
        <input type="text" id="identity" name="identity" value="<?= $user->identity ?>">
    </div>

    <div class="flex flex-col gap-4">
        <label>can admin</label>

        <div class="grid grid-cols-2 gap-4 text-center">
            <input type="radio" id="canAdmin-no" name="canAdmin" value="0" <?= $user->canAdmin == 0 ? 'checked' : '' ?> class="peer/no hidden">
            <label for="canAdmin-no" class="peer-checked/no:bg-red-700 peer-checked/no:border-red-700 border rounded p-4 cursor-pointer">No</label>
            <input type="radio" id="canAdmin-yes" name="canAdmin" value="1" <?= $user->canAdmin == 1 ? 'checked' : '' ?> class="peer/yes hidden">
            <label for="canAdmin-yes" class="peer-checked/yes:bg-blue-700 peer-checked/yes:border-blue-700 border rounded p-4 cursor-pointer">Yes</label>
        </div>
    </div>

    <div class="flex flex-col gap-4">
        <label for="telephone">telephone</label>
        <input type="tel" id="telephone" name="telephone" value="<?= $user->telephone ?>">
    </div>

    <div class="flex flex-col gap-4">
        <label for="email">email</label>
        <input type="email" id="email" name="email" value="<?= $user->email ?>">
    </div>

    <div class="flex flex-col gap-4">
        <label for="address">address</label>
        <textarea rows="4" name="address"><?= $user->address ?></textarea>
    </div>

    <div class="flex flex-col gap-4">
        <label for="image" class="text-white">image</label>
        <img id="previewImage" class="h-[256px] w-[256px]" src="upload/<?= $user->image ?>">
        <input type="file" id="image" name="image" onchange="changePreviewImage(event)">
        <script>
        function changePreviewImage(event) {
            document.getElementById('previewImage').src
                = window.URL.createObjectURL(event.target.files[0]);
        }
        </script>
    </div>

    <div class="flex gap-4">
        <button class="bg-blue-500 hover:bg-blue-700 text-white">บันทึกข้อมูล</button>
    </div>
</form>
<?php include '_foot.php' ?>
