<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

checkRole('admin');

if (isset($_GET['delete'])) {
    $id = escape($_GET['delete']);
    $check = mysqli_query($conn, "SELECT COUNT(*) as total FROM penjualan WHERE PelangganID = '$id'");
    $result = mysqli_fetch_assoc($check);
    
    if ($result['total'] > 0) {
        setAlert('danger', 'Pelanggan tidak bisa dihapus karena sudah ada transaksi!');
    } else {
        mysqli_query($conn, "DELETE FROM pelanggan WHERE PelangganID = '$id'");
        setAlert('success', 'Pelanggan berhasil dihapus!');
    }
    redirect('/kedaimupti/admin/pelanggan/');
}

$pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY NamaPelanggan ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelanggan - Kedaimupti</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <div class="sidebar-header"><div class="sidebar-brand">Kedaimupti</div></div>
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
                <a href="/kedaimupti/admin/pelanggan/" class="menu-item active">
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
                <a href="/kedaimupti/auth/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Keluar</a>
            </div>
        </aside>

        <main class="main-content">
            <div class="content-header">
                <h1 class="page-title">Data Pelanggan</h1>
                <p class="page-subtitle">Kelola data pelanggan</p>
            </div>

            <?php $alert = getAlert(); if ($alert): ?>
            <div class="alert alert-<?= $alert['type'] ?>">
                <i class="fas fa-<?= $alert['type'] == 'danger' ? 'exclamation-circle' : 'check-circle' ?>"></i>
                <span><?= $alert['message'] ?></span>
            </div>
            <?php endif; ?>

            <div class="content-card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-users"></i> Daftar Pelanggan</h3>
                    <a href="tambah.php" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Pelanggan</a>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pelanggan</th>
                                <th>No Telepon</th>
                                <th>Alamat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while ($p = mysqli_fetch_assoc($pelanggan)): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= $p['NamaPelanggan'] ?></strong></td>
                                <td><?= $p['NomorTelepon'] ?></td>
                                <td><?= $p['Alamat'] ?></td>
                                <td>
                                    <div class="action-btns">
                                        <a href="edit.php?id=<?= $p['PelangganID'] ?>" class="btn-icon btn-warning"><i class="fas fa-edit"></i></a>
                                        <?php if ($p['NamaPelanggan'] != 'Umum'): ?>
                                        <a href="?delete=<?= $p['PelangganID'] ?>" class="btn-icon btn-danger" onclick="return confirm('Yakin hapus?')"><i class="fas fa-trash"></i></a>
                                        <?php endif; ?>
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
