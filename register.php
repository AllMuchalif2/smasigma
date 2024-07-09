<?php
include('koneksi.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['nama'];
    $nisn = $_POST['nisn'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $sql_check = "SELECT * FROM siswa WHERE nisn='$nisn' OR email='$email'";
    $result_check = $koneksi->query($sql_check);

    if ($result_check->num_rows > 0) {
        echo "<script>alert('NISN atau Email sudah digunakan!'); window.history.back();</script>";
        exit();
    }

    if ($password != $confirm_password) {
        echo "<script>alert('Password tidak cocok!'); window.history.back();</script>";
    } else {
        // $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO siswa (nama,nisn, email, password) VALUES ('$name','$nisn', '$email', '$password')";

        if ($koneksi->query($sql) === TRUE) {
            header("Location: login.php");
        }
    }
}

$koneksi->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Siswa Baru</title>

    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet"> <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card bg-secondary-subtle p-2">
                    <div class="card-header">
                        <h3 class="text-center">Penerimaan Peserta Didik Baru</h3>
                    </div>
                    <div class="card-body p-3 bg-white">
                        <h5 class="text-center">Pendaftaran Siswa Baru</h5>
                        <form action="register.php" method="post">

                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Nama Lengkap" aria-label="Nama Lengkap" aria-describedby="nama" name="nama" required>
                                <span class="input-group-text" id="nama"><i class="fa-solid fa-user"></i></span>
                            </div>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="NISN" aria-label="NISN" aria-describedby="nisn" name="nisn" required>
                                <span class="input-group-text" id="nisn"><i class="fa-solid fa-user"></i></span>
                            </div>
                            <div class="input-group mb-3">
                                <input type="email" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="email" name="email" required>
                                <span class="input-group-text" id="email"><i class="fa-solid fa-envelope"></i></span>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="password" name="password" required>
                                <span class="input-group-text" id="password"><i class="fa-solid fa-lock"></i></span>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" placeholder="Konfirmasi Password" aria-label="Konfirmasi Password" aria-describedby="confirm_password" name="confirm_password" required>
                                <span class="input-group-text" id="confirm_password"><i class="fa-solid fa-lock"></i></span>
                            </div>
                            <div class="btn-grou text-center">
                                <a href="login.php" class="btn text-primary"><i class="fa-solid fa-arrow-left"></i> Saya sudah punya<br>akun</a>
                                <button type="submit" class="btn btn-primary">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>