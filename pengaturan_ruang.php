<?php

if (isset($_POST['submit'])) {
  $nama_ruang = $_POST['nama_ruang'];

  $stmt = $pdo->prepare("INSERT INTO tbl_ruang (nama_ruang) VALUES(?)");
  $stmt->execute([$nama_ruang]);
}

if (isset($_GET['hapus'])) {
  $id_ruang = $_GET['hapus'];

  $stmt = $pdo->prepare("DELETE FROM tbl_ruang WHERE id_ruang = ?");
  $stmt->execute([$id_ruang]);
}

?>
<h1 class="text-center">Ruang Kuliah</h1>
<div class="row">
  <div class="col-md-4"></div>
  <div class="col-md-4">
    <form class="" action="" method="post">
      <label for="kode">Nama Ruang</label>
      <input class="form-control" type="text" name="nama_ruang" value="">
      <br>
      <button class="btn btn-primary" type="submit" name="submit"><span class="glyphicon glyphicon-floppy-disk"></span>Simpan</button>
    </form>
    <br>
  </div>
  <div class="col-md-4"></div>
</div>
<table class="table">
  <th>No.</th><th>Nama Ruang</th><th>Aksi</th>
  <?php
  $stmt = $pdo->query("SELECT * FROM tbl_ruang ORDER BY nama_ruang");
  $no = 1;
  while ($row = $stmt->fetch()) {
    echo "<tr>";
    echo "<td>".$no++."</td>";
    echo "<td>".$row['nama_ruang']."</td>";
    echo "<td><a href=\"index.php?page=pengaturan&sub=ruang&hapus=".$row['id_ruang']."\"><span class=\"glyphicon glyphicon-remove\"></span></a></td>";
    echo "</tr>";
  }
  ?>
</table>
