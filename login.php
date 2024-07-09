<?php
session_start();
include('koneksi.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM siswa WHERE email = '$email'";
    $result = $koneksi->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // if (password_verify($password, $row['password'])) {
        if ($password == $row['password']) {
            $_SESSION['nisn'] = $row['nisn'];
            $_SESSION['nama'] = $row['nama'];
            header("Location: index.php");
        } else {
            echo "<script>alert('Password salah!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Email tidak ditemukan!'); window.history.back();</script>";
    }
}

$koneksi->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Siswa Baru</title>

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
                        <h3 class="text-center">Penerimaan Peserta <br>Didik Baru</h3>
                    </div>
                    <div class="card-body p-3 bg-white text-center">
                        <h5 class="text-center">Silahkan Login untuk memulai sesi anda</h5>
                        <form action="login.php" method="post">
                            <div class="input-group mb-3">
                                <input type="email" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="email" name="email" required>
                                <span class="input-group-text" id="email"><i class="fa-solid fa-envelope"></i></span>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="password" name="password" required>
                                <span class="input-group-text" id="password"><i class="fa-solid fa-lock"></i></span>
                            </div>
                            <div class="row d-flex">
                                <div class="col">

                                    <a href="register.php" class="btn text-primary"><i class="fa-solid fa-user-plus"></i> Belum punya akun?</a>
                                </div>
                                <div class="col">

                                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-right-to-bracket"></i> Login</button>
                                </div>
                            </div>
                            <div class="btn-group">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>