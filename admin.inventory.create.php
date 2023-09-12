<?php
include '_adminhead.php';

if (isPost()) {
    $_POST['image'] = $product->image;
    if ($_FILES['image']['size'] > 0) {
        $_POST['image'] = uploadRandomName($_FILES['image']['tmp_name']);
    }
    $stmt = db()->prepare('insert into product (title, detail, price, image) values (?, ?, ?, ?)');
    $result = $stmt->execute([$_POST['title'], $_POST['detail'], $_POST['price'], $_POST['image']]);
    if (!$result) {
        if ($_POST['image'] != '256')
            unlink(UPLOADDIR.'/'.$_POST['image']);
        goto render;
    }
    header('Location: admin.inventory.index.php');
    unlink(UPLOADDIR.'/'.$product->image);
    exit;
}

render:

?>
<div class="mt-8 text-white text-2xl flex justify-between items-center">
    <span>เพิ่มสินค้าใหม่</span>

    <div>
        <a href="admin.inventory.index.php" class="bg-yellow-500 hover:bg-yellow-700 text-white rounded px-4 py-2">ย้อนกลับ</a>
    </div>
</div>

<style type="text/tailwindcss">
form label { @apply text-white text-xl }
form input, form textarea, form button { @apply rounded p-4 bg-white w-full }
</style>

<form action="admin.inventory.create.php" method="post" enctype="multipart/form-data"
    class="flex flex-col gap-8 my-8">

    <div class="flex flex-col gap-4">
        <label for="title">title</label>
        <input type="text" id="title" name="title">
    </div>

    <div class="flex flex-col gap-4">
        <label for="detail">detail</label>
        <textarea rows="4" name="detail"></textarea>
    </div>

    <div class="flex flex-col gap-4">
        <label for="price">price</label>
        <input type="number" id="price" name="price">
    </div>

    <div class="flex flex-col gap-4">
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

    <div class="flex gap-4">
        <button class="bg-blue-500 hover:bg-blue-700 text-white">บันทึกข้อมูล</button>
    </div>
</form>
<?php include '_foot.php' ?>
