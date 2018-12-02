<?php
require_once 'config.php';

if (isset($_GET['hapus'])) {
  $id_jadwal = $_GET['hapus'];
  $stmt = $pdo->prepare("DELETE FROM tbl_jadwal WHERE id_jadwal = ?");
  $stmt->execute([$id_jadwal]);
}

?>
<table id="jadwaltbl" class="table table-bordered">
  <tr>
    <th colspan="2">(<?= ucfirst($_GET['hari']);?>)<br>Jam Ke:</th><th>D207</th><th>D208</th><th>D303</th><th>Lab SIP</th><th>Bengkel Elektronika</th><th>Lab Komjar</th>
  </tr>
  <?php
  $jam_ke = [
    "07.00-07.50",
    "07.50-08.40",
    "08.40-09.30",
    "09.30-10.20",
    "10.20-11.10",
    "11.10-12.00",
    "12.00-12.30",
    "12.30-13.20",
    "13.20-14.10",
    "14.10-15.00",
    "15.00-15.50",
    "15.50-16.40",
    "16.40-17.30"
  ];
  $stmt = $pdo->prepare("SELECT tbl_jadwal.id_jadwal, tbl_jadwal.sks, tbl_jadwal.jam_ke,
    tbl_kelas.nama_kelas, tbl_kelas.id_kelas, tbl_kelas.warna_kelas, tbl_ruang.id_ruang,
    tbl_dosen.nama_dosen, tbl_dosen.id_dosen,
    tbl_matakuliah.nama_matakuliah, tbl_matakuliah.kode_matakuliah
    FROM tbl_jadwal
    INNER JOIN tbl_kelas ON tbl_kelas.id_kelas = tbl_jadwal.id_kelas
    INNER JOIN tbl_ruang ON tbl_ruang.id_ruang = tbl_jadwal.id_ruang
    INNER JOIN tbl_dosen ON tbl_dosen.id_dosen = tbl_jadwal.id_dosen
    INNER JOIN tbl_matakuliah ON tbl_matakuliah.kode_matakuliah = tbl_jadwal.kode_matakuliah
    WHERE tbl_jadwal.hari = ?");
  $stmt->execute([$_GET['hari']]);
  $row = $stmt->fetchAll();
  echo $pdo->errorInfo()[2];
  $td_ruang = [0,0,0,0,0,0,0];
  $jumlah_jam = 13;
  for ($i=1; $i <= $jumlah_jam; $i++) {
    if ($_GET['hari'] == "jum'at") {
      if ($i == 6 || $i == 7) {
        echo "<tr style=\"background-color:#ccc\">";
      }
    } else if ($i == 7) {
      echo "<tr style=\"background-color:#ccc\">";
    } else {
      echo "<tr>";
    }
    echo "<td>".$i."</td><td>".$jam_ke[$i-1]."</td>";
    $m = 0;
    for ($j=1; $j <= 6; $j++) {
      $found = false;
      for ($k=0; $k < count($row); $k++) {
        if ($row[$k]['jam_ke'] == $i && $row[$k]['id_ruang'] == $j) {
          $found = true;
          $td_ruang[$j] = $row[$k]['sks'];
          echo "<td data-id-jadwal=\"".$row[$k]['id_jadwal']."\"";
          echo " data-jam-ke=\"".$row[$k]['jam_ke']."\"";
          echo " data-kode-matakuliah=\"".$row[$k]['kode_matakuliah']."\"";
          echo " data-id-kelas=\"".$row[$k]['id_kelas']."\" data-id-dosen=\"".$row[$k]['id_dosen']."\" data-id-ruang=\"".$row[$k]['id_ruang']."\"";
          echo " data-sks=\"".$row[$k]['sks']."\"";
          echo " rowspan=\"".$row[$k]['sks']."\" style=\"background-color:".$row[$k]['warna_kelas']."\">".$row[$k]['nama_matakuliah']." (".strtoupper($row[$k]['nama_kelas']).")<br>".$row[$k]['nama_dosen']."</td>";
          break;
        }
      }
        if ($td_ruang[$j] > 0) {
          --$td_ruang[$j];
          continue;
        } else {
          echo "<td></td>";
        }
    }
    echo "</tr>\n";
  }
  ?>
</table>
<script type="text/javascript">

  resetForm();

  function resetForm(){
    $("#jadwaltbl td").removeClass("highlight");
    $("#savejadwal").html("<span class=\"glyphicon glyphicon-floppy-disk\"></span> Simpan");
    $("#action").val("simpan");
    $("#id_jadwal").val("");
    $("select#jam_ke").val("-1");
    $("select#id_kelas").val("-1");
    $("select#kode_matakuliah").val("-1");
    $("select#id_dosen").val("-1");
    $("select#id_ruang").val("-1");
    $("select#sks").val("-1");
    $("#resetjadwal").css("display","none");
    $("#hapusjadwal").css("display","none");
  }

  $(document).ready(function(){
    $("#jadwaltbl tr:has(td)").mouseover(function(){
      $(this).css("cursor", "pointer");
    });
    $("#jadwaltbl tr:has(td)").click(function(e){
      var clickedCell = $(e.target).closest("td");
      if (clickedCell.data("id-jadwal")) {
        $("#jadwaltbl td").removeClass("highlight");
        clickedCell.addClass("highlight");
        $("#savejadwal").html("<span class=\"glyphicon glyphicon-floppy-disk\"></span> Ubah");
        $("#action").val("ubah");
        $("#resetjadwal").css("display","initial");
        $("#hapusjadwal").css("display","initial");
        $("#id_jadwal").val(clickedCell.data("id-jadwal"));

        $("select#jam_ke").val(clickedCell.data("jam-ke"));
        $("select#id_kelas").val(clickedCell.data("id-kelas"));
        $("select#kode_matakuliah").val(clickedCell.data("kode-matakuliah"));
        $("select#id_dosen").val(clickedCell.data("id-dosen"));
        $("select#id_ruang").val(clickedCell.data("id-ruang"));
        $("select#sks").val(clickedCell.data("sks"));
      }
    })
    $("#resetjadwal").click(function(){
      resetForm();
    });
  })
</script>
