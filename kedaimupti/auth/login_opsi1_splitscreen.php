<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Jika sudah login, redirect ke dashboard
if (isLoggedIn()) {
    if ($_SESSION['role'] == 'admin') {
        redirect('/kedaimupti/admin/dashboard/');
    } else {
        redirect('/kedaimupti/petugas/dashboard/');
    }
}

// Proses login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = escape($_POST['username']);
    $password = $_POST['password'];
    
    $query = "SELECT * FROM user WHERE Username = '$username'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        if ($password == $user['Password']) {
            $_SESSION['user_id'] = $user['UserID'];
            $_SESSION['username'] = $user['Username'];
            $_SESSION['role'] = $user['Role'];
            
            if ($user['Role'] == 'admin') {
                redirect('/kedaimupti/admin/dashboard/');
            } else {
                redirect('/kedaimupti/petugas/dashboard/');
            }
        } else {
            setAlert('danger', 'Password salah!');
        }
    } else {
        setAlert('danger', 'Username tidak ditemukan!');
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kedai Muptie (Split Screen)</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            overflow: hidden;
        }

        .login-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }

        .login-left {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -200px;
            left: -200px;
        }

        .login-left::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            bottom: -150px;
            right: -150px;
        }

        .login-left-content {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .login-left i {
            font-size: 120px;
            margin-bottom: 30px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .login-left h1 {
            font-size: 56px;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .login-left p {
            font-size: 20px;
            opacity: 0.9;
            font-weight: 300;
        }

        .login-right {
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px;
        }

        .login-form-container {
            width: 100%;
            max-width: 420px;
        }

        .login-form-header {
            margin-bottom: 40px;
        }

        .login-form-header h2 {
            font-size: 32px;
            color: #1f2937;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .login-form-header p {
            color: #6b7280;
            font-size: 15px;
        }

        .alert {
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #1f2937;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .btn-primary {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }

        .login-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        .login-footer p {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .login-footer a {
            color: #6366f1;
            font-weight: 600;
            text-decoration: none;
            font-size: 15px;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .login-wrapper {
                grid-template-columns: 1fr;
            }
            
            .login-left {
                display: none;
            }
            
            .login-right {
                padding: 40px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <!-- Left Side - Branding -->
        <div class="login-left">
            <div class="login-left-content">
                <i class="fas fa-cash-register"></i>
                <h1>Kedai Muptie</h1>
                <p>Sistem Kasir Pintar & Mudah</p>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="login-right">
            <div class="login-form-container">
                <div class="login-form-header">
                    <h2>Selamat Datang</h2>
                    <p>Silakan login ke akun Anda</p>
                </div>

                <?php 
                $alert = getAlert();
                if ($alert): 
                ?>
                <div class="alert alert-<?= $alert['type'] ?>">
                    <i class="fas fa-<?= $alert['type'] == 'danger' ? 'exclamation-circle' : 'check-circle' ?>"></i>
                    <span><?= $alert['message'] ?></span>
                </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>

                    <button type="submit" class="btn-primary">
                        Masuk Sekarang
                    </button>
                </form>

                <div class="login-footer">
                    <p>Belum punya akun?</p>
                    <a href="register.php">Daftar Sekarang</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
