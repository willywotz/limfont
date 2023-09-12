<?php include '_adminhead.php' ?>
<div class="mt-8 text-white text-2xl flex justify-between items-center">
    <span>บัญชีรายรับรายจ่าย</span>

    <div class="flex gap-4">
        <a href="admin.account.create.php" class="bg-blue-500 hover:bg-blue-700 text-white rounded px-4 py-2">เพิ่มรายการใหม่</a>
        <a href="admin.index.php" class="bg-yellow-500 hover:bg-yellow-700 text-white rounded px-4 py-2">ย้อนกลับ</a>
    </div>
</div>

<div class="mt-8 grid grid-cols-3 gap-4">
    <?php $sql = 'select sum(amount) as x from account where' ?>
    <?php $revenue = db()->query($sql.' type = 1')->fetch()['x'] ?>
    <?php $expense = db()->query($sql.' type = 2')->fetch()['x'] ?>
    <div class="flex flex-col bg-white rounded p-4">
        <span>รายรับ</span>
        <span class="text-2xl text-right">
            <?= number_format($revenue, 2) ?>
        </span>
    </div>

    <div class="flex flex-col bg-white rounded p-4">
        <span>รายจ่าย</span>
        <span class="text-2xl text-right">
            <?= number_format($expense, 2) ?>
        </span>
    </div>

    <div class="flex flex-col bg-white rounded p-4">
        <span>คงเหลือ</span>
        <span class="text-2xl text-right">
            <?= number_format($revenue - $expense, 2) ?>
        </span>
    </div>
</div>

<div></div>
<?php include '_foot.php' ?>
