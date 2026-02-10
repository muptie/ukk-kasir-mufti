<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

checkRole('admin');

$id = escape($_GET['id']);

$penjualan = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT p.*, pel.NamaPelanggan 
     FROM penjualan p 
     LEFT JOIN pelanggan pel ON p.PelangganID = pel.PelangganID 
     WHERE p.PenjualanID = '$id'"));

if (!$penjualan) {
    redirect('/kedaimupti/admin/penjualan/');
}

$items = mysqli_query($conn, 
    "SELECT d.*, pr.NamaProduk 
     FROM detailpenjualan d 
     JOIN produk pr ON d.ProdukID = pr.ProdukID 
     WHERE d.PenjualanID = '$id'");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi - Kedai Muptie</title>
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
                <h1 class="page-title">Detail Transaksi #<?= $penjualan['PenjualanID'] ?></h1>
                <p class="page-subtitle">Informasi lengkap transaksi</p>
            </div>

            <div class="content-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-receipt"></i>
                        Informasi Transaksi
                    </h3>
                    <a href="/kedaimupti/admin/penjualan/" class="btn btn-sm" style="background: #e5e7eb; color: #1f2937;">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 32px;">
                    <div>
                        <div class="stat-label">No. Nota</div>
                        <div style="font-size: 24px; font-weight: 700; color: var(--primary); margin-top: 8px;">
                            #<?= $penjualan['PenjualanID'] ?>
                        </div>
                    </div>
                    <div>
                        <div class="stat-label">Tanggal Transaksi</div>
                        <div style="font-size: 20px; font-weight: 600; margin-top: 8px;">
                            <?= date('d F Y, H:i', strtotime($penjualan['TanggalPenjualan'])) ?>
                        </div>
                    </div>
                    <div>
                        <div class="stat-label">Pelanggan</div>
                        <div style="font-size: 20px; font-weight: 600; margin-top: 8px;">
                            <?= $penjualan['NamaPelanggan'] ?? 'Umum' ?>
                        </div>
                    </div>
                    <div>
                        <div class="stat-label">Total Bayar</div>
                        <div style="font-size: 24px; font-weight: 700; color: var(--secondary); margin-top: 8px;">
                            <?= formatRupiah($penjualan['TotalHarga']) ?>
                        </div>
                    </div>
                </div>

                <div class="stat-label" style="margin-bottom: 12px;">Detail Pembelian</div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Harga Satuan</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while ($item = mysqli_fetch_assoc($items)): 
                                $harga_satuan = $item['Subtotal'] / $item['JumlahProduk'];
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= $item['NamaProduk'] ?></strong></td>
                                <td><?= formatRupiah($harga_satuan) ?></td>
                                <td><?= $item['JumlahProduk'] ?></td>
                                <td><strong><?= formatRupiah($item['Subtotal']) ?></strong></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot>
                            <tr style="background: var(--light); font-weight: 700;">
                                <td colspan="4" style="text-align: right; padding: 16px;">TOTAL:</td>
                                <td style="padding: 16px; font-size: 18px; color: var(--primary);">
                                    <?= formatRupiah($penjualan['TotalHarga']) ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
