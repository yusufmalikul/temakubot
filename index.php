<?php
require_once 'header.php';
if (isset($_GET['page'])) {
  $page = $_GET['page'];
  switch ($page) {
    case 'jadwal':
      include 'jadwal.php';
      break;
    case 'pengaturan':
    // include 'pengaturan_admin.php';
      $sub = $_GET['sub'];
      switch ($sub) {
        case 'kelas':
          include 'pengaturan_kelas.php';
          break;
        case 'matakuliah':
          include 'pengaturan_matakuliah.php';
          break;
        case 'ruang':
          include 'pengaturan_ruang.php';
          break;
        case 'dosen':
          include 'pengaturan_dosen.php';
          break;
        case 'admin':
          include 'pengaturan_admin.php';
          break;
      }
      break;
    case 'logout':
      include 'logout.php';
      break;
    default:
      include 'beranda.php';
      break;
  }
} else {
  include 'beranda.php';
}

?>
