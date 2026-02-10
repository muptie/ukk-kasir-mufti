<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

checkRole('admin');

// Get statistics
$total_produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM produk"))['total'];
$total_pelanggan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pelanggan"))['total'];
$total_petugas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM user WHERE Role = 'petugas'"))['total'];

// Penjualan hari ini
$today = date('Y-m-d');
$penjualan_hari_ini = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) as total FROM penjualan WHERE DATE(TanggalPenjualan) = '$today'"))['total'];

// Total omset (all time)
$total_omset = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT SUM(TotalHarga) as total FROM penjualan"))['total'] ?? 0;

// Total transaksi
$total_transaksi = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) as total FROM penjualan"))['total'];

// Produk stok menipis (< 10)
$produk_stok_menipis = mysqli_query($conn, 
    "SELECT * FROM produk WHERE Stok < 10 ORDER BY Stok ASC LIMIT 5");

// Transaksi terakhir
$transaksi_terakhir = mysqli_query($conn, 
    "SELECT p.*, pel.NamaPelanggan 
     FROM penjualan p 
     LEFT JOIN pelanggan pel ON p.PelangganID = pel.PelangganID 
     ORDER BY p.TanggalPenjualan DESC 
     LIMIT 10");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Kedai Muptie</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-brand">
                    <i class="fas fa-store"></i>
                    <span>Kedai Muptie</span>
                </div>
            </div>

            <nav class="sidebar-menu">
                <a href="/kedaimupti/admin/dashboard/" class="menu-item active">
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

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-header">
                <h1 class="page-title">Dashboard Admin</h1>
                <p class="page-subtitle">Selamat datang, <?= $_SESSION['username'] ?></p>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div>
                            <div class="stat-label">Total Produk</div>
                            <div class="stat-value"><?= $total_produk ?></div>
                        </div>
                        <div class="stat-card-icon blue">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <div>
                            <div class="stat-label">Penjualan Hari Ini</div>
                            <div class="stat-value"><?= $penjualan_hari_ini ?></div>
                        </div>
                        <div class="stat-card-icon green">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <div>
                            <div class="stat-label">Total Pelanggan</div>
                            <div class="stat-value"><?= $total_pelanggan ?></div>
                        </div>
                        <div class="stat-card-icon yellow">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <div>
                            <div class="stat-label">Total Petugas</div>
                            <div class="stat-value"><?= $total_petugas ?></div>
                        </div>
                        <div class="stat-card-icon red">
                            <i class="fas fa-user-tie"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Card -->
            <div class="content-card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line"></i>
                        Ringkasan Penjualan
                    </h3>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 24px;">
                    <div>
                        <div class="stat-label">Total Omset</div>
                        <div style="font-size: 28px; font-weight: 700; color: #10b981; margin-top: 8px;">
                            <?= formatRupiah($total_omset) ?>
                        </div>
                    </div>
                    <div>
                        <div class="stat-label">Total Transaksi</div>
                        <div style="font-size: 28px; font-weight: 700; color: #3b82f6; margin-top: 8px;">
                            <?= $total_transaksi ?> Transaksi
                        </div>
                    </div>
                </div>
            </div>

            <!-- Low Stock Alert -->
            <?php if (mysqli_num_rows($produk_stok_menipis) > 0): ?>
            <div class="content-card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-triangle" style="color: #f59e0b;"></i>
                        Stok Produk Menipis
                    </h3>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($produk = mysqli_fetch_assoc($produk_stok_menipis)): ?>
                            <tr>
                                <td><?= $produk['NamaProduk'] ?></td>
                                <td><?= formatRupiah($produk['Harga']) ?></td>
                                <td>
                                    <span class="badge badge-danger"><?= $produk['Stok'] ?></span>
                                </td>
                                <td>
                                    <a href="/kedaimupti/admin/produk/edit.php?id=<?= $produk['ProdukID'] ?>" 
                                       class="btn-icon btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <!-- Recent Transactions -->
            <div class="content-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clock"></i>
                        Transaksi Terakhir
                    </h3>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No. Nota</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($transaksi = mysqli_fetch_assoc($transaksi_terakhir)): ?>
                            <tr>
                                <td><strong>#<?= $transaksi['PenjualanID'] ?></strong></td>
                                <td><?= date('d/m/Y H:i', strtotime($transaksi['TanggalPenjualan'])) ?></td>
                                <td><?= $transaksi['NamaPelanggan'] ?? 'Umum' ?></td>
                                <td><strong><?= formatRupiah($transaksi['TotalHarga']) ?></strong></td>
                                <td>
                                    <a href="/kedaimupti/admin/penjualan/detail.php?id=<?= $transaksi['PenjualanID'] ?>" 
                                       class="btn-icon btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
