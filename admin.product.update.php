<?php
include '_adminhead.php';

set_time_limit(5);

$product = db()->query('select * from product where id = '.$_GET['id'])->fetch(PDO::FETCH_OBJ);

if (isPost()) {
    $oldImage = explode(' ', $product->image);
    $_POST['image'] = $oldImage;

    if ($_FILES['image']['tmp_name'][0] != '') {
        $_POST['image'] = [];
        foreach ($_FILES['image']['tmp_name'] as $item)
            $_POST['image'][] = uploadRandomName($item);
    }

    if (($_POST['image'] = implode(' ', $_POST['image'])) == '') {
        $_POST['image'] = '256';
    }

    $stmt = db()->prepare('update product set title = ?, detail = ?, price = ?, image = ?, serial = ? where id = ?');
    $result = $stmt->execute([$_POST['title'], $_POST['detail'], $_POST['price'], $_POST['image'], $_POST['serial'], $_GET['id']]);
    if (!$result) {
        if ($_POST['image'] != '256')
            foreach (explode(' ', $_POST['image']) as $item)
                unlink(UPLOADDIR.'/'.$item);
        goto render;
    }

    if ($_FILES['image']['tmp_name'][0] != '' && $oldImage[0] != '256')
        foreach ($oldImage as $item)
            unlink(UPLOADDIR.'/'.$item);

    header('Location: admin.product.index.php');
    exit;
}

render:

?>
<div class="mt-8 text-white text-2xl flex justify-between items-center">
    <span>แก้ไขสินค้า</span>

    <div>
        <a href="admin.product.index.php" class="bg-yellow-500 hover:bg-yellow-700 text-white rounded px-4 py-2">ย้อนกลับ</a>
    </div>
</div>

<style type="text/tailwindcss">
form label { @apply text-white text-xl }
form input, form textarea, form button { @apply rounded p-4 bg-white w-full }
</style>

<form action="admin.product.update.php?id=<?= $product->id ?>"
    method="post" enctype="multipart/form-data" class="flex flex-col gap-8 my-8">

    <div class="flex flex-col gap-4">
        <label for="serial">serial</label>
        <input type="text" id="serial" name="serial" value="<?= $product->serial ?>">
    </div>

    <div class="flex flex-col gap-4">
        <label for="title">title</label>
        <input type="text" id="title" name="title" value="<?= $product->title ?>">
    </div>

    <div class="flex flex-col gap-4">
        <label for="detail">detail</label>
        <textarea rows="4" name="detail"><?= $product->detail ?></textarea>
    </div>

    <div class="flex flex-col gap-4">
        <label for="price">price</label>
        <input type="number" id="price" name="price" value="<?= $product->price ?>">
    </div>

    <div class="flex flex-col gap-4">
        <label for="image" class="text-white">image</label>
        <div id="previewImage" class="grid grid-cols-4 gap-4">
            <?php foreach (explode(' ', $product->image) as $item): ?>
            <img src="upload/<?= $item ?>" class="h-[256px] w-[256px]">
            <?php endforeach ?>
        </div>
        <input type="file" id="image" name="image[]" multiple onchange="changePreviewImage(event)">
        <script>
        function changePreviewImage(event) {
            const el = document.getElementById('previewImage');
            while (el.firstChild) el.removeChild(el.firstChild, el);
            for (let f of event.target.files) {
                const i = document.createElement('img');
                i.setAttribute('class', 'h-[256px] w-[256px]');
                i.src = window.URL.createObjectURL(f);
                el.appendChild(i);
            }
        }
        </script>
    </div>

    <div class="flex gap-4">
        <button class="bg-blue-500 hover:bg-blue-700 text-white">บันทึกข้อมูล</button>
    </div>
</form>
<?php include '_foot.php' ?>
