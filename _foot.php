        </div>

        <div id="topmenu" class="!hidden">
            <a href="user.index.php">หน้าบ้าน</a>
            <a href="user.setting.php">ข้อมูลผู้ใช้งาน</a>
            <?php if (user()->canAdmin): ?>
            <a href="admin.index.php">จัดการข้อมูลหลังบ้าน</a>
            <?php endif ?>
            <a href="user.logout.php">ออกจากระบบ</a>

            <button onclick="document.getElementById('topmenu').classList.add('!hidden')"
                class="text-white bg-blue-500 hover:bg-blue-700">ปิดเมนู</button>
        </div>

    </body>
</html>
