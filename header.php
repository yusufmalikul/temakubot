<?php
require_once 'config.php';
session_start();
if (!isset($_SESSION['login'])) {
  header('location:login.php');
} else {
  $id_admin = $_SESSION['id_admin'];
  $stmt = $pdo->query("SELECT * FROM tbl_admin WHERE id_admin = $id_admin");
  $nama_admin = $stmt->fetch()['nama_admin'];
  $page = $_GET['page'];
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Temaku Admin</title>
    <link rel="shortcut icon" href="./assets/img/icon.png">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <script src="./assets/js/jquery-3.3.1.min.js" charset="utf-8"></script>
    <script src="./assets/js/bootstrap.min.js" charset="utf-8"></script>
  </head>
  <body class="admin-body">
    <nav class="navbar navbar-inverse">
      <div class="container">
        <div class="navbar-header">
          <a href="#" class="navbar-brand">Temaku admin</a>
        </div>
        <ul class="nav navbar-nav">
          <li <?= $page == "beranda"?"class=\"active\"":'';?>><a href="?page=beranda">Beranda</a></li>
          <li <?= $page == "jadwal"?"class=\"active\"":'';?>><a href="?page=jadwal">Jadwal</a></li>
          <!-- <li <?= $page == "pengaturan"?"class=\"active\"":'';?>><a href="?page=pengaturan">Pengaturan</a></li> -->
          <li <?= $page == "pengaturan"?"class=\"active dropdown\"":'dropdown';?>>
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Pengaturan <span class="caret"></span></a>
            <ul class="dropdown-menu navbar-inverse">
              <li><a style="color:white" href="?page=pengaturan&sub=kelas">Kelas</a></li>
              <li><a style="color:white" href="?page=pengaturan&sub=matakuliah">Mata Kuliah</a></li>
              <li><a style="color:white" href="?page=pengaturan&sub=ruang">Ruang Kuliah</a></li>
              <li><a style="color:white" href="?page=pengaturan&sub=dosen">Dosen</a></li>
              <li><a style="color:white" href="?page=pengaturan&sub=admin">Admin</a></li>
            </ul>
          </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="#">Hi, <?= $nama_admin;?></a></li>
          <li><a href="?page=logout"><span class="glyphicon glyphicon-off"></span> Keluar</a></li>
        </ul>
      </div>
    </nav>
    <div class="container">
