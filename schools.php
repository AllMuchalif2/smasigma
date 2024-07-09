<?php
session_start();
if (!isset($_SESSION['nisn'])) {
  header("Location: login.php");
  exit;
}

$nisn = $_SESSION['nisn'];
$nama = isset($_SESSION['nama']) ? $_SESSION['nama'] : '';
$tahun_lulus = "";
$nilai_mtk = "";
$kab_kota = "";
$nilai_inggris = "";
$provinsi = "";
$nilai_indo = "";
$foto_kk = "";

include('koneksi.php');

$form_submitted = false;
$error = '';
$sukses = '';

if (isset($_POST['simpan'])) {
  $tahun_lulus = isset($_POST['tahun_lulus']) ? $_POST['tahun_lulus'] : '';
  $nilai_mtk = isset($_POST['nilai_mtk']) ? $_POST['nilai_mtk'] : '';
  $kab_kota = isset($_POST['kab_kota']) ? $_POST['kab_kota'] : '';
  $nilai_inggris = isset($_POST['nilai_inggris']) ? $_POST['nilai_inggris'] : '';
  $provinsi = isset($_POST['provinsi']) ? $_POST['provinsi'] : '';
  $nilai_indo = isset($_POST['nilai_indo']) ? $_POST['nilai_indo'] : '';
  $foto_kk = isset($_FILES['foto_kk']['name']) ? $_FILES['foto_kk']['name'] : '';

  $timestamp_sekolah = date("Y-m-d H:i:s");

  if ($tahun_lulus && $nilai_mtk && $kab_kota && $nilai_inggris && $provinsi && $nilai_indo && $foto_kk) {
    $target_dir = "kk/";
    $file_extension = pathinfo($foto_kk, PATHINFO_EXTENSION);
    $new_file_name = $nisn . '.' . $file_extension;
    $target_file = $target_dir . $new_file_name;

    $result = $koneksi->query("SELECT foto_kk FROM siswa WHERE nisn='$nisn'");
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      if (file_exists($row['foto_kk'])) {
        unlink($row['foto_kk']);
      }
    }

    move_uploaded_file($_FILES["foto_kk"]["tmp_name"], $target_file);

    $sql1 = "INSERT INTO siswa (nisn, tahun_lulus, nilai_mtk, kab_kota, nilai_inggris, provinsi, nilai_indo, foto_kk, timestamp_sekolah, sekolah) 
             VALUES ('$nisn', '$tahun_lulus', '$nilai_mtk', '$kab_kota', '$nilai_inggris', '$provinsi', '$nilai_indo', '$target_file', '$timestamp_sekolah', 'y')
             ON DUPLICATE KEY UPDATE 
             tahun_lulus='$tahun_lulus', nilai_mtk='$nilai_mtk', kab_kota='$kab_kota', nilai_inggris='$nilai_inggris', provinsi='$provinsi', nilai_indo='$nilai_indo', foto_kk='$target_file', timestamp_sekolah='$timestamp_sekolah', sekolah='y'";

    $q1 = $koneksi->query($sql1);
    if ($q1) {
      $sukses = "Data berhasil disimpan";
      $form_submitted = true;
    } else {
      $error = "Data gagal disimpan";
    }
  } else {
    $error = "Silakan masukkan semua data";
  }
}

