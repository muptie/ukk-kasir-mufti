<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

checkRole('admin');

$id = escape($_GET['id']);
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user WHERE UserID = '$id'"));

if (!$user) {
    setAlert('danger', 'User tidak ditemukan!');
    redirect('/kedaimupti/admin/petugas/');
}

// Prevent editing own account
if ($user['UserID'] == $_SESSION['user_id']) {
    setAlert('danger', 'Anda tidak bisa mengedit akun sendiri!');
    redirect('/kedaimupti/admin/petugas/');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = escape($_POST['username']);
    $password = escape($_POST['password']);
    $role = escape($_POST['role']);
    
    // Update dengan atau tanpa password baru
    if (!empty($password)) {
        $query = "UPDATE user SET Username = '$username', Password = '$password', Role = '$role' WHERE UserID = '$id'";
    } else {
        $query = "UPDATE user SET Username = '$username', Role = '$role' WHERE UserID = '$id'";
    }
    
    if (mysqli_query($conn, $query)) {
        setAlert('success', 'User berhasil diupdate!');
        redirect('/kedaimupti/admin/petugas/');
    } else {
        setAlert('danger', 'Gagal mengupdate user!');
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Kedai Muptie</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-brand">
                    <i class="fas fa-store"></i>
                    <span>Kedai Muptie</span>
                </div>
            </div>
            <nav class="sidebar-menu">
                <a href="/kedaimupti/admin/dashboard/" class="menu-item">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="/kedaimupti/admin/penjualan/" class="menu-item">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Penjualan</span>
                </a>
                <a href="/kedaimupti/admin/produk/" class="menu-item">
                    <i class="fas fa-box"></i>
                    <span>Data Produk</span>
                </a>
                <a href="/kedaimupti/admin/pelanggan/" class="menu-item">
                    <i class="fas fa-users"></i>
                    <span>Data Pelanggan</span>
                </a>
                <a href="/kedaimupti/admin/petugas/" class="menu-item active">
                    <i class="fas fa-user-tie"></i>
                    <span>Manajemen User</span>
                </a>
                <a href="/kedaimupti/admin/laporan/" class="menu-item">
                    <i class="fas fa-file-invoice"></i>
                    <span>Laporan</span>
                </a>
            </nav>
            <div class="sidebar-footer">
                <a href="/kedaimupti/auth/logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Keluar</span>
                </a>
            </div>
        </aside>

        <main class="main-content">
            <div class="content-header">
                <h1 class="page-title">Edit User</h1>
                <p class="page-subtitle">Perbarui informasi user</p>
            </div>

            <div class="content-card" style="max-width: 600px;">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-edit"></i>
                        Form Edit User
                    </h3>
                </div>

                <form method="POST" action="">
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="<?= $user['Username'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                        <small style="color: #6b7280; font-size: 12px;">
                            <i class="fas fa-info-circle"></i> Kosongkan jika tidak ingin mengubah password
                        </small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-control" required>
                            <option value="admin" <?= $user['Role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="petugas" <?= $user['Role'] == 'petugas' ? 'selected' : '' ?>>Petugas</option>
                        </select>
                    </div>

                    <div style="display: flex; gap: 12px; margin-top: 32px;">
                        <button type="submit" class="btn btn-primary" style="flex: 1;">
                            <i class="fas fa-save"></i> Update User
                        </button>
                        <a href="/kedaimupti/admin/petugas/" class="btn" style="flex: 1; background: #e5e7eb; color: #1f2937;">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
