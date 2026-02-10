<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';
checkRole('petugas');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = escape($_POST['nama']);
    $telepon = escape($_POST['telepon']);
    $alamat = escape($_POST['alamat']);
    
    $check = mysqli_query($conn, "SELECT * FROM pelanggan WHERE NomorTelepon = '$telepon'");
    if (mysqli_num_rows($check) > 0) {
        setAlert('danger', 'Nomor telepon sudah terdaftar!');
    } else {
        $query = "INSERT INTO pelanggan (NamaPelanggan, Alamat, NomorTelepon) VALUES ('$nama', '$alamat', '$telepon')";
        if (mysqli_query($conn, $query)) {
            setAlert('success', 'Pelanggan berhasil ditambahkan!');
            redirect('/kedaimupti/petugas/pelanggan/');
        } else {
            setAlert('danger', 'Gagal menambahkan pelanggan!');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pelanggan</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <div class="sidebar-header"><div class="sidebar-brand">Kedaimupti</div></div>
            <nav class="sidebar-menu">
                <a href="/kedaimupti/petugas/dashboard/" class="menu-item"><i class="fas fa-home"></i> Dashboard</a>
                <a href="/kedaimupti/petugas/penjualan/" class="menu-item"><i class="fas fa-shopping-cart"></i> Transaksi</a><a href="/kedaimupti/petugas/produk/" class="menu-item"><i class="fas fa-box"></i> Lihat Produk</a>
                <a href="/kedaimupti/petugas/pelanggan/" class="menu-item"><i class="fas fa-users"></i> Data Pelanggan</a>
                <a href="/kedaimupti/petugas/pelanggan/" class="menu-item active"><i class="fas fa-users"></i> Data Pelanggan</a>
                
                
                
            </nav>
            <div class="sidebar-footer">
                <a href="/kedaimupti/auth/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Keluar</a>
            </div>
        </aside>
        <main class="main-content">
            <div class="content-header">
                <h1 class="page-title">Tambah Pelanggan</h1>
                <p class="page-subtitle">Tambah data pelanggan baru</p>
            </div>
            <div class="content-card" style="max-width: 600px;">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user-plus"></i> Form Tambah Pelanggan</h3>
                </div>
                <?php $alert = getAlert(); if ($alert): ?>
                <div class="alert alert-<?= $alert['type'] ?>">
                    <i class="fas fa-<?= $alert['type'] == 'danger' ? 'exclamation-circle' : 'check-circle' ?>"></i>
                    <span><?= $alert['message'] ?></span>
                </div>
                <?php endif; ?>
                <form method="POST">
                    <div class="form-group">
                        <label class="form-label">Nama Pelanggan</label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama lengkap" required autofocus style="padding-left: 16px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">No Telepon</label>
                        <input type="text" name="telepon" class="form-control" placeholder="08xxx" required style="padding-left: 16px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" placeholder="Alamat lengkap" rows="3" required style="padding-left: 16px;"></textarea>
                    </div>
                    <div style="display: flex; gap: 12px; margin-top: 24px;">
                        <button type="submit" class="btn btn-primary" style="flex: 1;"><i class="fas fa-save"></i> Simpan</button>
                        <a href="/kedaimupti/petugas/pelanggan/" class="btn btn-danger" style="flex: 1;"><i class="fas fa-times"></i> Batal</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
