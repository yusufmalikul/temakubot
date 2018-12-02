<?php
require_once 'config.php';
session_start();

if (isset($_SESSION['login'])) {
  header('location:index.php?page=beranda');
}

if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $password = md5($_POST['password']);

  $stmt = $pdo->prepare("SELECT * FROM tbl_admin WHERE username_admin = ? AND password_admin = ?");
  $stmt->execute([$username, $password]);
  if ($row = $stmt->fetch()) {
    $_SESSION['login'] = 1;
    $_SESSION['id_admin'] = $row['id_admin'];
    header('location: index.php');
  } else {
    $error = "<script>alert(\"Nama pengguna atau kata sandi salah!\")</script>";
  }

}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chatbot Admin Login</title>
    <link rel="shortcut icon" href="./assets/img/icon.png">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <script src="./assets/js/jquery-3.3.1.min.js" charset="utf-8"></script>
    <script src="./assets/js/bootstrap.min.js" charset="utf-8"></script>
  </head>
  <body class="login-body">
    <?php
    if (isset($error)) {
      echo $error;
    }
    ?>
    <div class="container">
      <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4 login-form">
          <h2 class="login-h2"><i class="glyphicon glyphicon-lock"></i> Temaku Admin Login</h2>
          <hr class="login-hr">
          <p>Silakan masuk terlebih dahulu.</p>
          <form class="" action="" method="post">
            <label for="username">Nama Pengguna:</label>
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
              <input class="form-control" type="text" name="username" value="">
            </div>
            <label for="password">Kata Sandi:</label>
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
              <input class="form-control" type="password" name="password" value="">
            </div><br>
              <button class="btn btn-primary col-md-12" type="submit" name="submit">
                MASUK</button>
          </form>
        </div>
        <div class="col-md-4"></div>
      </div>
    </div>
  </body>
  </html>
