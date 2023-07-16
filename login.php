<?php

include 'ubn.php';

if (user()) {
  redirectTo('index.php');
}

$error_message = false;
if (isset($_POST['user-identity'])) {
  $identity = $_POST['user-identity'];

  if (login($identity)) {
    redirectTo('index.php');
  }

  $error_message = 'รหัสลูกค้าไม่ถูกต้อง';
}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KOREA.SHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
      html, body { height: 100%; }
      .form-signin { max-width: 330px; padding: 1rem; }
      .form-signin .form-floating:focus-within { z-index: 2; }
    </style>
  </head>
  <body class="d-flex align-items-center py-4 bg-body-tertiary">
    <main class="form-signin w-100 m-auto">
      <form action="?" method="POST">
        <div class="text-center">
          <img class="mb-4 rounded-circle" src="https://placehold.co/200" alt="">
          <h1 class="h1 mb-3">KOREA.SHOP</h1>
        </div>

        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="floatingInput" placeholder="รหัสลูกค้า" name="user-identity">
          <label for="floatingInput">รหัสลูกค้า</label>
          <div class="invalid-feedback<?php if ($error_message): ?> d-block<?php endif; ?>"><?=$error_message ?></div>
        </div>

        <button class="btn btn-primary w-100 py-2" type="submit">เข้าใช้งาน</button>
      </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  </body>
</html>
