<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

checkRole('admin');

$penjualan = mysqli_query($conn, 
    "SELECT p.*, pel.NamaPelanggan 
     FROM penjualan p 
     LEFT JOIN pelanggan pel ON p.PelangganID = pel.PelangganID 
     ORDER BY p.TanggalPenjualan DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penjualan - Kedai Muptie</title>
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
                <a href="/kedaimupti/admin/penjualan/" class="menu-item active">
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

        <main class="main-content">
            <div class="content-header">
                <h1 class="page-title">Riwayat Penjualan</h1>
                <p class="page-subtitle">Semua transaksi penjualan</p>
            </div>

            <div class="content-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history"></i>
                        Semua Transaksi
                    </h3>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No. Nota</th>
                                <th>Tanggal & Waktu</th>
                                <th>Pelanggan</th>
                                <th>Total Bayar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($p = mysqli_fetch_assoc($penjualan)): ?>
                            <tr>
                                <td><strong>#<?= $p['PenjualanID'] ?></strong></td>
                                <td><?= date('d/m/Y H:i', strtotime($p['TanggalPenjualan'])) ?></td>
                                <td><?= $p['NamaPelanggan'] ?? 'Umum' ?></td>
                                <td><strong><?= formatRupiah($p['TotalHarga']) ?></strong></td>
                                <td>
                                    <a href="detail.php?id=<?= $p['PenjualanID'] ?>" 
                                       class="btn-icon btn-info" title="Detail">
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
