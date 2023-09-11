<?php
include '_config.php';

if (user() == false) {
    header('Location: user.login.php');
    exit;
}

?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>
        <style type="text/tailwindcss">
        #topmenu { @apply fixed inset-0 bg-[#333]; }
        #topmenu { @apply flex flex-col justify-center items-center gap-4; }
        #topmenu * { @apply rounded p-4 w-full max-w-[800px] text-center text-2xl; }
        #topmenu a { @apply bg-gray-50 hover:bg-gray-300; }
        </style>
    </head>

    <body class="bg-[#333]">
        <div class="max-w-[1000px] m-auto p-4">
            <div class="flex gap-4 text-4xl">
                <button onclick="document.getElementById('topmenu').classList.remove('!hidden')"
                    class="border px-8 bg-white rounded">☰</button>

                <a href="user.index.php" class="flex-grow bg-white rounded p-4">
                    <?= site()->title ?>
                </a>
            </div>
