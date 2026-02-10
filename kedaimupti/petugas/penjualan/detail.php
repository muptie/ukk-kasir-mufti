<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

checkRole('petugas');

$id = escape($_GET['id']);

// Get transaction details
$penjualan = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT p.*, pel.NamaPelanggan 
     FROM penjualan p 
     LEFT JOIN pelanggan pel ON p.PelangganID = pel.PelangganID 
     WHERE p.PenjualanID = '$id'"));

if (!$penjualan) {
    setAlert('danger', 'Transaksi tidak ditemukan!');
    redirect('/kedaimupti/petugas/dashboard/');
}

// Get transaction items
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
    <style>
        .receipt {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 4px 12px var(--shadow);
        }
        
        .receipt-header {
            text-align: center;
            border-bottom: 2px dashed var(--border);
            padding-bottom: 24px;
            margin-bottom: 24px;
        }
        
        .receipt-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .receipt-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 24px;
            font-size: 14px;
        }
        
        .receipt-items {
            margin-bottom: 24px;
        }
        
        .receipt-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid var(--light);
        }
        
        .receipt-footer {
            border-top: 2px dashed var(--border);
            padding-top: 24px;
            margin-top: 24px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
        }
        
        @media print {
            body * {
                visibility: hidden;
            }
            .receipt, .receipt * {
                visibility: visible;
            }
            .receipt {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-wrapper">
        <aside class="sidebar no-print">
            <div class="sidebar-header">
                <div class="sidebar-brand">
                    <i class="fas fa-store"></i>
                    <span>Kedai Muptie</span>
                </div>
            </div>
<nav class="sidebar-menu">
                <a href="/kedaimupti/petugas/dashboard/" class="menu-item"><i class="fas fa-home"></i> Dashboard</a>
                <a href="/kedaimupti/petugas/penjualan/" class="menu-item active"><i class="fas fa-shopping-cart"></i> Transaksi</a>
                <a href="/kedaimupti/petugas/produk/" class="menu-item"><i class="fas fa-box"></i> Lihat Produk</a>
                <a href="/kedaimupti/petugas/pelanggan/" class="menu-item"><i class="fas fa-users"></i> Data Pelanggan</a>
            </nav>
            <div class="sidebar-footer">
                <a href="/kedaimupti/auth/logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Keluar</span>
                </a>
            </div>
        </aside>

        <main class="main-content">
            <div class="content-header no-print">
                <h1 class="page-title">Detail Transaksi</h1>
                <p class="page-subtitle">Struk pembayaran</p>
            </div>

            <?php 
            $alert = getAlert();
            if ($alert): 
            ?>
            <div class="alert alert-<?= $alert['type'] ?> no-print" style="margin-bottom: 24px;">
                <i class="fas fa-check-circle"></i>
                <span><?= $alert['message'] ?></span>
            </div>
            <?php endif; ?>

            <div class="receipt">
                <div class="receipt-header">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-radius: 16px; margin: 0 auto 16px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-store" style="font-size: 40px; color: white;"></i>
                    </div>
                    <div class="receipt-title">Kedai Muptie</div>
                    <p style="color: var(--gray); margin: 0;">Sistem Kasir Pintar & Mudah</p>
                </div>

                <div class="receipt-info">
                    <div>
                        <strong>No. Nota:</strong> #<?= $penjualan['PenjualanID'] ?><br>
                        <strong>Tanggal:</strong> <?= date('d/m/Y H:i', strtotime($penjualan['TanggalPenjualan'])) ?>
                    </div>
                    <div style="text-align: right;">
                        <strong>Pelanggan:</strong><br>
                        <?= $penjualan['NamaPelanggan'] ?? 'Umum' ?>
                    </div>
                </div>

                <div class="receipt-items">
                    <div style="font-weight: 700; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 2px solid var(--border);">
                        Daftar Pembelian
                    </div>
                    <?php while ($item = mysqli_fetch_assoc($items)): ?>
                    <div class="receipt-item">
                        <div>
                            <div style="font-weight: 600;"><?= $item['NamaProduk'] ?></div>
                            <div style="font-size: 13px; color: var(--gray);">
                                <?= $item['JumlahProduk'] ?> × <?= formatRupiah($item['Subtotal'] / $item['JumlahProduk']) ?>
                            </div>
                        </div>
                        <div style="font-weight: 700;">
                            <?= formatRupiah($item['Subtotal']) ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>

                <div class="receipt-footer">
                    <div class="total-row">
                        <span>TOTAL BAYAR</span>
                        <span><?= formatRupiah($penjualan['TotalHarga']) ?></span>
                    </div>
                    <div style="text-align: center; margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--border); color: var(--gray); font-size: 14px;">
                        Terima kasih atas kunjungan Anda!<br>
                        <strong>Kedai Muptie</strong> - © 2026
                    </div>
                </div>
            </div>

            <div class="no-print" style="max-width: 600px; margin: 24px auto; display: flex; gap: 12px;">
                <button onclick="window.print()" class="btn btn-primary" style="flex: 1;">
                    <i class="fas fa-print"></i> Cetak Struk
                </button>
                <a href="/kedaimupti/petugas/penjualan/" class="btn btn-success" style="flex: 1; text-align: center; text-decoration: none;">
                    <i class="fas fa-plus"></i> Transaksi Baru
                </a>
                <a href="/kedaimupti/petugas/dashboard/" class="btn" style="flex: 1; background: #e5e7eb; color: #1f2937; text-align: center; text-decoration: none;">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </div>
        </main>
    </div>
</body>
</html>
