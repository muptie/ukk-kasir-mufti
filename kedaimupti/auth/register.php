<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Jika sudah login, redirect ke dashboard
if (isLoggedIn()) {
    if ($_SESSION['role'] == 'admin') {
        redirect('/kedaimupti/admin/dashboard/');
    } else {
        redirect('/kedaimupti/petugas/dashboard/');
    }
}

// Proses registrasi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = escape($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = escape($_POST['role']);
    
    // Validasi
    if ($password !== $confirm_password) {
        setAlert('danger', 'Password dan konfirmasi password tidak cocok!');
    } else {
        // Cek username sudah ada atau belum
        $check = mysqli_query($conn, "SELECT * FROM user WHERE Username = '$username'");
        
        if (mysqli_num_rows($check) > 0) {
            setAlert('danger', 'Username sudah digunakan!');
        } else {
            // Insert user baru dengan role yang dipilih
            $query = "INSERT INTO user (Username, Password, Role) VALUES ('$username', '$password', '$role')";
            
            if (mysqli_query($conn, $query)) {
                setAlert('success', 'Registrasi berhasil! Silakan login.');
                redirect('/kedaimupti/auth/login.php');
            } else {
                setAlert('danger', 'Registrasi gagal! Silakan coba lagi.');
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Kedai Muptie</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h1 class="login-title">Daftar Akun Baru</h1>
                <p class="login-subtitle">Bergabung dengan Kedai Muptie</p>
            </div>

            <?php 
            $alert = getAlert();
            if ($alert): 
            ?>
            <div class="alert alert-<?= $alert['type'] ?>">
                <i class="fas fa-<?= $alert['type'] == 'danger' ? 'exclamation-circle' : 'check-circle' ?>"></i>
                <span><?= $alert['message'] ?></span>
            </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Pilih username" required autofocus style="padding-left: 16px;">
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Buat password" required minlength="6" style="padding-left: 16px;">
                    <small style="color: #6b7280; font-size: 12px;">Minimal 6 karakter</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Ulangi password" required minlength="6" style="padding-left: 16px;">
                </div>

                <div class="form-group">
                    <label class="form-label">Daftar Sebagai</label>
                    <select name="role" class="form-control" required style="padding-left: 16px;">
                        <option value="">Pilih Role</option>
                        <option value="admin">Admin</option>
                        <option value="petugas">Petugas/Kasir</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    Daftar Sekarang
                </button>
            </form>

            <div style="text-align: center; margin-top: 24px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                <p style="color: #6b7280; font-size: 14px; margin-bottom: 12px;">
                    Sudah punya akun?
                </p>
                <a href="login.php" style="color: #ff6b35; font-weight: 600; text-decoration: none; font-size: 15px;">
                    Login Di Sini
                </a>
            </div>

            <div class="login-footer">
                © 2026 Kedai Muptie • ALL RIGHTS RESERVED
            </div>
        </div>
    </div>
</body>
</html>
