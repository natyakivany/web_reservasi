<?php
require 'config.php';

if (!isset($_SESSION['customer_id'])) {
    echo "<script>
            alert('Maaf, Anda harus Login atau Daftar Akun terlebih dahulu untuk memesan kamar!');
            window.location='login_member.php';
          </script>";
    exit; 
}

if (!isset($_GET['room_id'])) {
    header("Location: index.php");
    exit;
}

$room_id = $_GET['room_id'];
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->execute([$room_id]);
$room = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $stmtCheck = $pdo->prepare("SELECT id FROM customers WHERE email = ?");
    $stmtCheck->execute([$email]);
    $existingUser = $stmtCheck->fetch();

    if ($existingUser) {
        $customer_id = $existingUser['id'];
    } else {
        $stmtUser = $pdo->prepare("INSERT INTO customers (full_name, email, phone, address) VALUES (?, ?, ?, ?)");
        $stmtUser->execute([$full_name, $email, $phone, $address]);
        $customer_id = $pdo->lastInsertId();
    }

    $checkin = new DateTime($_POST['checkin']);
    $checkout = new DateTime($_POST['checkout']);
   
    if ($checkin >= $checkout) {
        echo "<script>alert('Tanggal Check-out harus setelah Check-in!');</script>";
    } else {
        $interval = $checkin->diff($checkout);
        $nights = $interval->days;
        $total = $nights * $room['price'];
        $booking_code = 'BOK-' . strtoupper(substr(md5(time()), 0, 6));
        
        $stmtBook = $pdo->prepare("INSERT INTO bookings (booking_code, room_id, customer_id, checkin_date, checkout_date, nights, total_amount, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
        $stmtBook->execute([
            $booking_code, $room_id, $customer_id, $_POST['checkin'], $_POST['checkout'], $nights, $total
        ]);

        echo "<script>alert('Booking Berhasil! Kode Booking Anda: $booking_code'); window.location='index.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Booking Kamar - Veloré Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h2 class="text-center mt-2">Form Reservasi</h2>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-info">
                            Anda memesan: <strong><?= htmlspecialchars($room['type']) ?></strong><br>
                            Harga: Rp <?= number_format($room['price']) ?> / malam
                        </div>
                        <form method="POST">
                            <h5 class="mb-3">Data Diri</h5>
                            
                            <?php 
                                $inputName = isset($_SESSION['customer_name']) ? $_SESSION['customer_name'] : '';
                                
                            ?>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Nama Lengkap</label>
                                    <input type="text" name="full_name" class="form-control" required value="<?= htmlspecialchars($inputName) ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>No Handphone</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Alamat</label>
                                <textarea name="address" class="form-control" rows="2"></textarea>
                            </div>

                            <h5 class="mb-3 mt-4">Detail Menginap</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Check-in</label>
                                    <input type="date" name="checkin" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Check-out</label>
                                    <input type="date" name="checkout" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-dark btn-lg rounded-0">KONFIRMASI RESERVASI</button>
                                <a href="index.php" class="btn btn-outline-secondary rounded-0">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>