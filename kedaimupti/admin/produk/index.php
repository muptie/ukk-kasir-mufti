<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

checkRole('admin');

// Handle delete
if (isset($_GET['delete'])) {
    $id = escape($_GET['delete']);
    mysqli_query($conn, "DELETE FROM produk WHERE ProdukID = '$id'");
    setAlert('success', 'Produk berhasil dihapus!');
    redirect('/kedaimupti/admin/produk/');
}

// Get all products
$produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY NamaProduk ASC");
$total_inventaris = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT SUM(Harga * Stok) as total FROM produk"))['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk - Kedai Muptie</title>
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

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-header">
                <h1 class="page-title">Manajemen Produk</h1>
                <p class="page-subtitle">Kelola stok dan harga produk</p>
            </div>

            <?php 
            $alert = getAlert();
            if ($alert): 
            ?>
            <div class="alert alert-<?= $alert['type'] ?>" style="margin-bottom: 24px;">
                <i class="fas fa-<?= $alert['type'] == 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                <span><?= $alert['message'] ?></span>
            </div>
            <?php endif; ?>

            <!-- Inventory Summary -->
            <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div>
                            <div class="stat-label">Total Produk</div>
                            <div class="stat-value"><?= mysqli_num_rows($produk) ?></div>
                        </div>
                        <div class="stat-card-icon blue">
                            <i class="fas fa-boxes"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <div>
                            <div class="stat-label">Nilai Inventaris</div>
                            <div class="stat-value" style="font-size: 24px;">
                                <?= formatRupiah($total_inventaris) ?>
                            </div>
                        </div>
                        <div class="stat-card-icon green">
                            <i class="fas fa-warehouse"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product List -->
            <div class="content-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-th-list"></i>
                        Daftar Produk
                    </h3>
                    <a href="tambah.php" class="btn btn-success btn-sm">
                        <i class="fas fa-plus"></i> Tambah Produk
                    </a>
                </div>
                
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Nilai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            mysqli_data_seek($produk, 0);
                            while ($row = mysqli_fetch_assoc($produk)): 
                                $nilai = $row['Harga'] * $row['Stok'];
                                $stok_class = $row['Stok'] < 10 ? 'badge-danger' : ($row['Stok'] < 20 ? 'badge-warning' : 'badge-success');
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= $row['NamaProduk'] ?></strong></td>
                                <td><?= formatRupiah($row['Harga']) ?></td>
                                <td><span class="badge <?= $stok_class ?>"><?= $row['Stok'] ?></span></td>
                                <td><?= formatRupiah($nilai) ?></td>
                                <td>
                                    <div class="action-btns">
                                        <a href="edit.php?id=<?= $row['ProdukID'] ?>" 
                                           class="btn-icon btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?delete=<?= $row['ProdukID'] ?>" 
                                           class="btn-icon btn-danger" title="Hapus"
                                           onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
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
