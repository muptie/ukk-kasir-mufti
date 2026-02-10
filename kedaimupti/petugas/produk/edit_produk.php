<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

checkRole('petugas');

$id = escape($_GET['id']);
$produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM produk WHERE ProdukID = '$id'"));

if (!$produk) {
    setAlert('danger', 'Produk tidak ditemukan!');
    redirect('/kedaimupti/petugas/produk/');
}

// Proses update stok
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stok_baru = (int)$_POST['stok'];
    
    $query = "UPDATE produk SET Stok = '$stok_baru' WHERE ProdukID = '$id'";
    
    if (mysqli_query($conn, $query)) {
        setAlert('success', 'Stok berhasil diupdate!');
        redirect('/kedaimupti/petugas/produk/');
    } else {
        setAlert('danger', 'Gagal mengupdate stok!');
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Stok - Kedaimupti</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-brand">Kedaimupti</div>
            </div>
            <nav class="sidebar-menu">
                <a href="/kedaimupti/petugas/dashboard/" class="menu-item">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="/kedaimupti/petugas/penjualan/" class="menu-item">
                    <i class="fas fa-shopping-cart"></i> Transaksi
                </a>
                <a href="/kedaimupti/petugas/produk/" class="menu-item active">
                <a href="/kedaimupti/petugas/pelanggan/" class="menu-item"><i class="fas fa-users"></i> Data Pelanggan</a>
                    <i class="fas fa-box"></i> Lihat Produk
                </a>
            </nav>
            <div class="sidebar-footer">
                <a href="/kedaimupti/auth/logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </a>
            </div>
        </aside>

        <main class="main-content">
            <div class="content-header">
                <h1 class="page-title">Edit Stok Produk</h1>
                <p class="page-subtitle">Update stok barang</p>
            </div>

            <div class="content-card" style="max-width: 600px;">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit"></i> Form Edit Stok
                    </h3>
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
                        <label class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" value="<?= $produk['NamaProduk'] ?>" disabled style="padding-left: 16px; background: #f5f5f5;">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Harga</label>
                        <input type="text" class="form-control" value="<?= formatRupiah($produk['Harga']) ?>" disabled style="padding-left: 16px; background: #f5f5f5;">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Stok Saat Ini</label>
                        <input type="number" class="form-control" value="<?= $produk['Stok'] ?>" disabled style="padding-left: 16px; background: #f5f5f5;">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Stok Baru <span style="color: red;">*</span></label>
                        <input type="number" name="stok" class="form-control" value="<?= $produk['Stok'] ?>" required min="0" style="padding-left: 16px;" autofocus>
                        <small style="color: #6c757d; font-size: 12px; display: block; margin-top: 5px;">
                            <i class="fas fa-info-circle"></i> Masukkan jumlah stok yang baru
                        </small>
                    </div>

                    <div style="display: flex; gap: 12px; margin-top: 32px;">
                        <button type="submit" class="btn btn-primary" style="flex: 1;">
                            <i class="fas fa-save"></i> Update Stok
                        </button>
                        <a href="/kedaimupti/petugas/produk/" class="btn btn-danger" style="flex: 1;">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html><?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

checkRole('petugas');

$id = escape($_GET['id']);
$produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM produk WHERE ProdukID = '$id'"));

if (!$produk) {
    setAlert('danger', 'Produk tidak ditemukan!');
    redirect('/kedaimupti/petugas/produk/');
}

// Proses update stok
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stok_baru = (int)$_POST['stok'];
    
    $query = "UPDATE produk SET Stok = '$stok_baru' WHERE ProdukID = '$id'";
    
    if (mysqli_query($conn, $query)) {
        setAlert('success', 'Stok berhasil diupdate!');
        redirect('/kedaimupti/petugas/produk/');
    } else {
        setAlert('danger', 'Gagal mengupdate stok!');
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Stok - Kedaimupti</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-brand">Kedaimupti</div>
            </div>
            <nav class="sidebar-menu">
                <a href="/kedaimupti/petugas/dashboard/" class="menu-item">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="/kedaimupti/petugas/penjualan/" class="menu-item">
                    <i class="fas fa-shopping-cart"></i> Transaksi
                </a>
                <a href="/kedaimupti/petugas/produk/" class="menu-item active">
                <a href="/kedaimupti/petugas/pelanggan/" class="menu-item"><i class="fas fa-users"></i> Data Pelanggan</a>
                    <i class="fas fa-box"></i> Lihat Produk
                </a>
            </nav>
            <div class="sidebar-footer">
                <a href="/kedaimupti/auth/logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </a>
            </div>
        </aside>

        <main class="main-content">
            <div class="content-header">
                <h1 class="page-title">Edit Stok Produk</h1>
                <p class="page-subtitle">Update stok barang</p>
            </div>

            <div class="content-card" style="max-width: 600px;">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit"></i> Form Edit Stok
                    </h3>
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
                        <label class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" value="<?= $produk['NamaProduk'] ?>" disabled style="padding-left: 16px; background: #f5f5f5;">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Harga</label>
                        <input type="text" class="form-control" value="<?= formatRupiah($produk['Harga']) ?>" disabled style="padding-left: 16px; background: #f5f5f5;">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Stok Saat Ini</label>
                        <input type="number" class="form-control" value="<?= $produk['Stok'] ?>" disabled style="padding-left: 16px; background: #f5f5f5;">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Stok Baru <span style="color: red;">*</span></label>
                        <input type="number" name="stok" class="form-control" value="<?= $produk['Stok'] ?>" required min="0" style="padding-left: 16px;" autofocus>
                        <small style="color: #6c757d; font-size: 12px; display: block; margin-top: 5px;">
                            <i class="fas fa-info-circle"></i> Masukkan jumlah stok yang baru
                        </small>
                    </div>

                    <div style="display: flex; gap: 12px; margin-top: 32px;">
                        <button type="submit" class="btn btn-primary" style="flex: 1;">
                            <i class="fas fa-save"></i> Update Stok
                        </button>
                        <a href="/kedaimupti/petugas/produk/" class="btn btn-danger" style="flex: 1;">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>