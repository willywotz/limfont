<?php include '_adminhead.php' ?>
<div class="mt-8 text-white text-2xl">จัดการข้อมูลหลังบ้าน</div>

<style type="text/tailwindcss">
#adminMenu a { @apply rounded p-4 text-center text-2xl }
#adminMenu a { @apply bg-white hover:bg-gray-300 }
</style>

<div class="mt-8 grid grid-cols-1 gap-4" id="adminMenu">
    <a href="admin.user.index.php">สมาชิก</a>
    <a href="admin.inventory.index.php">สต๊อกสินค้า</a>
    <a href="admin.account.index.php">บัญชีรายรับรายจ่าย</a>
    <a href="admin.purchase.index.php">คำสั่งซื้อ</a>
</div>

<?php include '_foot.php' ?>
