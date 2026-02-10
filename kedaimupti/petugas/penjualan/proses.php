<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

checkRole('petugas');

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    redirect('/kedaimupti/petugas/penjualan/');
}

$customerName = escape($_POST['customer_name']);
$customerPhone = escape($_POST['customer_phone']);
$customerAddress = escape($_POST['customer_address']);
$cart = json_decode($_POST['cart'], true);
$total = floatval($_POST['total']);

// Validasi
if (empty($customerName) || empty($customerPhone) || empty($customerAddress)) {
    setAlert('danger', 'Data pelanggan tidak lengkap!');
    redirect('/kedaimupti/petugas/penjualan/');
}

// Start transaction
mysqli_begin_transaction($conn);

try {
    // Check or create customer berdasarkan nama DAN telepon
    $customerQuery = "SELECT PelangganID FROM pelanggan 
                      WHERE NamaPelanggan = '$customerName' AND NomorTelepon = '$customerPhone' 
                      LIMIT 1";
    $customerResult = mysqli_query($conn, $customerQuery);
    
    if (mysqli_num_rows($customerResult) > 0) {
        $customerId = mysqli_fetch_assoc($customerResult)['PelangganID'];
        // Update alamat jika berbeda
        mysqli_query($conn, "UPDATE pelanggan SET Alamat = '$customerAddress' WHERE PelangganID = '$customerId'");
    } else {
        // Create new customer dengan data lengkap
        $insertCustomer = "INSERT INTO pelanggan (NamaPelanggan, Alamat, NomorTelepon) 
                          VALUES ('$customerName', '$customerAddress', '$customerPhone')";
        mysqli_query($conn, $insertCustomer);
        $customerId = mysqli_insert_id($conn);
    }
    
    // Insert penjualan
    $insertPenjualan = "INSERT INTO penjualan (TanggalPenjualan, TotalHarga, PelangganID) 
                       VALUES (NOW(), '$total', '$customerId')";
    mysqli_query($conn, $insertPenjualan);
    $penjualanId = mysqli_insert_id($conn);
    
    // Insert detail penjualan and update stock
    foreach ($cart as $item) {
        $produkId = $item['id'];
        $qty = $item['qty'];
        $subtotal = $item['price'] * $qty;
        
        // Insert detail
        $insertDetail = "INSERT INTO detailpenjualan (PenjualanID, ProdukID, JumlahProduk, Subtotal) 
                        VALUES ('$penjualanId', '$produkId', '$qty', '$subtotal')";
        mysqli_query($conn, $insertDetail);
        
        // Update stock
        $updateStock = "UPDATE produk SET Stok = Stok - $qty WHERE ProdukID = '$produkId'";
        mysqli_query($conn, $updateStock);
    }
    
    // Commit transaction
    mysqli_commit($conn);
    
    // Redirect to success page
    setAlert('success', 'Transaksi berhasil disimpan!');
    redirect('/kedaimupti/petugas/penjualan/detail.php?id=' . $penjualanId);
    
} catch (Exception $e) {
    mysqli_rollback($conn);
    setAlert('danger', 'Transaksi gagal! Error: ' . $e->getMessage());
    redirect('/kedaimupti/petugas/penjualan/');
}
?>
