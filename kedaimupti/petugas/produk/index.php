<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';
checkRole('petugas');
$produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY NamaProduk ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <div class="sidebar-header"><div class="sidebar-brand">Kedaimupti</div></div>
            <nav class="sidebar-menu">
                <a href="/kedaimupti/petugas/dashboard/" class="menu-item"><i class="fas fa-home"></i> Dashboard</a>
                <a href="/kedaimupti/petugas/penjualan/" class="menu-item"><i class="fas fa-shopping-cart"></i> Transaksi</a>
                <a href="/kedaimupti/petugas/produk/" class="menu-item active"><i class="fas fa-box"></i> Lihat Produk</a>
                <a href="/kedaimupti/petugas/pelanggan/" class="menu-item"><i class="fas fa-users"></i> Data Pelanggan</a>
            </nav>
            <div class="sidebar-footer">
                <a href="/kedaimupti/auth/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Keluar</a>
            </div>
        </aside>
        <main class="main-content">
            <div class="content-header">
                <h1 class="page-title">Data Produk</h1>
                <p class="page-subtitle">Lihat daftar produk</p>
            </div>
            <div class="content-card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-box"></i> Daftar Produk</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($p = mysqli_fetch_assoc($produk)): 
                            if ($p['Stok'] < 10) { $badge = 'badge-danger'; }
                            elseif ($p['Stok'] < 20) { $badge = 'badge-warning'; }
                            else { $badge = 'badge-success'; }
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><strong><?= $p['NamaProduk'] ?></strong></td>
                            <td><?= formatRupiah($p['Harga']) ?></td>
                            <td><span class="badge <?= $badge ?>"><?= $p['Stok'] ?> pcs</span></td>
                            <td>
                                <a href="edit_stok.php?id=<?= $p['ProdukID'] ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit Stok
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
