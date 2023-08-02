<?php

define('DBDSN', 'mysql:dbname=limfont');
define('DBUSER', 'root');
define('DBPASS', '');
define('UPLOADPATH', __DIR__ . '/upload');

class Application {
  public $page;
  public $user;
  public $isAdmin;

  private static $instance;
  private static $connection;

  function __construct() {
    date_default_timezone_set('Asia/Bangkok');
    session_start();

    $this->page = $_GET['p'] ?? 'home';

    $this->db(); // pre-check db (select 1)
    $this->autoLogin();
    $this->dispatch();
  }

  function dispatch() {
    function m($x) { return $_SERVER['REQUEST_METHOD'] == $x; }
    function p($x) { return preg_match('/'.$x.'/', $_GET['p'] ?? 'home') != false; }

    if (m('GET') && p('login') && !$this->user) { return; }
    if (m('POST') && p('login') && !$this->user) { return $this->loginPost(); }
    if ($this->isAdmin) {
      if (m('GET') && p('admin-products')) { return $this->adminAllProduct(); }
      if (m('GET') && p('admin-add-product')) { return; }
      if (m('POST') && p('admin-add-product')) { return $this->adminAddProductPost(); }
      if (m('GET') && p('admin-set-product')) { return $this->adminSetProduct(); }
      if (m('POST') && p('admin-set-product')) { return $this->adminSetProductPost(); }
      if (m('GET') && p('admin-del-product')) { return $this->adminDelProduct(); }
    }
    if (m('GET') && !p('home')) { return $this->redirector('home'); }
  }

  function autoLogin() {
    if (!isset($_SESSION['identity'])) { return false; }
    $user = $this->login($_SESSION['identity']);

    return $user != null;
  }

  function loginPost() {
    global $hasError;

    $user = $this->login($_POST['identity']);
    if (!$user) {
      $hasError = true;
      return false;
    }

    $_SESSION['identity'] = $user->identity;
    return $this->redirector('home');
  }

  function adminAllProduct() {
    global $products;
    $products = $this->getAllProduct();
  }

  function adminAddProductPost() {
    $image = $this->uploadFile($_FILES['image']['tmp_name']);
    $product = $this->addProduct($_POST['name'], $_POST['price'], $_POST['quantity'], $image);

    return $this->redirector('admin-products');
  }

  function adminSetProduct() {
    global $product;
    $product = $this->getProductById($_GET['id']);
  }

  function adminSetProductPost() {
    $image = $_POST['old_image'];
    if (!empty($_FILES['image']['tmp_name'])) {
      if ($image != '') unlink(UPLOADPATH.'/'.$image);
      $image = $this->uploadFile($_FILES['image']['tmp_name']);
    }

    $product = $this->setProduct($_POST['id'], $_POST['name'], $_POST['price'], $_POST['quantity'], $image);

    return $this->redirector('admin-products');
  }

  function adminDelProduct() {
    $this->delProduct($_GET['id']);
    return $this->redirector('admin-products');
  }

  function redirector($uri) {
    header("Location: index.php?p=$uri");
    echo "<script>location.href = 'index.php?=$uri'</script>";
    exit;
  }

  function randomString($length = 4) {
    $sample = 'abcdefghijklmnopqrstuvwxyz';
    for ($ret = ''; strlen($ret) < $length;)
      $ret .= $sample[strlen($sample)-1-rand(0,strlen($sample))];
    return $ret;
  }

  function uploadFile($tmpName) {
    do {
      $name = $this->randomString();
      $to = UPLOADPATH.'/'.$name;
    } while (is_file($to));
    $ret = move_uploaded_file($tmpName, $to);
    return $ret ? $name : false;
  }

  static function getInstance() {
    if (static::$instance == null) {
      static::$instance = new static;
    }
    return static::$instance;
  }

  static function getDatabase() {
    if (static::$connection == null) {
      static::$connection = new PDO(DBDSN, DBUSER, DBPASS);
      static::$connection->query('select 1');
    }
    return static::$connection;
  }

  function db() {
    return static::getDatabase();
  }

  function getUserByIdentity($identity) {
    $sql = "select * from users where identity = ? and deleted_at is null";
    $sth = $this->db()->prepare($sql);
    $sth->execute([$identity]);

    $user = $sth->fetchObject();
    return $user ? $user : null;
  }

  function getAllProduct() {
    $sql = "select * from products where deleted_at is null";
    $sth = $this->db()->prepare($sql);
    $sth->execute();

    return $sth->fetchAll(PDO::FETCH_OBJ);
  }

  function getProductById($id) {
    $sql = "select * from products where id = ? and deleted_at is null";
    $sth = $this->db()->prepare($sql);
    $sth->execute([$id]);

    $product = $sth->fetchObject();
    return $product ? $product : null;
  }

  function addProduct($name, $price, $quantity, $image) {
    $sql = "insert into products (name, price, quantity, image) values (?, ?, ?, ?)";
    $sth = $this->db()->prepare($sql);
    $sth->execute([$name, $price, $quantity, $image]);
    $id = $this->db()->lastInsertId();

    return $this->getProductById($id);
  }

  function setProduct($id, $name, $price, $quantity, $image) {
    $sql = "update products set name = ?, price = ?, quantity = ?, image = ? where id = ?";
    $sth = $this->db()->prepare($sql);
    $sth->execute([$name, $price, $quantity, $image, $id]);

    return $this->getProductById($id);
  }

