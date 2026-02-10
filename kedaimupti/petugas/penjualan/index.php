<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

checkRole('petugas');

// Get all products
$produk = mysqli_query($conn, "SELECT * FROM produk WHERE Stok > 0 ORDER BY NamaProduk ASC");

// Get all customers
$pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY NamaPelanggan ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Penjualan - Kedai Muptie</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .pos-container {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 16px;
            height: calc(100vh - 140px);
            max-height: calc(100vh - 140px);
        }
        
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 12px;
            max-height: calc(100vh - 160px);
            overflow-y: auto;
            padding: 12px;
            background: white;
            border-radius: 12px;
        }
        
        .product-card {
            background: white;
            border-radius: 10px;
            padding: 12px;
            text-align: center;
            cursor: pointer;
            border: 2px solid var(--border);
        }
        
        .product-card:hover {
            border-color: var(--primary);
            background: #fff5f0;
        }
        
        .product-name {
            font-weight: 600;
            margin-bottom: 6px;
            color: var(--dark);
            font-size: 12px;
        }
        
        .product-price {
            color: var(--primary);
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 4px;
        }
        
        .product-stock {
            font-size: 11px;
            color: var(--gray);
        }
        
        .cart-panel {
            background: white;
            border-radius: 12px;
            padding: 14px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            max-height: calc(100vh - 140px);
            overflow-y: auto;
        }
        
        .cart-header {
            border-bottom: 1px solid var(--border);
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        
        .cart-header h3 {
            font-size: 15px !important;
            margin-bottom: 0 !important;
        }
        
        .cart-items {
            flex: 1;
            overflow-y: auto;
            margin-bottom: 12px;
            min-height: 80px;
            max-height: 220px;
        }
        
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px;
            background: #f8f9fa;
            border-radius: 6px;
            margin-bottom: 6px;
            font-size: 12px;
        }
        
        .cart-item-name {
            font-weight: 600;
            font-size: 12px;
            flex: 1;
        }
        
        .cart-item-qty {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .qty-btn {
            width: 24px;
            height: 24px;
            border-radius: 4px;
            border: none;
            background: var(--primary);
            color: white;
            cursor: pointer;
            font-weight: 600;
            font-size: 12px;
        }
        
        .qty-btn:hover {
            background: var(--primary-dark);
        }
        
        .cart-summary {
            border-top: 1px solid var(--border);
            padding-top: 10px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 13px;
        }
        
        .summary-row.total {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
            margin-top: 8px;
        }
    </style>
</head>
<body>
    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-brand">Kedaimupti</div>
            </div>
            <nav class="sidebar-menu">
                <a href="/kedaimupti/petugas/dashboard/" class="menu-item">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="/kedaimupti/petugas/penjualan/" class="menu-item active">
                    <i class="fas fa-shopping-cart"></i> Transaksi
                </a>
                <a href="/kedaimupti/petugas/produk/" class="menu-item">
                    <i class="fas fa-box"></i> Lihat Produk
                </a>
                <a href="/kedaimupti/petugas/pelanggan/" class="menu-item">
                    <i class="fas fa-users"></i> Data Pelanggan
                </a>
            </nav>
            <div class="sidebar-footer">
                <a href="/kedaimupti/auth/logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </a>
            </div>
        </aside>

        <main class="main-content" style="padding: 16px;">
            <div class="content-header" style="margin-bottom: 12px;">
                <h1 class="page-title" style="font-size: 22px; margin-bottom: 2px;">Transaksi Penjualan</h1>
                <p class="page-subtitle">Pilih produk untuk transaksi</p>
            </div>

            <div class="pos-container">
                <!-- Product Selection -->
                <div style="border-radius: 12px; overflow: hidden; display: flex; flex-direction: column;">
                    <div style="padding: 12px; background: white; border-bottom: 1px solid var(--border);">
                        <h3 style="font-size: 14px; font-weight: 700; margin: 0;">
                            <i class="fas fa-th"></i> Pilih Produk
                        </h3>
                    </div>
                    <div class="product-grid">
                        <?php while ($p = mysqli_fetch_assoc($produk)): ?>
                        <div class="product-card" onclick="addToCart(<?= $p['ProdukID'] ?>, '<?= addslashes($p['NamaProduk']) ?>', <?= $p['Harga'] ?>, <?= $p['Stok'] ?>)">
                            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-radius: 8px; margin: 0 auto 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-box" style="color: white; font-size: 24px;"></i>
                            </div>
                            <div class="product-name"><?= $p['NamaProduk'] ?></div>
                            <div class="product-price"><?= formatRupiah($p['Harga']) ?></div>
                            <div class="product-stock">Stok: <?= $p['Stok'] ?></div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <!-- Shopping Cart -->
                <div class="cart-panel">
                    <div class="cart-header">
                        <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px;">
                            <i class="fas fa-shopping-cart"></i> Keranjang Belanja
                        </h3>
                    </div>

                    <!-- Data Pelanggan -->
                    <div style="background: #f8f9fa; padding: 10px; border-radius: 6px; margin-bottom: 10px;">
                        <h4 style="font-size: 12px; font-weight: 700; margin-bottom: 8px; color: var(--dark);">
                            <i class="fas fa-user"></i> Data Pelanggan
                        </h4>
                        
                        <div class="form-group" style="margin-bottom: 6px;">
                            <label class="form-label" style="margin-bottom: 2px; font-size: 11px;">Nama</label>
                            <input type="text" id="customerName" class="form-control" placeholder="Nama" style="padding: 6px 8px; font-size: 12px;">
                        </div>

                        <div class="form-group" style="margin-bottom: 6px;">
                            <label class="form-label" style="margin-bottom: 2px; font-size: 11px;">No Telepon</label>
                            <input type="text" id="customerPhone" class="form-control" placeholder="08xxx" style="padding: 6px 8px; font-size: 12px;">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="margin-bottom: 2px; font-size: 11px;">Alamat</label>
                            <textarea id="customerAddress" class="form-control" placeholder="Alamat" rows="1" style="padding: 6px 8px; resize: none; font-size: 12px;"></textarea>
                        </div>
                    </div>

                    <!-- Keranjang Belanja -->
                    <h4 style="font-size: 12px; font-weight: 700; margin-bottom: 6px; color: var(--dark);">
                        <i class="fas fa-shopping-basket"></i> Item
                    </h4>
                    
                    <div class="cart-items" id="cartItems">
                        <div style="text-align: center; padding: 20px 10px; color: var(--gray); font-size: 12px;">
                            <i class="fas fa-shopping-basket" style="font-size: 32px; opacity: 0.3; margin-bottom: 6px;"></i>
                            <p style="margin: 0;">Belum ada produk</p>
                        </div>
                    </div>

                    <div class="cart-summary">
                        <div class="summary-row">
                            <span style="font-size: 12px;">Total:</span>
                            <span id="grandTotal" style="font-weight: 700; font-size: 14px;">Rp 0</span>
                        </div>
                        
                        <div class="form-group" style="margin: 8px 0;">
                            <label class="form-label" style="font-size: 11px; margin-bottom: 2px;">Uang Bayar</label>
                            <input type="number" id="payment" class="form-control" placeholder="0" onkeyup="calculateChange()" style="padding: 6px 8px; font-size: 12px;">
                        </div>
                        
                        <div class="summary-row" style="color: var(--secondary); font-size: 12px; margin-bottom: 8px;">
                            <span>Kembalian:</span>
                            <span id="change" style="font-weight: 700;">Rp 0</span>
                        </div>
                        
                        <button onclick="processPayment()" class="btn btn-success" style="width: 100%; margin: 0; padding: 10px; font-size: 13px; font-weight: 600;">
                            <i class="fas fa-check-circle"></i> BAYAR
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        let cart = [];

        function addToCart(id, name, price, maxStock) {
            const existing = cart.find(item => item.id === id);
            
            if (existing) {
                if (existing.qty < maxStock) {
                    existing.qty++;
                } else {
                    alert('Stok tidak mencukupi!');
                    return;
                }
            } else {
                cart.push({ id, name, price, qty: 1, maxStock });
            }
            
            updateCart();
        }

        function updateQty(id, change) {
            const item = cart.find(i => i.id === id);
            if (item) {
                item.qty += change;
                if (item.qty <= 0) {
                    cart = cart.filter(i => i.id !== id);
                } else if (item.qty > item.maxStock) {
                    item.qty = item.maxStock;
                    alert('Stok tidak mencukupi!');
                }
            }
            updateCart();
        }

        function removeItem(id) {
            cart = cart.filter(i => i.id !== id);
            updateCart();
        }

        function updateCart() {
            const container = document.getElementById('cartItems');
            
            if (cart.length === 0) {
                container.innerHTML = `
                    <div style="text-align: center; padding: 40px 20px; color: var(--gray);">
                        <i class="fas fa-shopping-basket" style="font-size: 48px; opacity: 0.3; margin-bottom: 12px;"></i>
                        <p>Belum ada produk</p>
                    </div>
                `;
            } else {
                container.innerHTML = cart.map(item => `
                    <div class="cart-item">
                        <div>
                            <div class="cart-item-name">${item.name}</div>
                            <div style="font-size: 13px; color: var(--gray);">${formatRupiah(item.price)} × ${item.qty}</div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div class="cart-item-qty">
                                <button class="qty-btn" onclick="updateQty(${item.id}, -1)">-</button>
                                <span style="font-weight: 700; min-width: 30px; text-align: center;">${item.qty}</span>
                                <button class="qty-btn" onclick="updateQty(${item.id}, 1)">+</button>
                            </div>
                            <button class="btn-icon btn-danger" onclick="removeItem(${item.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `).join('');
            }
            
            const total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            document.getElementById('grandTotal').textContent = formatRupiah(total);
            calculateChange();
        }

        function calculateChange() {
            const total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            const payment = parseFloat(document.getElementById('payment').value) || 0;
            const change = payment - total;
            document.getElementById('change').textContent = formatRupiah(Math.max(0, change));
        }

        function formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function processPayment() {
            if (cart.length === 0) {
                alert('Keranjang masih kosong!');
                return;
            }
            
            const total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            const payment = parseFloat(document.getElementById('payment').value) || 0;
            
            if (payment < total) {
                alert('Uang bayar kurang!');
                return;
            }
            
            const customerName = document.getElementById('customerName').value.trim();
            const customerPhone = document.getElementById('customerPhone').value.trim();
            const customerAddress = document.getElementById('customerAddress').value.trim();
            
            if (!customerName) {
                alert('Nama pelanggan harus diisi!');
                return;
            }
            
            if (!customerPhone) {
                alert('No telepon pelanggan harus diisi!');
                return;
            }
            
            if (!customerAddress) {
                alert('Alamat pelanggan harus diisi!');
                return;
            }
            
            // Submit transaction
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'proses.php';
            
            form.innerHTML = `
                <input type="hidden" name="customer_name" value="${customerName}">
                <input type="hidden" name="customer_phone" value="${customerPhone}">
                <input type="hidden" name="customer_address" value="${customerAddress}">
                <input type="hidden" name="cart" value='${JSON.stringify(cart)}'>
                <input type="hidden" name="total" value="${total}">
            `;
            
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>
