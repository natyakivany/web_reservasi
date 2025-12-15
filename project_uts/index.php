<?php
require 'config.php';

$stmt = $pdo->query("SELECT * FROM rooms WHERE status = 'available'");
$rooms = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veloré Hotel - Luxury Stay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

   <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">Veloré Hotel.</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    
                    <li class="nav-item"><a class="nav-link" href="#rooms">Kamar</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">Tentang Kami</a></li>

                    <?php if(isset($_SESSION['customer_id'])): ?>
                        <li class="nav-item dropdown ms-3">
                            <a class="nav-link dropdown-toggle btn btn-outline-dark border-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i> Hai, <?= htmlspecialchars(explode(' ', $_SESSION['customer_name'])[0]) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li><a class="dropdown-item" href="history.php">Riwayat Booking</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="logout_member.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item ms-3">
                            <a class="btn rounded-0 text-white px-4 py-2 fw-bold" href="login_member.php" 
                               style="background-color: #c5a880; border: none; transition: 0.3s;">
                               Masuk / Daftar
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item ms-3">
                            <a class="btn btn-danger btn-sm rounded-0" href="logout.php">Logout Staff</a>
                        </li>
                    <?php elseif(!isset($_SESSION['customer_id'])): ?>
                        <li class="nav-item ms-2 border-start ps-2">
                            <a class="nav-link small text-muted" href="login.php" style="font-size: 0.75rem;">Staff Only</a>
                        </li>
                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="display-3 mb-4">Experience Luxury</h1>
            <p class="lead mb-5">Temukan ketenangan di jantung kota dengan pelayanan kelas dunia.</p>
            <a href="#rooms" class="btn btn-custom">Lihat Kamar</a>
        </div>
    </header>
                    
    <section id="rooms" class="py-5">
        <div class="container">
            <div class="text-center mb-5 mt-5">
                <h2>Pilihan Kamar Kami</h2>
                <p class="text-muted">Desain elegan untuk kenyamanan maksimal Anda</p>
            </div>

            <div class="row">
                <?php if(count($rooms) > 0): ?>
                    <?php foreach($rooms as $room): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card room-card border-0 shadow-sm h-100">
                            <img src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=600&q=80" class="card-img-top" alt="Room Image">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= htmlspecialchars($room['type']) ?></h5>
                                <p class="card-text text-muted"><?= htmlspecialchars(substr($room['description'], 0, 100)) ?>...</p>
                                <p class="h5 mb-3">Rp <?= number_format($room['price'], 0, ',', '.') ?> / malam</p>
                                <a href="book.php?room_id=<?= $room['id'] ?>" class="btn btn-outline-dark btn-sm rounded-0 px-4">BOOK NOW</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p>Belum ada kamar tersedia. Silahkan login admin untuk menambah data.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="about" class="py-5 bg-white">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                             alt="Hotel Interior" class="img-fluid shadow-sm" style="height: 500px; object-fit: cover; width: 100%;">
                        
                        <div style="position: absolute; bottom: -20px; right: -20px; width: 150px; height: 150px; background-color: #c5a880; z-index: -1;"></div>
                    </div>
                </div>

                <div class="col-md-6 ps-md-5">
                    <h5 class="text-uppercase text-muted letter-spacing-2 mb-3" style="letter-spacing: 3px; font-size: 0.9rem;">Cerita Kami</h5>
                    <h2 class="display-5 font-playfair mb-4">Tradisi Kemewahan Sejak 2010</h2>
                    <p class="text-muted mb-4" style="line-height: 1.8;">
                        Veloré Hotel hadir sebagai oase ketenangan di tengah hiruk pikuk kota. Kami memadukan arsitektur modern dengan sentuhan keramahan tradisional yang hangat.
                    </p>
                    <p class="text-muted mb-4" style="line-height: 1.8;">
                        Setiap sudut hotel kami dirancang untuk memberikan pengalaman visual yang memanjakan mata dan kenyamanan yang tak tertandingi. Dari lobi yang megah hingga sprei katun Mesir di kamar Anda, detail adalah segalanya bagi kami.
                    </p>
                    
                    <div class="row mt-4">
                        <div class="col-6">
                            <h3 class="font-playfair">15+</h3>
                            <p class="small text-uppercase text-muted">Tahun Pengalaman</p>
                        </div>
                        <div class="col-6">
                            <h3 class="font-playfair">50k+</h3>
                            <p class="small text-uppercase text-muted">Tamu Bahagia</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer text-center">
        <div class="container">
            <p>&copy; 2025 Veloré Hotel. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>