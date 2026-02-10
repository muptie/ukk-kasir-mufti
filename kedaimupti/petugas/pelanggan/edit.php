<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';
checkRole('petugas');

$id = escape($_GET['id']);
$pelanggan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pelanggan WHERE PelangganID = '$id'"));
if (!$pelanggan) { setAlert('danger', 'Pelanggan tidak ditemukan!'); redirect('/kedaimupti/petugas/pelanggan/'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = escape($_POST['nama']);
    $telepon = escape($_POST['telepon']);
    $alamat = escape($_POST['alamat']);
    
    $check = mysqli_query($conn, "SELECT * FROM pelanggan WHERE NomorTelepon = '$telepon' AND PelangganID != '$id'");
    if (mysqli_num_rows($check) > 0) {
        setAlert('danger', 'Nomor telepon sudah digunakan!');
    } else {
        $query = "UPDATE pelanggan SET NamaPelanggan='$nama', NomorTelepon='$telepon', Alamat='$alamat' WHERE PelangganID='$id'";
        if (mysqli_query($conn, $query)) {
            setAlert('success', 'Pelanggan berhasil diupdate!');
            redirect('/kedaimupti/petugas/pelanggan/');
        } else {
            setAlert('danger', 'Gagal update!');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pelanggan</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <div class="sidebar-header"><div class="sidebar-brand">Kedaimupti</div></div>
            <nav class="sidebar-menu">
                <a href="/kedaimupti/petugas/dashboard/" class="menu-item"><i class="fas fa-home"></i> Dashboard</a>
                <a href="/kedaimupti/petugas/produk/" class="menu-item"><i class="fas fa-box"></i> Produk</a>
                <a href="/kedaimupti/petugas/pelanggan/" class="menu-item"><i class="fas fa-users"></i> Data Pelanggan</a>
                <a href="/kedaimupti/petugas/pelanggan/" class="menu-item active"><i class="fas fa-users"></i> Pelanggan</a>
                
                
                
            </nav>
            <div class="sidebar-footer">
                <a href="/kedaimupti/auth/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Keluar</a>
            </div>
        </aside>
        <main class="main-content">
            <div class="content-header">
                <h1 class="page-title">Edit Pelanggan</h1>
                <p class="page-subtitle">Update data pelanggan</p>
            </div>
            <div class="content-card" style="max-width: 600px;">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-edit"></i> Form Edit</h3></div>
                <?php $alert = getAlert(); if ($alert): ?>
                <div class="alert alert-<?= $alert['type'] ?>">
                    <i class="fas fa-<?= $alert['type'] == 'danger' ? 'exclamation-circle' : 'check-circle' ?>"></i>
                    <span><?= $alert['message'] ?></span>
                </div>
                <?php endif; ?>
                <form method="POST">
                    <div class="form-group">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" value="<?= $pelanggan['NamaPelanggan'] ?>" required autofocus style="padding-left: 16px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">No Telepon</label>
                        <input type="text" name="telepon" class="form-control" value="<?= $pelanggan['NomorTelepon'] ?>" required style="padding-left: 16px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3" required style="padding-left: 16px;"><?= $pelanggan['Alamat'] ?></textarea>
                    </div>
                    <div style="display: flex; gap: 12px; margin-top: 24px;">
                        <button type="submit" class="btn btn-primary" style="flex: 1;"><i class="fas fa-save"></i> Update</button>
                        <a href="/kedaimupti/petugas/pelanggan/" class="btn btn-danger" style="flex: 1;"><i class="fas fa-times"></i> Batal</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
