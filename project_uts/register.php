<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['full_name'];
    $email = $_POST['email'];
    $hp = $_POST['phone'];
    $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("INSERT INTO customers (full_name, email, phone, password_hash) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nama, $email, $hp, $pass]);
        echo "<script>alert('Pendaftaran Berhasil! Silakan Login.'); window.location='login_member.php';</script>";
    } catch (PDOException $e) {
        $error = "Email sudah terdaftar!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Daftar Member - Veloré Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center py-5" style="min-height: 100vh;">
        <div class="card border-0 shadow p-4" style="width: 450px;">
            <div class="card-body">
                <h3 class="text-center font-playfair mb-4">Daftar Akun Tamu</h3>
                <?php if(isset($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
                
                <form method="POST">
                    <div class="mb-3">
                        <label>Nama Lengkap</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>No Handphone</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 rounded-0">DAFTAR SEKARANG</button>
                    <div class="text-center mt-3">
                        <small>Sudah punya akun? <a href="login_member.php">Login disini</a></small>
                    </div>
                    <div class="text-center mt-2">
                        <small><a href="index.php" class="text-muted">Kembali ke Beranda</a></small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>