$result = $koneksi->query("SELECT * FROM siswa WHERE nisn='$nisn'");
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $tahun_lulus = $row['tahun_lulus'];
  $nilai_mtk = $row['nilai_mtk'];
  $kab_kota = $row['kab_kota'];
  $nilai_inggris = $row['nilai_inggris'];
  $provinsi = $row['provinsi'];
  $nilai_indo = $row['nilai_indo'];
  $foto_kk = $row['foto_kk'];
  $timestamp_sekolah = $row['timestamp_sekolah'];
  $sekolah = $row['sekolah'];
  $form_submitted = ($sekolah == 'y');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Isi Data Sekolah</title>

  <!-- font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <!-- css -->
  <link rel="stylesheet" href="styles.css">
  <!-- bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!-- icon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
  <div class="d-flex" id="wrapper">
    <!-- Sidebar-->
    <div class="border-end bg-dark" id="sidebar-wrapper">
      <div class="sidebar-heading border-bottom text-light">PPDB</div>
      <div class="list-group list-group-flush">
        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="index.php"><i class="fa-solid fa-bullhorn"></i> Pengumuman</a>
        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="dataRegistrasi.php"><i class="fa-solid fa-user"></i> Data Registrasi</a>
        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="dataOrtu.php"><i class="fa-solid fa-users"></i> Data Orang Tua</a>
        <a class="list-group-item list-group-item-action list-group-item-primary p-3" href="schools.php"><i class="fa-solid fa-school"></i> Schools</a>
        <a class="list-group-item list-group-item-action list-group-item-light p-3 mt-auto" href="logout.php"><i class="fa-solid fa-sign-out-alt"></i> Logout</a>
      </div>
    </div>

    <!-- Page content wrapper-->
    <div id="page-content-wrapper">
      <!-- Top navigation-->
      <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom mb-3">
        <div class="container-fluid">
          <button class="btn btn-link text-dark" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
          <span class="mx-5"><?php echo $_SESSION['nama']; ?></span>
        </div>
      </nav>
      <!-- Page content-->
      <div class="container-fluid bg-tertiary mb-5">
        <?php if ($form_submitted) : ?>
          <div class="container-fluid bg-success rounded py-3" id="alert">
            <h4 class="mt-1 pl-4">Data berhasil disimpan</h4>
          </div>
        <?php elseif ($error) : ?>
          <div class="container-fluid bg-danger rounded py-3">
            <?php echo $error; ?>
          </div>
        <?php endif; ?>

        <h1 class="mx-5 my-3"><i class="fa-solid fa-user"></i> Isi Data Sekolah</h1>

        <?php if ($form_submitted) : ?>
          <div class="container-fluid bg-tertiary mx-3">
            <div class="container-fluid" id="dataInfo">
              <div class="row">
                <div class="col-6">
                  <p style="font-size: 1.25rem;"><strong>Tahun Lulus:</strong><br> <?php echo $tahun_lulus; ?></p>
                  <p style="font-size: 1.25rem;"><strong>Kabupaten/Kota:</strong><br> <?php echo $kab_kota; ?></p>
                  <p style="font-size: 1.25rem;"><strong>Provinsi:</strong><br> <?php echo $provinsi; ?></p>
                  <p style="font-size: 1.25rem;"><strong>Di isi pada:</strong><br> <?php echo $timestamp_sekolah; ?></p>
                  <div>
                    <p style="font-size: 1.25rem;"><strong>Foto Kartu Keluarga:</strong></p>
                    <img src="<?php echo $foto_kk . '?' . time(); ?>" style="max-width: 100%; height: auto;">
                  </div>
                </div>
                <div class="col-6">
                  <p style="font-size: 1.25rem;"><strong>Nilai MTK:</strong><br> <?php echo $nilai_mtk; ?></p>
                  <p style="font-size: 1.25rem;"><strong>Nilai Bahasa Inggris:</strong><br> <?php echo $nilai_inggris; ?></p>
                  <p style="font-size: 1.25rem;"><strong>Nilai Bahasa Indonesia:</strong><br> <?php echo $nilai_indo; ?></p>
                </div>
              </div>
              <button class="btn btn-secondary" onclick="editData()">Edit Data</button>
            </div>
          </div>
        <?php endif; ?>

        <form action="schools.php" method="post" enctype="multipart/form-data" id="dataForm" style="<?php echo $form_submitted ? 'display: none;' : 'display: block;'; ?>">
          <div class="container-fluid">
            <div class="row">
              <div class="col-6 my-3">
                <div class="mb-3">
                  <label for="tahun_lulus" class="form-label">Tahun Lulus:</label>
                  <input type="number" class="form-control" id="tahun_lulus" name="tahun_lulus" value="<?php echo $tahun_lulus; ?>" required>
                </div>
              </div>
              <div class="col-6 my-3">
                <div class="mb-3">
                  <label for="nilai_mtk" class="form-label">Nilai matematika :</label>
                  <input type="number" class="form-control" id="nilai_mtk" name="nilai_mtk" value="<?php echo $nilai_mtk; ?>" min="0" max="100" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6 my-3">
                <div class="mb-3">
                  <label for="kab_kota" class="form-label">Kab/Kota:</label>
                  <input type="text" class="form-control" id="kab_kota" name="kab_kota" value="<?php echo $kab_kota; ?>" required>
                </div>
              </div>
              <div class="col-6 my-3">
                <div class="mb-3">
                  <label for="nilai_inggris" class="form-label">Nilai Bahasa Inggris :</label>
                  <input type="number" class="form-control" id="nilai_inggris" name="nilai_inggris" value="<?php echo $nilai_inggris; ?>" min="0" max="100" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6 my-3">
                <div class="mb-3">
                  <label for="provinsi" class="form-label">Provinsi:</label>
                  <input type="text" class="form-control" id="provinsi" name="provinsi" value="<?php echo $provinsi; ?>" required>
                </div>
              </div>
              <div class="col-6 my-3">
                <div class="mb-3">
                  <label for="nilai_indo" class="form-label">Nilai Bahasa Indonesia :</label>
                  <input type="number" class="form-control" id="nilai_indo" name="nilai_indo" value="<?php echo $nilai_indo; ?>" min="0" max="100" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6 my-3">
                <div class="mb-3">
                  <label for="foto_kk" class="form-label">Foto KK:</label>
                  <input type="file" class="form-control" id="foto_kk" name="foto_kk" required>
                </div>
              </div>
            </div>
            <div class="row my-3">
              <div class="col-6">
                <button type="submit" class="btn btn-primary w-100" name="simpan"><i class="fa-solid fa-floppy-disk"></i> Kirim Data</button>
              </div>
              <div class="col-6">
                <button type="button" class="btn btn-secondary w-100" name="reset" onclick="clearForm()"><i class="fa-solid fa-rotate"></i> Reset Data</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- script -->
  <script>
    function clearForm() {
      var form = document.getElementById("dataForm");

      for (var i = 0; i < form.elements.length; i++) {
        var element = form.elements[i];

        if (element.tagName.toLowerCase() === "input" || element.tagName.toLowerCase() === "select") {
          element.value = "";
        }
      }
    }

    function showForm() {
      document.getElementById('dataForm').style.display = 'block';
      document.getElementById('dataInfo').style.display = 'none';
      document.getElementById('alert').style.display = 'none';
      document.getElementById('btn').style.display = 'block';
    }

    function editData() {
      document.getElementById('dataForm').style.display = 'block';
      document.getElementById('dataInfo').style.display = 'none';
      document.getElementById('alert').style.display = 'none';
      document.getElementById('btn').style.display = 'none';
    }
  </script>
  <!-- all docs script -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="scripts.js"></script>

</body>

</html>