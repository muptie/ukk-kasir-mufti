<?php
session_start();

// Cek apakah user sudah login
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Cek role user
function checkRole($role) {
    if (!isLoggedIn()) {
        header('Location: /kedaimupti/auth/login.php');
        exit;
    }
    
    if ($_SESSION['role'] != $role) {
        header('Location: /kedaimupti/auth/login.php');
        exit;
    }
}

// Format rupiah
function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

// Escape string untuk keamanan
function escape($string) {
    global $conn;
    return mysqli_real_escape_string($conn, $string);
}

// Redirect helper
function redirect($url) {
    header("Location: $url");
    exit;
}

// Alert message
function setAlert($type, $message) {
    $_SESSION['alert'] = [
        'type' => $type,
        'message' => $message
    ];
}

function getAlert() {
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        unset($_SESSION['alert']);
        return $alert;
    }
    return null;
}
?>
