<?php

function sendMessage($chat_id, $reply){
  $sendto = API_URL.'sendmessage?chat_id='.$chat_id.'&parse_mode=html&text='.$reply;
  file_get_contents($sendto);
}

function query($userData){
  global $pdo;
  $text = trim(removeDoubleSpace($userData['text']));
  $reply = "";
  $subject = $text;
  if (preg_match("/(hapus no) (\d)/", $text, $matches)) {
    $stmt = $pdo->query("SELECT pesan_terakhir_pengguna FROM tbl_pengguna WHERE id_pengguna = '".$userData['chat_id']."'");
    $pesan_terakhir = $stmt->fetch()['pesan_terakhir_pengguna'];
    if ($pesan_terakhir == 'tugas' || $pesan_terakhir == 'daftar tugas') {
      $stmt = $pdo->query("SELECT id_tugas FROM tbl_tugas WHERE id_pengguna = '".$userData['chat_id']."' ORDER BY id_tugas ASC");
      $row = $stmt->fetchAll();
      $hapus = $row[($matches[2]-1)]['id_tugas'];
      $stmt = $pdo->query("DELETE FROM tbl_tugas WHERE id_tugas = $hapus");
      $reply = "Nomor ".$matches[2]." dihapus dari daftar tugas";
    } else {
      $reply = "Mungkin Anda ingin mengetahui daftar tugas dengan mengetik <b>tugas</b>";
    }
  } else if (stripos($subject, "dosen") !== false) {
    $reply = query_dosen($subject);
  } else if (stripos($subject, "jadwal") !== false) {
    $reply = query_jadwal($subject);
  } else if (stripos($subject, "tugas") !== false) {
    $reply = query_tugas($subject, $userData['chat_id']);
  } else if ($subject == "/start") {
    $ada = $pdo->query("SELECT COUNT(*) FROM tbl_pengguna WHERE id_pengguna = '".$userData['chat_id']."'")->fetchColumn();
    if ($ada == 0) {
      $pdo->query("INSERT INTO tbl_pengguna (id_pengguna, waktu_pengingat, status_pengingat)
                    VALUES('".$userData['chat_id']."', 'off', 0)");
    }
    $reply = "Hi, ".$userData['first_name'].". \nAku adalah Temaku. Ketik <b>bantuan</b> untuk mempelajari bagaimana berinteraksi denganku :)";
  } else if (stripos($subject, "bantuan") !== false){
    $reply = query_bantuan($subject);
  } else if (stripos($subject, "pengingat") !== false){
    $reply = query_pengingat_tugas($subject, $userData['chat_id']);
  } else {
    $reply = "Aku tidak mengerti apa yang kamu maksud.\nKetik <b>bantuan</b> jika mengalami kesulitan :)";
  }
  $stmt = $pdo->prepare("UPDATE tbl_pengguna SET pesan_terakhir_pengguna = ? WHERE id_pengguna = ?");
  $stmt->execute([$text, $userData['chat_id']]);
  return $reply;
}

function query_dosen($subject){
  if (strtolower($subject) == "dosen") {
    $reply = "Contoh: dosen sistem informasi";
    return $reply;
  }
  if (preg_match("/dosen ([a-z ]+)(\d[a-c]?\d?)?/i", $subject, $matches)) {
    global $pdo;
    $kelas = '';
    if (isset($matches[2])) {
      $matches[1] = str_replace("kelas", '', $matches[1]);
      $kelas = trim($matches[2]);
      $stmt = $pdo->prepare("SELECT tbl_dosen.nama_dosen, tbl_jadwal.kode_matakuliah, tbl_matakuliah.nama_matakuliah, tbl_kelas.nama_kelas
        FROM tbl_dosen INNER JOIN tbl_jadwal ON tbl_jadwal.id_dosen = tbl_dosen.id_dosen
        INNER JOIN tbl_matakuliah ON tbl_matakuliah.kode_matakuliah = tbl_jadwal.kode_matakuliah
        INNER JOIN tbl_kelas ON tbl_kelas.id_kelas = tbl_jadwal.id_kelas
        WHERE tbl_matakuliah.nama_matakuliah LIKE concat('%', ? ,'%') AND tbl_kelas.nama_kelas LIKE concat('%', ? ,'%')
        ORDER BY tbl_matakuliah.nama_matakuliah");
        $stmt->execute([trim($matches[1]), $matches[2]]);
    } else {
      $stmt = $pdo->prepare("SELECT tbl_dosen.nama_dosen, tbl_jadwal.kode_matakuliah, tbl_matakuliah.nama_matakuliah, tbl_kelas.nama_kelas
        FROM tbl_dosen INNER JOIN tbl_jadwal ON tbl_jadwal.id_dosen = tbl_dosen.id_dosen
        INNER JOIN tbl_matakuliah ON tbl_matakuliah.kode_matakuliah = tbl_jadwal.kode_matakuliah
        INNER JOIN tbl_kelas ON tbl_kelas.id_kelas = tbl_jadwal.id_kelas
        WHERE tbl_matakuliah.nama_matakuliah LIKE concat('%', ? ,'%') ORDER BY tbl_matakuliah.nama_matakuliah");
        $stmt->execute([$matches[1]]);
    }
    $reply = "";
    while ($res = $stmt->fetch()) {
      $reply .= $res['nama_dosen']."\n".$res['nama_matakuliah']." (".$res['nama_kelas'].")\n\n";
    }
    if (isset($res['nama_kelas'])) {
      echo "ada";
    }
    if (empty($reply)) {
      $reply = "dosen ".$matches[1]." tidak ditemukan";
    }
    return $reply;
  }
}

function query_jadwal($subject){
  $jam_ke = [
    "07.00",
    "07.50",
    "08.40",
    "09.30",
    "10.20",
    "11.10",
    "12.00",
    "12.30",
    "13.20",
    "14.10",
    "15.00",
    "15.50",
    "16.40",
    "17.30"
  ];
  if (strtolower($subject) == "jadwal") {
    $reply = "Contoh: jadwal sistem informasi";
    return $reply;
  }
  if (preg_match("/jadwal ([a-z ]+)(\d[a-c]?\d?)?/i", $subject, $matches)) {
   global $pdo;
   $matches[1] = str_replace("kelas", '', $matches[1]);
   $matches[1] = str_replace("kuliah", '', $matches[1]);
   $matches[1] = trim($matches[1]);
   if (isset($matches[2])) {
     $stmt = $pdo->prepare("SELECT tbl_jadwal.hari, tbl_jadwal.jam_ke, tbl_jadwal.sks, tbl_ruang.nama_ruang,
       tbl_matakuliah.nama_matakuliah, tbl_kelas.nama_kelas
       FROM tbl_jadwal
       INNER JOIN tbl_matakuliah ON tbl_matakuliah.kode_matakuliah = tbl_jadwal.kode_matakuliah
       INNER JOIN tbl_kelas ON tbl_kelas.id_kelas = tbl_jadwal.id_kelas
       INNER JOIN tbl_ruang ON tbl_ruang.id_ruang = tbl_jadwal.id_ruang
       WHERE tbl_matakuliah.nama_matakuliah LIKE concat('%',?,'%') AND tbl_kelas.nama_kelas LIKE concat('%',?,'%')");
       $stmt->execute([$matches[1], $matches[2]]);
   } else {
     $stmt = $pdo->prepare("SELECT tbl_jadwal.hari, tbl_jadwal.jam_ke, tbl_jadwal.sks, tbl_ruang.nama_ruang,
       tbl_matakuliah.nama_matakuliah, tbl_kelas.nama_kelas
       FROM tbl_jadwal
       INNER JOIN tbl_matakuliah ON tbl_matakuliah.kode_matakuliah = tbl_jadwal.kode_matakuliah
       INNER JOIN tbl_kelas ON tbl_kelas.id_kelas = tbl_jadwal.id_kelas
       INNER JOIN tbl_ruang ON tbl_ruang.id_ruang = tbl_jadwal.id_ruang
       WHERE tbl_matakuliah.nama_matakuliah LIKE concat('%',?,'%')");
       $stmt->execute([$matches[1]]);
   }
   $reply = '';
   while ($row = $stmt->fetch()) {
     $reply .= $row['hari']."\n";
     $reply .= $row['nama_ruang']."\n";
     $reply .= $jam_ke[$row['jam_ke']-1]."-".$jam_ke[$row['jam_ke']+$row['sks']-1]."\n";
     $reply .= $row['nama_matakuliah']."\n";
     $reply .= "Kelas ".$row['nama_kelas']."\n\n";
   }
   if (empty($reply)) {
     return "Jadwal kuliah ".$matches[1]." tidak ditemukan";
   }
   return $reply;
 }
}

function query_tugas($subject, $chat_id){
  global $pdo;
  $reply = "";
  if (preg_match("/(ada|tambah) tugas (.+)/i", $subject, $matches)) {
    $stmt = $pdo->prepare("INSERT INTO tbl_tugas (id_pengguna, isi_tugas, waktu_tugas) VALUES (?,?,?)");
    $stmt->execute([$chat_id, $matches[2], date("Y-m-d H:i:s")]);
    $reply = "OK, telah ditambahkan ke daftar <b>tugas</b>";
  } else if (preg_match("/(tugas)/i", $subject, $matches)) {
    $jumlah = $pdo->query("SELECT COUNT(*) FROM tbl_tugas WHERE id_pengguna = $chat_id")->fetchColumn();
    if ($jumlah < 1) {
      $reply = "Tidak ada tugas.\nUntuk menambahkan tugas ketik '<b>ada tugas</b> isi tugas'";
    } else {
      $stmt = $pdo->query("SELECT * FROM tbl_tugas WHERE id_pengguna = $chat_id ORDER BY id_tugas ASC");
      $reply = "Tugas:";
      $no = 1;
      while ($row = $stmt->fetch()) {
        $reply .= "\n".$no++.". ".$row['isi_tugas'];
      }
    }
  }
  return $reply;
}

function query_pengingat_tugas($subject, $chat_id){
  global $pdo;
  if (preg_match("/(matikan|stop) (pengingat)/i", $subject, $matches)) {
    $pdo->query("UPDATE tbl_pengguna SET waktu_pengingat = 'off' WHERE id_pengguna = '$chat_id'");
    $reply = "Pengingat tugas dimatikan.\n";
    $reply .= "Untuk cara menyalakan lihat <b>bantuan</b>.";
    return $reply;
  }
  if (preg_match("/(pengingat) (jam|pukul) (\d?\d)\:(\d\d)/i", $subject, $matches)) {
    $hour = $matches[3];
    $minute = $matches[4];
    $a = "";
    if ($hour > 12) {
      $a = "pm";
      $hour -= 12;
    } else {
      $a = "am";
    }
    $waktu = $hour.":".$minute." ".$a;
    $pdo->query("UPDATE tbl_pengguna SET waktu_pengingat = '$waktu' WHERE id_pengguna = '$chat_id'");

    $now = new DateTime(date("h:i a"));
    $set = new DateTime($hour.":".$minute." ".$a);
    if ($set > $now) {
      $pdo->query("UPDATE tbl_pengguna SET status_pengingat = 0 WHERE id_pengguna = '$chat_id'");
      $pdo->query("UPDATE tbl_pengaturan SET isi_pengaturan = 1 WHERE nama_pengaturan = 'remind_tugas'");
    } else {
      $pdo->query("UPDATE tbl_pengguna SET status_pengingat = 1 WHERE id_pengguna = '$chat_id'");
    }

    $reply = "Waktu pengingat tugas diatur pukul ".$waktu;
    return $reply;
  }
}

function query_bantuan(){
  $reply = "<b>Bantuan</b>\n\n";
  $reply .= "<b>jadwal</b>: jadwal sistem informasi\n";
  $reply .= "<b>dosen</b>: dosen sistem informasi\n";
  $reply .= "<b>ada tugas</b>: ada tugas pembuatan video\n";
  $reply .= "<b>tugas</b>: tugas\n";
  $reply .= "<b>hapus no</b>: hapus no 3\n";
  $reply .= "<b>pengingat pukul</b>: pengingat pukul 19:00\n";
  $reply .= "<b>matikan pengingat</b>: matikan pengingat";
  return $reply;
}

function getUserData($jsondata){
  $chat_id = $jsondata['message']['chat']['id'];
  $text = $jsondata['message']['text'];
  $first_name = $jsondata['message']['chat']['first_name'];
  $userData = [
    "chat_id" => $chat_id,
    "first_name" => $first_name,
    "text" => $text
  ];
  return $userData;
}

function removeDoubleSpace($text) {
  return preg_replace("/\s\s+/", ' ', $text);
}

?>
