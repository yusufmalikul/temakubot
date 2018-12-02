<?php
set_time_limit(0);
require_once 'config.php';
require_once 'functions.php';

// h = hour 01-12
// m = minute 00-59
// a = am-pm
// $now = new DateTime(date("h:i a"));
$now = new DateTime(date("h:i a"));
echo date("h:i a");
$reset_time = new DateTime("01:00 am");

$remind_tugas = $pdo->query("SELECT isi_pengaturan FROM tbl_pengaturan WHERE nama_pengaturan = 'remind_tugas'")->fetchColumn();

if ($now < $reset_time && $remind_tugas == 0) {
  echo "reminder tugas ON";
  $pdo->query("UPDATE tbl_pengaturan SET isi_pengaturan = 1 WHERE nama_pengaturan = 'remind_tugas'");
  $pdo->query("UPDATE tbl_pengguna SET status_pengingat = 0");
}

if ($remind_tugas == 0) {
  echo "reminder off";
  exit();
}

$stmt = $pdo->query("SELECT * FROM tbl_pengguna WHERE status_pengingat = 0 AND waktu_pengingat <> 'off'");
$no_user = true;
while ($row = $stmt->fetch()) {
  $no_user = false;
  $user_id = $row['id_pengguna'];
  $user_time = new DateTime($row['waktu_pengingat']);
  echo "there are";
  if ($now >= $user_time) {
    echo "there is";
    // ingatkan
    $tugas_stmt = $pdo->query("SELECT * FROM tbl_tugas WHERE id_pengguna = $user_id ORDER BY id_tugas ASC");
    $pdo->query("UPDATE tbl_pengguna SET status_pengingat = 1, pesan_terakhir_pengguna = 'tugas' WHERE id_pengguna = $user_id");
    $reply = 'Tugas:';
    $no = 1;
    $ada_tugas = false;
    while ($res = $tugas_stmt->fetch()) {
      $ada_tugas = true;
      $reply .= "\n".$no++.". ".$res['isi_tugas'];
    }
    if (!$ada_tugas) {
      $reply .= " (tidak ada tugas)";
    }
    sendMessage($user_id, urlencode($reply));
  } else {
    echo "listening";
  }
}

if ($no_user) {
  // jika tidak ada lagi yg perlu diingatkan
  // turn off tugas reminder
  $pdo->query("UPDATE tbl_pengaturan SET isi_pengaturan = 0 WHERE nama_pengaturan = 'remind_tugas'");
}
?>