  function delProduct($id) {
    $sql = "update products set deleted_at = ? where id = ?";
    $sth = $this->db()->prepare($sql);
    $sth->execute([date('Y-m-d H:i:s'), $id]);

    return $sth->rowCount() > 0;
  }

  function login($identity) {
    $user = $this->getUserByIdentity($identity);
    if (!$user) { return false; }
    $this->user = $user;
    $this->isAdmin = $user->is_admin == 1;

    return $user;
  }
}

function app() {
  return Application::getInstance();
}
app();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <style>
    * { box-sizing: border-box; }
    html, body { font: 16px/1.5 sans-serif; color: #333; }
    body { margin: 0; }
    img { max-width: 100%; height: auto; }
    .block { margin: 0 auto; max-width: 600px; }
    .mt-1 { margin-top: 1rem; }

    <?php if (app()->page != 'login'): ?>
    .navbar { margin: 0 auto; max-width: 600px; }
    .navbar-body { display: flex; }
    .navbar-body h1, .navbar-body span { padding: 1rem; }
    .navbar-body h1 { margin: 0; }
    .navbar-title { flex: 1; background-color: hsla(0deg 0% 0% / 25%); }
    .navbar-link { background-color: hsla(0deg 0% 0% / 75%); color: #fff; display: flex; align-items: center; padding: 0 1rem; text-decoration: none; }
    @media screen and (min-width: 800px) { .navbar-body { margin-top: 4rem; } }
    <?php endif; ?>

    <?php if (app()->page == 'login'): ?>
    .login-block { position: fixed; left: 0; right: 0; top: 0; bottom: 0; }
    .login-block { display: flex; justify-content: center; align-items: center; }
    .login-body { width: 100%; max-width: 330px; }
    .login-img img { max-width: 100%; height: auto; }
    .login-input input { font: inherit; width: 100%; }
    .login-error { padding-top: 1em; color: red; display: none; }
    .login-error.has-error { display: block; }
    .login-btn { padding-top: 1em; }
    .login-btn button { font: inherit; width: 100%; }
    <?php endif; ?>
    </style>
  </head>
  <body>
    <?php if (app()->page != 'login'): ?>
    <div class="navbar">
      <div class="navbar-body">
        <a href="index.php?p=home" class="navbar-title">
          <h1>KOREA.SHOP</h1>
        </a>
        <?php if (app()->user): ?>
        <a href="index.php?p=profile" class="navbar-link">
          <span class="material-symbols-outlined">account_circle</span>
        </a>
        <?php else: ?>
        <a href="index.php?p=login" class="navbar-link">
          <span class="material-symbols-outlined">login</span>
        </a>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php if (app()->page == 'login'): ?>
    <div class="login-block">
      <form action="index.php?p=login" method="post" class="login-body">
        <div class="login-img"><img src="https://placehold.co/400" alt=""></div>
        <div class="login-title"><a href="index.php?p=home"><h1>KOREA.SHOP</h1></a></div>
        <div class="login-input"><input type="text" name="identity" required="" placeholder="รหัสผู้ใช้งาน"></div>
        <div class="login-error<?php if (isset($hasError) && $hasError): ?> has-error<?php endif; ?>"><span>ข้อมูลไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง</span></div>
        <div class="login-btn"><button>เข้าใช้งาน</button></div>
      </form>
    </div>
    <?php endif; ?>

    <?php if (app()->page == 'admin-products'): ?>
    <table class="block mt-1" style="width: 100%;">
      <tr>
        <th>#</th>
        <th>name</th>
        <th>price</th>
        <th>quantity</th>
        <th></th>
        <th></th>
      </tr>

      <?php foreach ($products as $product): ?>
      <tr>
        <td><?=$product->id ?></td>
        <td><?=$product->name ?></td>
        <td><?=$product->price ?></td>
        <td><?=$product->quantity ?></td>
        <td><a href="index.php?p=admin-set-product&id=<?=$product->id ?>">edit</a></td>
        <td><a href="index.php?p=admin-del-product&id=<?=$product->id ?>">delete</a></td>
      </tr>
      <?php endforeach; ?>

    </table>
    <?php endif; ?>

    <?php if (app()->page == 'admin-add-product'
      || app()->page == 'admin-set-product'): ?>
    <form action="index.php?p=<?=$_GET['p'] ?>" method="post" enctype="multipart/form-data" class="block mt-1">
      <?php if (app()->page == 'admin-set-product'): ?>
      <input type="hidden" name="id" value="<?=$_GET['id'] ?>">
      <input type="hidden" name="old_image" value="<?=$product->image ?? '' ?>">
      <?php endif;?>
      <input type="text" name="name" value="<?=$product->name ?? '' ?>">
      <input type="text" name="price" value="<?=$product->price ?? '' ?>">
      <input type="text" name="quantity" value="<?=$product->quantity ?? '' ?>">
      <?php if (isset($product->image) && $product->image != ''): ?>
      <img src="upload/<?=$product->image ?>" alt="">
      <?php endif; ?>
      <input type="file" name="image">
      <button>submit</button>
    </form>
    <?php endif; ?>

  </body>
</html>
