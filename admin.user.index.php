<?php include '_adminhead.php' ?>
<div class="mt-8 text-white text-2xl flex justify-between items-center">
    <span>จัดการข้อมูลสมาชิก</span>
    <a href="admin.user.create.php" class="bg-blue-500 hover:bg-blue-700 text-white rounded px-4 py-2">เพิ่มสมาชิกใหม่</a>
</div>

<div class="mt-8 grid grid-cols-3 gap-4">
    <?php $users = db()->query('select * from user')->fetchAll(PDO::FETCH_OBJ) ?>
    <?php foreach ($users as $user): ?>
    <div class="rounded overflow-hidden bg-white">
        <div class="py-8 text-2xl border-b text-center"><?= $user->identity ?></div>
        <div class="py-2 px-4 border-b">สินค้าที่เคยสั่ง: 7 ชิ้น</div>
        <div class="py-2 px-4">สินค้าที่กำลังพรีอยู่: 0 ชิ้น</div>
        <div class="grid grid-cols-2 text-white text-center">
            <a href="admin.user.update.php?identity=<?= $user->identity ?>" class="bg-yellow-500 hover:bg-yellow-700 py-2">แก้ไข</a>
            <a href="admin.user.delete.php?identity=<?= $user->identity ?>" class="bg-red-500 hover:bg-red-700 py-2">ลบ</a>
        </div>
    </div>
    <?php endforeach ?>
</div>
<?php include '_foot.php' ?>
