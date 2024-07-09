<?php
session_start();
if (!isset($_SESSION['nisn'])) {
  header("Location: login.php");
  exit;
}

$nisn = $_SESSION['nisn'];
$nama = isset($_SESSION['nama']) ? $_SESSION['nama'] : '';
$tempat_lahir = "";
$tanggal_lahir = "";
$jenis_kelamin = "";
$agama = "";
$alamat = "";

include('koneksi.php');

$form_submitted = false;
$error = '';
$sukses = '';

if (isset($_POST['simpan'])) {
  $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
  $tempat_lahir = isset($_POST['tempat_lahir']) ? $_POST['tempat_lahir'] : '';
  $tanggal_lahir = isset($_POST['tanggal_lahir']) ? $_POST['tanggal_lahir'] : '';
  $jenis_kelamin = isset($_POST['jenis_kelamin']) ? $_POST['jenis_kelamin'] : '';
  $agama = isset($_POST['agama']) ? $_POST['agama'] : '';
  $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';

  $timestamp_siswa = date("Y-m-d H:i:s");

  if ($nama && $tempat_lahir && $tanggal_lahir && $jenis_kelamin && $alamat && $agama) {
    $sql1 = "INSERT INTO siswa (nisn, nama, tempat_lahir, tanggal_lahir, jenis_kelamin, agama, alamat, timestamp_siswa, registrasi) VALUES ('$nisn', '$nama', '$tempat_lahir', '$tanggal_lahir', '$jenis_kelamin', '$agama', '$alamat', '$timestamp_siswa', 'y')
                ON DUPLICATE KEY UPDATE nama='$nama', tempat_lahir='$tempat_lahir', tanggal_lahir='$tanggal_lahir', jenis_kelamin='$jenis_kelamin', agama='$agama', alamat='$alamat', timestamp_siswa='$timestamp_siswa', registrasi='y'";

    $q1 = $koneksi->query($sql1);
    if ($q1) {
      $sukses = "Data berhasil disimpan";
      $form_submitted = true;
      $_SESSION['nama']=$nama;
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
  $nama = $row['nama'];
  $tempat_lahir = $row['tempat_lahir'];
  $tanggal_lahir = $row['tanggal_lahir'];
  $jenis_kelamin = $row['jenis_kelamin'];
  $agama = $row['agama'];
  $alamat = $row['alamat'];
  $timestamp_siswa = $row['timestamp_siswa'];
  $registrasi = $row['registrasi'];
  $form_submitted = ($registrasi == 'y');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Isi Data Siswa</title>

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
        <a class="list-group-item list-group-item-action list-group-item-primary p-3" href="dataRegistrasi.php"><i class="fa-solid fa-user"></i> Data Registrasi</a>
        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="dataOrtu.php"><i class="fa-solid fa-users"></i> Data Orang Tua</a>
        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="schools.php"><i class="fa-solid fa-school"></i> Schools</a>
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

        <h1 class="mx-5 my-3"><i class="fa-solid fa-user"></i> Data Registrasi anda</h1>

        <?php if ($form_submitted) : ?>
          <div class="container-fluid bg-tertiary mx-3">
            <div class="container-fluid" id="dataInfo">
              <div class="row">
                <div class="col-6">
                  <p style="font-size: 1.25rem;"><strong>Nama Lengkap:</strong><br> <?php echo $nama; ?></p>
                  <p style="font-size: 1.25rem;"><strong>Tanggal Lahir:</strong><br> <?php echo date('d F Y', strtotime($tanggal_lahir)); ?></p>
                  <p style="font-size: 1.25rem;"><strong>Agama:</strong><br> <?php echo $agama; ?></p>
                  <p style="font-size: 1.25rem;"><strong>Di isi pada:</strong><br> <?php echo $timestamp_siswa; ?></p>
                </div>
                <div class="col-6">
                  <p style="font-size: 1.25rem;"><strong>Tempat Lahir:</strong><br> <?php echo $tempat_lahir; ?></p>
                  <p style="font-size: 1.25rem;"><strong>Jenis Kelamin:</strong><br> <?php echo $jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'; ?></p>
                  <p style="font-size: 1.25rem;"><strong>Alamat:</strong><br> <?php echo $alamat; ?></p>
                </div>
              </div>
              <button class="btn btn-secondary" onclick="editData()">Edit Data</button>
            </div>
            <div class="text-end" id="btn">
              <a class="btn btn-primary" href="dataOrtu.php" role="button"><i class="fa-solid fa-users" id="btn"></i> Isi Data Orang Tua</a>
            </div>
          </div>
        <?php endif; ?>

        <form action="dataRegistrasi.php" method="post" id="dataForm" style="<?php echo $form_submitted ? 'display: none;' : 'display: block;'; ?>">
          <div class="container-fluid">
            <div class="row">
              <div class="col-6 my-3">
                <div class="mb-3">
                  <label for="nama" class="form-label">Nama Lengkap:</label>
                  <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama; ?>" required>
                </div>
              </div>
              <div class="col-6 my-3">
                <div class="mb-3">
                  <label for="tempat_lahir" class="form-label">Tempat Lahir:</label>
                  <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="<?php echo $tempat_lahir; ?>" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6 my-3">
                <div class="mb-3">
                  <label for="tanggal_lahir" class="form-label">Tanggal Lahir:</label>
                  <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo $tanggal_lahir; ?>" required>
                </div>
              </div>
              <div class="col-6 my-3">
                <div class="mb-3">
                  <label for="jenis_kelamin" class="form-label">Jenis Kelamin:</label>
                  <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                    <option value="L" <?php if ($jenis_kelamin == "L") echo "selected" ?>>Laki-Laki</option>
                    <option value="P" <?php if ($jenis_kelamin == "P") echo "selected" ?>>Perempuan</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6 my-3">
                <div class="mb-3">
                  <label for="agama" class="form-label">Agama:</label>
                  <input type="text" class="form-control" id="agama" name="agama" value="<?php echo $agama; ?>" required>
                </div>
              </div>
              <div class="col-6 my-3">
                <div class="mb-3">
                  <label for="alamat" class="form-label">Alamat:</label>
                  <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $alamat; ?>" required>
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