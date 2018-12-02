<?php

if (isset($_POST['submit'])) {
  $kode = $_POST['kode'];
  $matakuliah = $_POST['matakuliah'];

  $stmt = $pdo->prepare("INSERT INTO tbl_matakuliah (kode_matakuliah, nama_matakuliah) VALUES(?, ?)");
  $stmt->execute([$kode, $matakuliah]);
}

if (isset($_GET['hapus'])) {
  $kode = $_GET['hapus'];

  $stmt = $pdo->prepare("DELETE FROM tbl_matakuliah WHERE kode_matakuliah = ?");
  $stmt->execute([$kode]);
}

?>
<h1 class="text-center">Mata Kuliah</h1>
<div class="row">
  <div class="col-md-4"></div>
  <div class="col-md-4">
    <form class="" action="" method="post">
      <label for="kode">Kode Mata Kuliah</label>
      <input class="form-control" type="text" name="kode" value="">
      <label for="matakuliah">Mata Kuliah</label>
      <input class="form-control" type="text" name="matakuliah" value="">
      <br>
      <button class="btn btn-primary" type="submit" name="submit"><span class="glyphicon glyphicon-floppy-disk"></span>Simpan</button>
    </form>
    <br>
  </div>
  <div class="col-md-4"></div>
</div>
<table class="table">
  <th>No.</th><th>Kode</th><th>Mata Kuliah</th><th>Aksi</th>
  <?php
  $stmt = $pdo->query("SELECT * FROM tbl_matakuliah ORDER BY kode_matakuliah");
  $no = 1;
  while ($row = $stmt->fetch()) {
    echo "<tr>";
    echo "<td>".$no++."</td>";
    echo "<td>".$row['kode_matakuliah']."</td>";
    echo "<td>".$row['nama_matakuliah']."</td>";
    echo "<td><a href=\"index.php?page=pengaturan&sub=matakuliah&hapus=".$row['kode_matakuliah']."\"><span class=\"glyphicon glyphicon-remove\"></span></a></td>";
    echo "</tr>";
  }
  ?>
</table>
