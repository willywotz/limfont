<?php
include '_adminhead.php';

if (isPost()) {
    $stmt = db()->prepare('insert into account (detail, amount, type) values (?, ?, ?)');
    $result = $stmt->execute([$_POST['detail'], $_POST['amount'], $_POST['type']]);
    if (!$result) goto render;
    header('Location: admin.account.index.php');
    exit;
}

render:

?>
<div class="mt-8 text-white text-2xl flex justify-between items-center">
    <span>เพิ่มรายการใหม่</span>

    <div class="flex gap-4">
        <a href="admin.index.php" class="bg-yellow-500 hover:bg-yellow-700 text-white rounded px-4 py-2">ย้อนกลับ</a>
    </div>
</div>

<style type="text/tailwindcss">
form label { @apply text-white text-xl }
form input, form textarea, form button { @apply rounded p-4 bg-white w-full }
</style>

<form action="admin.account.create.php" method="post" class="flex flex-col gap-8 my-8">

    <div class="flex flex-col gap-4">
        <label>ประเภท</label>

        <div class="grid grid-cols-2 gap-4 text-center">
            <input type="radio" id="type-1" name="type" value="1" checked class="peer/type-1 hidden">
            <label for="type-1" class="peer-checked/type-1:bg-blue-700 peer-checked/type-1:border-blue-700 border rounded p-4 cursor-pointer">รายรับ</label>
            <input type="radio" id="type-2" name="type" value="2" class="peer/type-2 hidden">
            <label for="type-2" class="peer-checked/type-2:bg-red-700 peer-checked/type-2:border-red-700 border rounded p-4 cursor-pointer">รายจ่าย</label>
        </div>
    </div>

    <div class="flex flex-col gap-4">
        <label for="detail">รายการ</label>
        <input type="text" id="detail" name="detail">
    </div>

    <div class="flex flex-col gap-4">
        <label for="amount">จำนวน</label>
        <input type="number" id="amount" name="amount">
    </div>

    <div class="flex gap-4">
        <button class="bg-blue-500 hover:bg-blue-700 text-white">บันทึกข้อมูล</button>
    </div>
</form>
<?php include '_foot.php' ?>
