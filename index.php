<?php
session_start();
if (!isset($_SESSION['nisn'])) {
    header("Location: login.php");
    exit;
}
$nama = isset($_SESSION['nama']) ? $_SESSION['nama'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pendaftaran</title>

    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet"> 
    <!-- css -->
    <link rel="stylesheet" href="styles.css">
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .status-message {
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .status-kosong {
            background-color: #DC3545;
            color: white;
        }

        .status-oke {
            background-color: #FFC107;
            color: black;
        }

        .status-diterima {
            background-color: #28A745;
            color: white;
        }

        .status-tolak {
            background-color: #DC3545;
            color: white;
        }
    </style>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar-->
        <div class="border-end bg-dark" id="sidebar-wrapper">
            <div class="sidebar-heading border-bottom text-light">PPDB</div>
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action list-group-item-primary p-3" href="index.php"><i class="fa-solid fa-bullhorn"></i> Pengumuman</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="dataRegistrasi.php"><i class="fa-solid fa-user"></i> Data Registrasi</a>
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
            <div class="container-fluid">
                <?php
                include('koneksi.php');

                $nisn = $_SESSION['nisn'];

                $result = $koneksi->query("SELECT registrasi, ortu, sekolah,status FROM siswa WHERE nisn='$nisn'");
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $registrasi = $row['registrasi'];
                    $ortu = $row['ortu'];
                    $sekolah = $row['sekolah'];
                    $status = $row['status'];

                    if ($status == 'kosong' && $registrasi == 'y' && $ortu == 'y' && $sekolah == 'y') {
                        $update_sql = "UPDATE siswa SET status='oke' WHERE nisn='$nisn'";
                        $koneksi->query($update_sql);
                    }
                }

                $sql = "SELECT status FROM siswa WHERE nisn='$nisn'";
                $result = $koneksi->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $status = $row['status'];

                    switch ($status) {
                        case "kosong":
                            echo '<div class="card-fluid mx-5 status-message status-kosong">
                <strong><i class="fa-solid fa-triangle-exclamation"></i> Perhatian</strong><br> Anda belum mengisi data diri, silahkan isi data terlebih dahulu.<a class="btn btn-primary" href="dataRegistrasi.php" role="button"><i class="fa-solid fa-pen-to-square"></i> Isi data</a>
            </div>';
                            break;
                        case "oke":
                            echo '<div class="card-fluid mx-5 status-message status-oke">
                <strong><i class="fa-solid fa-check"></i> Selamat</strong><br> Anda sudah mengisi semua data yang dibutuhkan, silahkan tunggu verifikasi dari admin.
            </div>';
                            break;
                        case "diterima":
                            echo '<div class="card-fluid mx-5 status-message status-diterima">
                <strong><i class="fa-solid fa-check"></i> Selamat</strong><br> Data anda memenuhi syarat penerimaan sekolah, anda diterima di sekolah ini.
            </div>';
                            break;
                        case "tolak":
                            echo '<div class="card-fluid mx-5 status-message status-tolak">
                <strong><i class="fa-solid fa-triangle-exclamation"></i> Mohon Maaf</strong><br> Data anda dianggap tidak memenuhi syarat penerimaan sekolah, oleh karena itu anda tidak diterima di sekolah ini.
            </div>';
                            break;
                        default:
                            echo '<div class="alert alert-danger">Status tidak dikenali.</div>';
                    }
                } else {
                    echo '<div class="alert alert-danger">NISN tidak ditemukan.</div>';
                }

                $koneksi->close();
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="scripts.js"></script>
</body>

</html>