<?php

if (isset($_POST['submit'])) {
  $nama_kelas = $_POST['nama_kelas'];
  // $kurikulum = $_POST['kurikulum'];
  $warna = $_POST['warna'];

  $stmt = $pdo->prepare("INSERT INTO tbl_kelas (nama_kelas, warna_kelas) VALUES(?, ?)");
  $stmt->execute([$nama_kelas, $warna]);
}

if (isset($_GET['hapus'])) {
  $id_kelas = $_GET['hapus'];

  $stmt = $pdo->prepare("DELETE FROM tbl_kelas WHERE id_kelas = ?");
  $stmt->execute([$id_kelas]);
}

?>
<h1 class="text-center">Kelas</h1>
<div class="row">
  <div class="col-md-4"></div>
  <div class="col-md-4">
    <form class="" action="" method="post">
      <label for="nama">Kelas</label>
      <input class="form-control" type="text" name="nama_kelas" value="">
      <!-- <label for="kurikulum">Kurikulum</label>
      <select class="form-control" name="kurikulum">
        <option value="2017">2017</option>
        <option value="2016">2016</option>
        <option value="2015">2015</option>
        <option value="2014">2014</option>
      </select> -->
      <label for="warna">Warna</label>
      <input class="form-control" type="text" name="warna" value="">
      <br>
      <button class="btn btn-primary" type="submit" name="submit"><span class="glyphicon glyphicon-floppy-disk"></span>Simpan</button>
    </form>
    <br>
  </div>
  <div class="col-md-4"></div>
</div>
<table class="table">
  <th>No.</th><th>Nama Kelas</th><th>Warna</th><th>Aksi</th>
  <?php
  $stmt = $pdo->query("SELECT * FROM tbl_kelas ORDER BY nama_kelas");
  $no = 1;
  while ($row = $stmt->fetch()) {
    echo "<tr>";
    echo "<td>".$no++."</td>";
    echo "<td>".$row['nama_kelas']."</td>";
    echo "<td>".$row['warna_kelas']."</td>";
    // echo "<td>".$row['kurikulum']."</td>";
    echo "<td><a href=\"index.php?page=pengaturan&sub=kelas&hapus=".$row['id_kelas']."\"><span class=\"glyphicon glyphicon-remove\"></span></a></td>";
    echo "</tr>";
  }
  ?>
</table>
