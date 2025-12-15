<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM customers WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && $user['password_hash'] && password_verify($password, $user['password_hash'])) {
        $_SESSION['customer_id'] = $user['id'];
        $_SESSION['customer_name'] = $user['full_name'];
        
        header("Location: index.php");
        exit;
    } else {
        $error = "Email atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Login Member - Veloré Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card border-0 shadow p-4" style="width: 400px;">
            <div class="card-body">
                <h3 class="text-center font-playfair mb-4">Login Tamu</h3>
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 rounded-0">MASUK</button>
                    
                    <div class="text-center mt-3">
                        <small>Belum punya akun? <a href="register.php">Daftar disini</a></small>
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