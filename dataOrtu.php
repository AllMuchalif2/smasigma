<?php
session_start();
if (!isset($_SESSION['nisn'])) {
  header("Location: login.php");
  exit;
}

$nisn = $_SESSION['nisn'];
$nama = isset($_SESSION['nama']) ? $_SESSION['nama'] : '';
$ayah = "";
$pekerjaan_ayah = "";
$no_telp_ayah = "";
$ibu = "";
$pekerjaan_ibu = "";
$no_telp_ibu = "";


include('koneksi.php');

$form_submitted = false;
$error = '';
$sukses = '';

if (isset($_POST['simpan'])) {
  $ayah = isset($_POST['ayah']) ? $_POST['ayah'] : '';
  $ibu = isset($_POST['ibu']) ? $_POST['ibu'] : '';
  $pekerjaan_ayah = isset($_POST['pekerjaan_ayah']) ? $_POST['pekerjaan_ayah'] : '';
  $pekerjaan_ibu = isset($_POST['pekerjaan_ibu']) ? $_POST['pekerjaan_ibu'] : '';
  $no_telp_ayah = isset($_POST['no_telp_ayah']) ? $_POST['no_telp_ayah'] : '';
  $no_telp_ibu = isset($_POST['no_telp_ibu']) ? $_POST['no_telp_ibu'] : '';


  $timestamp_ortu = date("Y-m-d H:i:s");

  if ($ayah && $ibu && $pekerjaan_ayah && $pekerjaan_ibu && $no_telp_ibu && $no_telp_ayah) {
    $sql1 = "INSERT INTO siswa (nisn, ayah, ibu, pekerjaan_ayah, pekerjaan_ibu, no_telp_ayah, no_telp_ibu, timestamp_ortu, ortu) VALUES ('$nisn', '$ayah', '$ibu', '$pekerjaan_ayah', '$pekerjaan_ibu', '$no_telp_ayah', '$no_telp_ibu', '$timestamp_ortu', 'y')
                ON DUPLICATE KEY UPDATE ayah='$ayah', ibu='$ibu', pekerjaan_ayah='$pekerjaan_ayah', pekerjaan_ibu='$pekerjaan_ibu', no_telp_ayah='$no_telp_ayah', no_telp_ibu='$no_telp_ibu', timestamp_ortu='$timestamp_ortu', ortu='y'";

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
  $ayah = $row['ayah'];
  $ibu = $row['ibu'];
  $pekerjaan_ayah = $row['pekerjaan_ayah'];
  $pekerjaan_ibu = $row['pekerjaan_ibu'];
  $no_telp_ayah = $row['no_telp_ayah'];
  $no_telp_ibu = $row['no_telp_ibu'];
  $timestamp_ortu = $row['timestamp_ortu'];
  $ortu = $row['ortu'];
  $form_submitted = ($ortu == 'y');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Isi Data Orang Tua</title>

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
        <a class="list-group-item list-group-item-action list-group-item-primary p-3" href="dataOrtu.php"><i class="fa-solid fa-users"></i> Data Orang Tua</a>
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

        <h1 class="mx-5 my-3"><i class="fa-solid fa-user"></i> Data Orang Tua</h1>

        <?php if ($form_submitted) : ?>
          <div class="container-fluid bg-tertiary mx-3">
            <div class="container-fluid" id="dataInfo">
              <div class="row">
                <div class="col-6">
                  <p style="font-size: 1.25rem;"><strong>Nama Ayah:</strong><br> <?php echo $ayah; ?></p>
                  <p style="font-size: 1.25rem;"><strong>Pekerjaan Ayah:</strong><br> <?php echo $pekerjaan_ayah; ?></p>
                  <p style="font-size: 1.25rem;"><strong>No Telp Ayah:</strong><br> <?php echo $no_telp_ayah; ?></p>
                  <p style="font-size: 1.25rem;"><strong>Di isi pada:</strong><br> <?php echo $timestamp_ortu; ?></p>
                </div>
                <div class="col-6">
                  <p style="font-size: 1.25rem;"><strong>Nama Ibu:</strong><br> <?php echo $ibu; ?></p>
                  <p style="font-size: 1.25rem;"><strong>Pekerjaan Ibu:</strong><br> <?php echo $pekerjaan_ibu; ?></p>
                  <p style="font-size: 1.25rem;"><strong>No Telp Ibu:</strong><br> <?php echo $no_telp_ibu; ?></p>
                </div>
              </div>
              <button class="btn btn-secondary" onclick="editData()">Edit Data</button>
            </div>
            <div class="text-end" id="btn">
              <a class="btn btn-primary" href="schools.php" role="button"><i class="fa-solid fa-school" id="btn"></i> Isi Data Sekolah</a>
            </div>
          </div>
        <?php endif; ?>

        <form action="dataOrtu.php" method="post" id="dataForm" style="<?php echo $form_submitted ? 'display: none;' : 'display: block;'; ?>">
          <div class="container-fluid">
            <div class="row">
              <div class="col-6 my-3">
                <div class="mb-3">
                  <label for="ayah" class="form-label">Nama Ayah:</label>
                  <input type="text" class="form-control" id="ayah" name="ayah" value="<?php echo $ayah; ?>" required>
                </div>
              </div>
              <div class="col-6 my-3">
                <div class="mb-3">
                  <label for="ibu" class="form-label">Nama Ibu:</label>
                  <input type="text" class="form-control" id="ibu" name="ibu" value="<?php echo $ibu; ?>" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6 my-3">
                <div class="mb-3">
                  <label for="pekerjaan_ayah" class="form-label">Pekerjaan Ayah:</label>
                  <input type="text" class="form-control" id="pekerjaan_ayah" name="pekerjaan_ayah" value="<?php echo $pekerjaan_ayah; ?>" required>
                </div>
              </div>
              <div class="col-6 my-3">
                <div class="mb-3">
                  <label for="pekerjaan_ibu" class="form-label">Pekerjaan Ibu:</label>
                  <input type="text" class="form-control" id="pekerjaan_ibu" name="pekerjaan_ibu" value="<?php echo $pekerjaan_ibu; ?>" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6 my-3">
                <div class="mb-3">
                  <label for="no_telp_ayah" class="form-label">No Telp Ayah:</label>
                  <input type="text" class="form-control" id="no_telp_ayah" name="no_telp_ayah" value="<?php echo $no_telp_ayah; ?>" required>
                </div>
              </div>
              <div class="col-6 my-3">
                <div class="mb-3">
                  <label for="no_telp_ibu" class="form-label">No Telp Ibu:</label>
                  <input type="text" class="form-control" id="no_telp_ibu" name="no_telp_ibu" value="<?php echo $no_telp_ibu; ?>" required>
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