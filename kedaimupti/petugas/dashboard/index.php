<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

checkRole('petugas');

$today = date('Y-m-d');
$transaksi_hari_ini = mysqli_query($conn, 
    "SELECT p.*, pel.NamaPelanggan 
     FROM penjualan p 
     LEFT JOIN pelanggan pel ON p.PelangganID = pel.PelangganID 
     WHERE DATE(p.TanggalPenjualan) = '$today'
     ORDER BY p.TanggalPenjualan DESC");

$total_hari_ini = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT SUM(TotalHarga) as total FROM penjualan WHERE DATE(TanggalPenjualan) = '$today'"))['total'] ?? 0;

$jumlah_transaksi = mysqli_num_rows($transaksi_hari_ini);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <div class="sidebar-header"><div class="sidebar-brand">Kedaimupti</div></div>
            <nav class="sidebar-menu">
                <a href="/kedaimupti/petugas/dashboard/" class="menu-item active"><i class="fas fa-home"></i> Dashboard</a>
                <a href="/kedaimupti/petugas/penjualan/" class="menu-item"><i class="fas fa-shopping-cart"></i> Transaksi</a>
                <a href="/kedaimupti/petugas/produk/" class="menu-item"><i class="fas fa-box"></i> Lihat Produk</a>
                <a href="/kedaimupti/petugas/pelanggan/" class="menu-item"><i class="fas fa-users"></i> Data Pelanggan</a>
            </nav>
            <div class="sidebar-footer">
                <a href="/kedaimupti/auth/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Keluar</a>
            </div>
        </aside>

        <main class="main-content">
            <div class="content-header">
                <h1 class="page-title">Dashboard Kasir</h1>
                <p class="page-subtitle">Selamat bekerja, <?= $_SESSION['username'] ?></p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">Transaksi Hari Ini</div>
                    <div class="stat-value"><?= $jumlah_transaksi ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Total Penjualan</div>
                    <div class="stat-value" style="font-size: 24px;"><?= formatRupiah($total_hari_ini) ?></div>
                </div>
            </div>

            <div class="content-card">
                <div class="card-header">
                    <h3 class="card-title">Transaksi Hari Ini</h3>
                    <a href="../penjualan/" class="btn btn-primary btn-sm">Transaksi Baru</a>
                </div>
                
                <?php if ($jumlah_transaksi > 0): mysqli_data_seek($transaksi_hari_ini, 0); ?>
                <table>
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>No. Nota</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($t = mysqli_fetch_assoc($transaksi_hari_ini)): ?>
                        <tr>
                            <td><?= date('H:i', strtotime($t['TanggalPenjualan'])) ?></td>
                            <td>#<?= $t['PenjualanID'] ?></td>
                            <td><?= $t['NamaPelanggan'] ?? 'Umum' ?></td>
                            <td><?= formatRupiah($t['TotalHarga']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p style="text-align: center; padding: 40px; color: var(--gray);">Belum ada transaksi hari ini</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
