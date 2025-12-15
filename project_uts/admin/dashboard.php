<?php
require '../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = ?");
        $stmt->execute([$id]);
        echo "<script>alert('Data berhasil dihapus!'); window.location='dashboard.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Gagal menghapus data: " . $e->getMessage() . "');</script>";
    }
}

if (isset($_POST['update_status'])) {
    $id = $_POST['booking_id'];
    $status = $_POST['status'];
    
    try {
        $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        echo "<script>alert('Status berhasil diperbarui!'); window.location='dashboard.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Gagal update status: " . $e->getMessage() . "');</script>";
    }
}

$stmt = $pdo->query("SELECT b.*, c.full_name, r.type as room_type 
                     FROM bookings b 
                     JOIN customers c ON b.customer_id = c.id 
                     JOIN rooms r ON b.room_id = r.id 
                     ORDER BY b.created_at DESC");
$bookings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Admin - Veloré</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
    
    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">Veloré Admin</a>
            <span class="navbar-text text-white">
                Halo, <strong><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></strong> | 
                <a href="../logout.php" class="text-danger text-decoration-none ms-2">Logout</a>
            </span>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Daftar Reservasi Masuk</h2>
            <button class="btn btn-outline-secondary btn-sm" onclick="window.location.reload();">
                <i class="fas fa-sync-alt"></i> Refresh Data
            </button>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Kode Booking</th>
                                <th>Tamu</th>
                                <th>Kamar</th>
                                <th>Check-in/Out</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th> </tr>
                        </thead>
                        <tbody>
                            <?php foreach($bookings as $row): ?>
                            <tr>
                                <td class="fw-bold"><?= $row['booking_code'] ?></td>
                                <td><?= $row['full_name'] ?></td>
                                <td><?= $row['room_type'] ?></td>
                                <td>
                                    <small class="d-block text-muted">In: <?= $row['checkin_date'] ?></small>
                                    <small class="d-block text-muted">Out: <?= $row['checkout_date'] ?></small>
                                </td>
                                <td>Rp <?= number_format($row['total_amount']) ?></td>
                                <td>
                                    <?php 
                                        $badge = 'bg-secondary';
                                        if($row['status']=='confirmed') $badge='bg-success';
                                        if($row['status']=='pending') $badge='bg-warning text-dark';
                                        if($row['status']=='cancelled') $badge='bg-danger';
                                    ?>
                                    <span class="badge <?= $badge ?>"><?= strtoupper($row['status']) ?></span>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    
                                    <a href="?delete_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data booking ini? Data yang dihapus tidak bisa dikembalikan.')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Status Booking: <strong><?= $row['booking_code'] ?></strong></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="booking_id" value="<?= $row['id'] ?>">
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Update Status Reservasi</label>
                                                    <select name="status" class="form-select">
                                                        <option value="pending" <?= $row['status'] == 'pending' ? 'selected' : '' ?>>PENDING (Menunggu)</option>
                                                        <option value="confirmed" <?= $row['status'] == 'confirmed' ? 'selected' : '' ?>>CONFIRMED (Dikonfirmasi)</option>
                                                        <option value="checked_in" <?= $row['status'] == 'checked_in' ? 'selected' : '' ?>>CHECKED IN (Masuk)</option>
                                                        <option value="completed" <?= $row['status'] == 'completed' ? 'selected' : '' ?>>COMPLETED (Selesai)</option>
                                                        <option value="cancelled" <?= $row['status'] == 'cancelled' ? 'selected' : '' ?>>CANCELLED (Dibatalkan)</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" name="update_status" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>