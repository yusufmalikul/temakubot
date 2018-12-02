<?php
require_once 'config.php';
if (isset($_POST['submit'])) {
  $hari             = $_POST['hari'];
  $jam_ke           = $_POST['jam_ke'];
  $id_kelas         = $_POST['id_kelas'];
  $kode_matakuliah  = $_POST['kode_matakuliah'];
  $id_dosen         = $_POST['id_dosen'];
  $id_ruang         = $_POST['id_ruang'];
  $sks              = $_POST['sks'];
  $id_jadwal        = $_POST['id_jadwal'];
  $action           = $_POST['action'];

  if ($action == "simpan") {
    $stmt = $pdo->prepare("INSERT INTO tbl_jadwal (id_ruang, kode_matakuliah, id_dosen, id_kelas, hari, jam_ke, sks) VALUES (?,?,?,?,?,?,?);");
    if ($stmt->execute([$id_ruang, $kode_matakuliah, $id_dosen, $id_kelas, $hari, $jam_ke, $sks])) {
      echo "Data jadwal berhasil tersimpan";
    } else {
      $error = $pdo->errorInfo();
      echo $error[0]." ".$error[1]." ".$error[2];
    }
  } else if ($action == "ubah") {
    $stmt = $pdo->prepare("UPDATE tbl_jadwal SET id_ruang = ?, kode_matakuliah = ?, id_dosen = ?, id_kelas = ?, hari = ?, jam_ke = ?, sks = ? WHERE id_jadwal = ?");
    if ($stmt->execute([$id_ruang, $kode_matakuliah, $id_dosen, $id_kelas, $hari, $jam_ke, $sks, $id_jadwal])) {
      echo "Data jadwal berhasil diubah";
    } else {
      $error = $pdo->errorInfo();
      echo $error[0]." ".$error[1]." ".$error[2];
    }
  }
}

?>
