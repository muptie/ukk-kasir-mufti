<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

checkRole('admin');

$id = escape($_GET['id']);
$produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM produk WHERE ProdukID = '$id'"));

if (!$produk) {
    setAlert('danger', 'Produk tidak ditemukan!');
    redirect('/kedaimupti/admin/produk/');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = escape($_POST['nama']);
    $harga = escape($_POST['harga']);
    $stok = escape($_POST['stok']);
    
    $query = "UPDATE produk SET NamaProduk = '$nama', Harga = '$harga', Stok = '$stok' WHERE ProdukID = '$id'";
    
    if (mysqli_query($conn, $query)) {
        setAlert('success', 'Produk berhasil diupdate!');
        redirect('/kedaimupti/admin/produk/');
    } else {
        setAlert('danger', 'Gagal mengupdate produk!');
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Kedai Muptie</title>
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
                <a href="/kedaimupti/admin/produk/" class="menu-item active">
                    <i class="fas fa-box"></i>
                    <span>Data Produk</span>
                </a>
                <a href="/kedaimupti/admin/pelanggan/" class="menu-item">
                    <i class="fas fa-users"></i>
                    <span>Data Pelanggan</span>
                </a>
                <a href="/kedaimupti/admin/petugas/" class="menu-item">
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
                <h1 class="page-title">Edit Produk</h1>
                <p class="page-subtitle">Perbarui informasi produk</p>
            </div>

            <div class="content-card" style="max-width: 600px;">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit"></i>
                        Form Edit Produk
                    </h3>
                </div>

                <form method="POST" action="">
                    <div class="form-group">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="nama" class="form-control" value="<?= $produk['NamaProduk'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Harga</label>
                        <input type="number" name="harga" class="form-control" value="<?= $produk['Harga'] ?>" min="0" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" class="form-control" value="<?= $produk['Stok'] ?>" min="0" required>
                    </div>

                    <div style="display: flex; gap: 12px; margin-top: 32px;">
                        <button type="submit" class="btn btn-primary" style="flex: 1;">
                            <i class="fas fa-save"></i> Update Produk
                        </button>
                        <a href="/kedaimupti/admin/produk/" class="btn" style="flex: 1; background: #e5e7eb; color: #1f2937;">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
