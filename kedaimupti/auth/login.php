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

// Proses login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = escape($_POST['username']);
    $password = $_POST['password'];
    
    $query = "SELECT * FROM user WHERE Username = '$username'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Verifikasi password (plaintext untuk sementara, sebaiknya gunakan password_hash)
        if ($password == $user['Password']) {
            $_SESSION['user_id'] = $user['UserID'];
            $_SESSION['username'] = $user['Username'];
            $_SESSION['role'] = $user['Role'];
            
            if ($user['Role'] == 'admin') {
                redirect('/kedaimupti/admin/dashboard/');
            } else {
                redirect('/kedaimupti/petugas/dashboard/');
            }
        } else {
            setAlert('danger', 'Password salah!');
        }
    } else {
        setAlert('danger', 'Username tidak ditemukan!');
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kedai Muptie</title>
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
                    <i class="fas fa-basket-shopping"></i>
                </div>
                <h1 class="login-title">Kedai Muptie</h1>
                <p class="login-subtitle">Sistem Kasir Pintar & Mudah</p>
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
                    <div class="input-group">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Masuk Sekarang
                </button>
            </form>

            <div style="text-align: center; margin-top: 24px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                <p style="color: #6b7280; font-size: 14px; margin-bottom: 12px;">
                    Belum punya akun?
                </p>
                <a href="register.php" style="color: #ff6b35; font-weight: 600; text-decoration: none; font-size: 15px;">
                    Daftar Sekarang
                </a>
            </div>

            <div class="login-footer">
                © 2026 Kedai Muptie • ALL RIGHTS RESERVED
            </div>
        </div>
    </div>
</body>
</html>
