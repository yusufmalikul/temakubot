<h1 class="text-center">Jadwal Perkuliahan</h1>
<br><br>
<div class="row">
  <div class="col-md-4">
        <div class="form-group">
          <label for="hari">Hari:</label>
          <select id="hari" class="form-control" name="hari">
            <option value="senin">Senin</option>
            <option value="selasa">Selasa</option>
            <option value="rabu">Rabu</option>
            <option value="kamis">Kamis</option>
            <option value="jum'at">Jum'at</option>
          </select>
        </div>
        <div class="form-group">
          <label for="kelas">Jam ke:</label>
          <select id="jam_ke" class="form-control" name="jam_ke">
            <option value="-1">Pilih jam</option>
            <?php
            for ($i=1; $i <= 13; $i++) {
              if ($i == 7) {
                echo "<option value=\"".$i."\">".$i." (istirahat)</option>";
              } else echo "<option value=\"".$i."\">".$i."</option>";
            }
            ?>
          </select>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="kelas">Kelas:</label>
          <select id="id_kelas" class="form-control" name="id_kelas">
            <option value="-1">Pilih kelas</option>
            <?php
            $stmt = $pdo->query("SELECT * FROM tbl_kelas ORDER BY nama_kelas ASC");
            while ($row = $stmt->fetch()) {
              echo "<option value=\"".$row['id_kelas']."\">".strtoupper($row['nama_kelas'])."</option>";
            }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label for="kelas">Mata kuliah:</label>
          <select id="kode_matakuliah" class="form-control" name="kode_matakuliah">
            <option value="-1">Pilih mata kuliah</option>
            <?php
            $stmt = $pdo->query("SELECT * FROM tbl_matakuliah ORDER BY nama_matakuliah ASC");
            while ($row = $stmt->fetch()) {
              echo "<option value=\"".$row['kode_matakuliah']."\">".$row['nama_matakuliah']."</option>";
            }
            ?>
          </select>
        </div>
      </div>
        <div class="col-md-4">
        <div class="form-group">
          <label for="kelas">Dosen:</label>
          <select id="id_dosen" class="form-control" name="id_dosen">
            <option value="-1">Pilih dosen</option>
            <?php
            $stmt = $pdo->query("SELECT * FROM tbl_dosen ORDER BY nama_dosen ASC");
            while ($row = $stmt->fetch()) {
              echo "<option value=\"".$row['id_dosen']."\">".$row['nama_dosen']."</option>";
            }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label for="kelas">Ruang:</label>
          <select id="id_ruang" class="form-control" name="id_ruang">
            <option value="-1">Pilih ruang</option>
            <?php
            $stmt = $pdo->query("SELECT * FROM tbl_ruang ORDER BY id_ruang ASC");
            while ($row = $stmt->fetch()) {
              echo "<option value=\"".$row['id_ruang']."\">".$row['nama_ruang']."</option>";
            }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label for="sks">SKS</label>
          <select id="sks" class="form-control" name="sks">
            <option value="-1">Pilih SKS</option>
            <?php
            for ($i=1; $i <= 6; $i++) {
              echo "<option value=\"".$i."\">".$i."</option>";
            }
            ?>
          </select>
        </div>
        <button id="savejadwal" class="btn btn-primary" type="button" name="submit"><span class="glyphicon glyphicon-floppy-disk"></span> Simpan</button>
        <button id="hapusjadwal" class="btn btn-primary" style="display:none" data-hapus-id=""><span class="glyphicon glyphicon-remove"></span> Hapus</button>
        <button id="resetjadwal" class="btn btn-primary" style="display:none">Batal</button>
        <input id="action" type="hidden" value="simpan">
        <input id="id_jadwal" type="hidden" value="">
      <br><br>
      </div>
      <br>
</div>
<div class="row">
  <div class="col-md-12">
    <p id="status"></p>
  </div>
</div>
<div class="row">
  <div id="getjadwal" class="col-md-12">
  </div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function(){

  $("#jam_ke").change(function(){
    var istirahat = "Tidak boleh memilih jam istirahat.";
    if ($("#hari").val() == "jum'at") {
      if ($("#jam_ke").val() == 6 || $("#jam_ke").val() == 7) {
        $("#jam_ke").val(1);
        alert(istirahat);
      }
    } else {
      if ($("#jam_ke").val() == 7) {
        $("#jam_ke").val(1);
        alert(istirahat);
      }
    }
  })

  $("#getjadwal").fadeOut(0).load("getjadwal.php?hari=senin").fadeIn(3000);
  $("#hari").change(function(){
    hari_val = $("#hari").val();
    $("#getjadwal").fadeOut(1000);
    $("#getjadwal").html("<i>Sedang memuat jadwal...</i>");
    $("#getjadwal").load("getjadwal.php?hari="+hari_val, function(){
    $("#getjadwal").fadeIn(2300);
    });
    $("#jam_ke").val(1);

    if ($("#hari").val() == "jum'at") {
      $("#jam_ke option[value=6]").text('6 (istirahat)');
    } else {
      $("#jam_ke option[value=6]").text('6');
    }

  });

  $("#hapusjadwal").click(function(){
    id_jadwal = $("#id_jadwal").val();
    hari_val = $("#hari").val();
    $("#getjadwal").fadeOut(1000);
    $("#getjadwal").html("<i>Berhasil dihapus. Sedang memuat jadwal...</i>");
    $("#getjadwal").load("getjadwal.php?hari="+hari_val+"&hapus="+id_jadwal, function(){
    $("#getjadwal").fadeIn(2300);
  });
});

  $("#savejadwal").click(function(){
    $("#savejadwal").prop("disabled", true);
    $("#savejadwal").text('Menyimpan...');
    $("#resetjadwal").css("display", "none");
    hari_val = $("#hari").val();
    jam_ke_val = $("#jam_ke").val();
    id_kelas_val = $("#id_kelas").val();
    kode_matakuliah_val = $("#kode_matakuliah").val();
    id_dosen_val = $("#id_dosen").val();
    id_ruang_val = $("#id_ruang").val();
    sks_val = $("#sks").val();
    action_val = $("#action").val();
    id_jadwal_val = $("#id_jadwal").val();
    $.post("savejadwal.php",
    {
      submit: 'savejadwal',
      hari: hari_val,
      jam_ke: jam_ke_val,
      id_kelas: id_kelas_val,
      kode_matakuliah: kode_matakuliah_val,
      id_dosen: id_dosen_val,
      id_ruang: id_ruang_val,
      sks: sks_val,
      action: action_val,
      id_jadwal: id_jadwal_val
    },
    function(data, status){
      $("#status").text(data).fadeOut(9000);
      $("#savejadwal").html('<span class="glyphicon glyphicon-floppy-disk"></span> Simpan');
      $("#savejadwal").prop("disabled", false);
    }).always(function(){
      $("#getjadwal").fadeOut(1000);
      $("#getjadwal").load("getjadwal.php?hari="+hari_val);
      $("#getjadwal").fadeIn(2300);
    });
  })
})
</script>
