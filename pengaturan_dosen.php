<?php

if (isset($_POST['submit'])) {
  $nip = $_POST['nip'];
  $nama = $_POST['nama'];
  $jk = $_POST['jk'];

  $stmt = $pdo->prepare("INSERT INTO tbl_dosen (nip_dosen, nama_dosen, jk_dosen) VALUES(?, ?, ?)");
  $stmt->execute([$nip, $nama, $jk]);
}

if (isset($_GET['hapus'])) {
  $id_dosen = $_GET['hapus'];

  $stmt = $pdo->prepare("DELETE FROM tbl_dosen WHERE id_dosen = ?");
  $stmt->execute([$id_dosen]);
}

?>
<h1 class="text-center">Dosen</h1>
<div class="row">
  <div class="col-md-4"></div>
  <div class="col-md-4">
    <form class="" action="" method="post">
      <label for="warna">Nama Dosen</label>
      <input class="form-control" type="text" name="nama" value="">
      <label for="nama">NIP</label>
      <input class="form-control" type="text" name="nip" value="">
      <label for="kurikulum">Jenis Kelamin</label>
      <select class="form-control" name="jk">
        <option value="L">Laki-laki</option>
        <option value="P">Perempuan</option>
      </select>
      <br>
      <button class="btn btn-primary" type="submit" name="submit"><span class="glyphicon glyphicon-floppy-disk"></span>Simpan</button>
    </form>
    <br>
  </div>
  <div class="col-md-4"></div>
</div>
<table class="table">
  <th>No.</th><th>Nama</th><th>NIP</th><th>JK</th><th>Aksi</th>
  <?php
  $stmt = $pdo->query("SELECT * FROM tbl_dosen ORDER BY nama_dosen");
  $no = 1;
  while ($row = $stmt->fetch()) {
    echo "<tr>";
    echo "<td>".$no++."</td>";
    echo "<td>".$row['nama_dosen']."</td>";
    echo "<td>".$row['nip_dosen']."</td>";
    echo "<td>".$row['jk_dosen']."</td>";
    echo "<td><a href=\"index.php?page=pengaturan&sub=dosen&hapus=".$row['id_dosen']."\"><span class=\"glyphicon glyphicon-remove\"></span></a></td>";
    echo "</tr>";
  }
  ?>
</table>
