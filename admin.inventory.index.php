<?php include '_adminhead.php' ?>
<div class="mt-8 text-white text-2xl flex justify-between items-center">
    <span>จัดการสต๊อกสินค้า</span>

    <div>
        <a href="admin.product.create.php" class="bg-blue-500 hover:bg-blue-700 text-white rounded px-4 py-2">เพิ่มสินค้าใหม่</a>
        <a href="admin.index.php" class="bg-yellow-500 hover:bg-yellow-700 text-white rounded px-4 py-2">ย้อนกลับ</a>
    </div>
</div>

<div class="mt-8 grid grid-cols-3 gap-4">
    <?php $products = db()->query('select * from product')->fetchAll(PDO::FETCH_OBJ) ?>
    <?php foreach ($products as $product): ?>
    <div class="rounded overflow-hidden bg-white">
        <div class="py-8 text-2xl border-b text-center"><?= $product->title ?></div>
        <div class="grid grid-cols-2 text-white text-center">
            <a href="admin.product.update.php?id=<?= $product->id ?>" class="bg-yellow-500 hover:bg-yellow-700 py-2">แก้ไข</a>
            <a href="admin.product.delete.php?id=<?= $product->id ?>" class="bg-red-500 hover:bg-red-700 py-2">ลบ</a>
        </div>
    </div>
    <?php endforeach ?>
</div>
<?php include '_foot.php' ?>
