<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

checkRole('admin');

// Handle filter
$dari_tanggal = isset($_GET['dari']) ? $_GET['dari'] : date('Y-m-01');
$sampai_tanggal = isset($_GET['sampai']) ? $_GET['sampai'] : date('Y-m-d');

// Get laporan data
$laporan = mysqli_query($conn, 
    "SELECT p.*, pel.NamaPelanggan 
     FROM penjualan p 
     LEFT JOIN pelanggan pel ON p.PelangganID = pel.PelangganID 
     WHERE DATE(p.TanggalPenjualan) BETWEEN '$dari_tanggal' AND '$sampai_tanggal'
     ORDER BY p.TanggalPenjualan DESC");

$total_omset = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT SUM(TotalHarga) as total FROM penjualan 
     WHERE DATE(TanggalPenjualan) BETWEEN '$dari_tanggal' AND '$sampai_tanggal'"))['total'] ?? 0;

$jumlah_transaksi = mysqli_num_rows($laporan);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - Kedai Muptie</title>
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
                <a href="/kedaimupti/admin/petugas/" class="menu-item">
                    <i class="fas fa-user-tie"></i>
                    <span>Manajemen User</span>
                </a>
                <a href="/kedaimupti/admin/laporan/" class="menu-item active">
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
                <h1 class="page-title">Laporan Penjualan</h1>
                <p class="page-subtitle">Pantau performa penjualan</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div>
                            <div class="stat-label">Total Omset</div>
                            <div class="stat-value" style="font-size: 28px;">
                                <?= formatRupiah($total_omset) ?>
                            </div>
                        </div>
                        <div class="stat-card-icon green">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <div>
                            <div class="stat-label">Jumlah Transaksi</div>
                            <div class="stat-value"><?= $jumlah_transaksi ?></div>
                        </div>
                        <div class="stat-card-icon blue">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list"></i>
                        Daftar Transaksi
                    </h3>
                    <button onclick="window.print()" class="btn btn-success btn-sm">
                        <i class="fas fa-print"></i> Cetak Laporan
                    </button>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>No. Nota</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($laporan)): 
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($row['TanggalPenjualan'])) ?></td>
                                <td><strong>#<?= $row['PenjualanID'] ?></strong></td>
                                <td><?= $row['NamaPelanggan'] ?? 'Umum' ?></td>
                                <td><strong><?= formatRupiah($row['TotalHarga']) ?></strong></td>
                                <td>
                                    <a href="/kedaimupti/admin/penjualan/detail.php?id=<?= $row['PenjualanID'] ?>" 
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
