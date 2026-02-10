<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

checkRole('admin');

// Handle delete
if (isset($_GET['delete'])) {
    $id = escape($_GET['delete']);
    mysqli_query($conn, "DELETE FROM user WHERE UserID = '$id'");
    setAlert('success', 'User berhasil dihapus!');
    redirect('/kedaimupti/admin/petugas/');
}

// Handle tambah user
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'tambah') {
    $username = escape($_POST['username']);
    $password = escape($_POST['password']);
    $role = escape($_POST['role']);
    
    $query = "INSERT INTO user (Username, Password, Role) VALUES ('$username', '$password', '$role')";
    
    if (mysqli_query($conn, $query)) {
        setAlert('success', 'User berhasil ditambahkan!');
    } else {
        setAlert('danger', 'Gagal menambahkan user!');
    }
    redirect('/kedaimupti/admin/petugas/');
}

$users = mysqli_query($conn, "SELECT * FROM user ORDER BY Role, Username");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - Kedai Muptie</title>
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
                <a href="/kedaimupti/admin/petugas/" class="menu-item active">
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
                <h1 class="page-title">Manajemen User</h1>
                <p class="page-subtitle">Kelola akun admin dan petugas</p>
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

            <div style="display: grid; grid-template-columns: 1fr 400px; gap: 24px;">
                <!-- User List -->
                <div class="content-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-users"></i>
                            Daftar User
                        </h3>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                while ($user = mysqli_fetch_assoc($users)): 
                                    $badge_class = $user['Role'] == 'admin' ? 'badge-danger' : 'badge-primary';
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= $user['Username'] ?></strong></td>
                                    <td>
                                        <span class="badge <?= $badge_class ?>">
                                            <?= strtoupper($user['Role']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($user['UserID'] != $_SESSION['user_id']): ?>
                                        <div class="action-btns">
                                            <a href="edit.php?id=<?= $user['UserID'] ?>" 
                                               class="btn-icon btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="?delete=<?= $user['UserID'] ?>" 
                                               class="btn-icon btn-danger" title="Hapus"
                                               onclick="return confirm('Yakin ingin menghapus user ini?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                        <?php else: ?>
                                        <span class="badge badge-primary">Akun Anda</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Add User Form -->
                <div class="content-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user-plus"></i>
                            Tambah User Baru
                        </h3>
                    </div>
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="tambah">
                        
                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Username" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-control" required>
                                <option value="">Pilih Role</option>
                                <option value="admin">Admin</option>
                                <option value="petugas">Petugas</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success" style="width: 100%;">
                            <i class="fas fa-save"></i> Simpan User
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
