<?php
$id_admin = $_SESSION['id_admin'];

if (isset($_POST['submit'])) {
  $id = $_POST['id'];
  $nama = $_POST['nama'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $password_old = $_POST['password_old'];

  if (empty($password)) {
    $stmt = $pdo->prepare("UPDATE tbl_admin SET username_admin = ?, nama_admin = ? WHERE id_admin = ?");
    $stmt->execute([$username, $nama, $id]);
    echo "<script>alert(\"Perubahan disimpan!\")</script>";
    // header('location:index.php?page=pengaturan');
  } else {
    $stmt = $pdo->prepare("SELECT * FROM tbl_admin WHERE username_admin = ? AND password_admin = ?");
    $stmt->execute([$username, md5($password_old)]);
    if ($row = $stmt->fetch()) {
      $stmt = $pdo->prepare("UPDATE tbl_admin SET username_admin = ?, nama_admin = ?, password_admin = ? WHERE id_admin = ?");
      $stmt->execute([$username, $nama, md5($password), $id]);
      echo "<script>alert(\"Perubahan disimpan!\")</script>";
    } else {
      echo "<script>alert(\"Kata sandi lama salah!\")</script>";
    }
  }
}

$stmt = $pdo->query("SELECT * FROM tbl_admin WHERE id_admin = $id_admin");
$row = $stmt->fetch();

?>
<h1 class="text-center">Data Admin</h1>
<div class="col-md-4"></div>
<div class="col-md-4">
  <p>Untuk mengganti kata sandi mohon masukkan kata sandi baru dan kata sandi lama.</p>
  <form class="" action="" method="post">
    <input type="hidden" name="id" value="<?= isset($row)?$row['id_admin']:'';?>">
    <div class="form-group">
      <label for="username">Nama:</label>
      <input class="form-control" type="text" name="nama" value="<?= isset($row)?$row['nama_admin']:'';?>">
    </div>
    <div class="form-group">
      <label for="username">Nama pengguna:</label>
      <input class="form-control" type="text" name="username" value="<?= isset($row)?$row['username_admin']:'';?>">
    </div>
    <div class="form-group">
      <label for="password">Kata sandi baru:</label>
      <input class="form-control" type="password" name="password" value="">
    </div>
    <div class="form-group">
      <label for="password_old">Kata sandi lama:</label>
      <input class="form-control" type="password" name="password_old" value="">
    </div>
    <button class="btn btn-primary" type="submit" name="submit">Simpan</button>
  </form>
</div>
<div class="col-md-4"></div>
