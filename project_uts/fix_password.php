<?php
require 'config.php';

$username = 'admin';
$new_password = 'admin123';
$hash = password_hash($new_password, PASSWORD_BCRYPT);

$stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE username = ?");
$stmt->execute([$hash, $username]);

echo "Password user 'admin' berhasil di-update menjadi hash aman. Silakan login sekarang.";
?>