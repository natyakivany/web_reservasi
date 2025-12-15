<?php
require 'config.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login_member.php");
    exit;
}

$customer_id = $_SESSION['customer_id'];

try {
    $stmt = $pdo->prepare("SELECT b.*, r.type as room_type, r.price 
                           FROM bookings b 
                           JOIN rooms r ON b.room_id = r.id 
                           WHERE b.customer_id = ? 
                           ORDER BY b.created_at DESC");
    $stmt->execute([$customer_id]);
    $histories = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Riwayat Booking - Veloré Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { background-color: #f8f9fa; }
        .card-history { border: none; border-radius: 10px; transition: 0.3s; }
        .card-history:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-5 shadow-sm">
        <div class="container">
            <a class="navbar-brand font-playfair" href="index.php">Veloré Hotel.</a>
            <div class="ms-auto text-white">
                <a href="index.php" class="btn btn-outline-light btn-sm rounded-0">Kembali ke Home</a>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="font-playfair">Riwayat Reservasi Anda</h2>
                <p class="text-muted">Pantau status pemesanan kamar Anda di sini.</p>
            </div>
        </div>

        <?php if(count($histories) > 0): ?>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <?php foreach($histories as $data): ?>
                        <div class="card card-history bg-white shadow-sm mb-4">
                            <div class="card-body p-4">
                                <div class="row align-items-center">
                                    
                                    <div class="col-md-2 text-center mb-3 mb-md-0">
                                        <div class="bg-light rounded-circle d-inline-flex justify-content-center align-items-center" style="width: 60px; height: 60px;">
                                            <i class="fas fa-bed text-muted fs-4"></i>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <h5 class="mb-1"><?= htmlspecialchars($data['room_type']) ?></h5>
                                        <small class="text-muted">Kode: <span class="text-dark fw-bold"><?= $data['booking_code'] ?></span></small>
                                    </div>

                                    <div class="col-md-3 mb-3 mb-md-0">
                                        <small class="d-block text-muted">Check-in: <?= date('d M Y', strtotime($data['checkin_date'])) ?></small>
                                        <small class="d-block text-muted">Durasi: <?= $data['nights'] ?> Malam</small>
                                        <span class="fw-bold text-primary">Rp <?= number_format($data['total_amount'], 0, ',', '.') ?></span>
                                    </div>

                                    <div class="col-md-3 text-md-end text-center">
                                        <?php 
                                            $status = $data['status'];
                                            $badgeColor = 'bg-secondary';
                                            if($status == 'confirmed') $badgeColor = 'bg-success';
                                            if($status == 'pending') $badgeColor = 'bg-warning text-dark';
                                            if($status == 'cancelled') $badgeColor = 'bg-danger';
                                        ?>
                                        <span class="badge status-badge <?= $badgeColor ?>"><?= $status ?></span>
                                        
                                        <?php if($status == 'confirmed'): ?>
                                            <div class="mt-2">
                                                <button class="btn btn-sm btn-outline-dark rounded-0" onclick="window.print()"><i class="fas fa-print"></i> Cetak</button>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <img src="https://cdn-icons-png.flaticon.com/512/2748/2748558.png" width="100" class="mb-3 opacity-50">
                <h4 class="text-muted">Belum ada riwayat pemesanan.</h4>
                <a href="index.php#rooms" class="btn btn-dark mt-3 rounded-0 px-4">Pesan Kamar Sekarang</a>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>