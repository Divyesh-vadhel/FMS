<?php
session_start();
if (empty($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orders Management - BITEBUDDY Admin Portal</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
  
  <script src="https://unpkg.com/lucide@latest"></script>
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body data-theme="light">

  <!-- NAVBAR -->
  <nav class="navbar">
    <div class="navbar-container">
      <a href="index.php" class="nav-brand">
        <div class="logo-badge" style="background: linear-gradient(135deg, #1E293B, #0F172A);">admin</div>
        <div class="logo-text">
          <h1>BITEBUDDY</h1>
          <span>Platform Administration</span>
        </div>
      </a>

      <!-- Admin Dedicated Navigation Links with Icons -->
      <div class="nav-actions">
        <a href="index.php" class="nav-link-item">
          <i data-lucide="layout-dashboard"></i>
          <span>Dashboard</span>
        </a>
        <a href="orders.php" class="nav-link-item active">
          <i data-lucide="shopping-cart"></i>
          <span>Orders</span>
        </a>
        <a href="products.php" class="nav-link-item">
          <i data-lucide="utensils"></i>
          <span>Products</span>
        </a>
        <a href="users.php" class="nav-link-item">
          <i data-lucide="users"></i>
          <span>Users</span>
        </a>
        <a href="drivers.php" class="nav-link-item">
          <i data-lucide="bike"></i>
          <span>Drivers</span>
        </a>
        <a href="carts.php" class="nav-link-item">
          <i data-lucide="shopping-bag"></i>
          <span>Carts</span>
        </a>
        <a href="restaurants.php" class="nav-link-item">
          <i data-lucide="store"></i>
          <span>Outlets</span>
        </a>
        <a href="../user/index.php" class="nav-link-item nav-btn-outline" title="Open Customer Storefront">
          <i data-lucide="external-link"></i>
          <span>Live Store</span>
        </a>

        <!-- Admin Profile -->
        <div class="nav-link-item" style="cursor: default; background: var(--bg-card); border-color: var(--border);">
          <i data-lucide="shield-check" style="color: var(--veg-green);"></i>
          <span><?= htmlspecialchars($_SESSION['admin_name'] ?? 'admin') ?></span>
        </div>

        <a href="logout.php" class="nav-link-item nav-btn-outline" title="Sign Out from Console">
          <i data-lucide="log-out"></i>
          <span>Logout</span>
        </a>

        <button class="btn-icon" onclick="toggleTheme()" title="Toggle Dark/Light Mode">
          <i data-lucide="moon" id="theme-icon"></i>
        </button>
      </div>
    </div>
  </nav>

  <main class="admin-layout">
    
    <div class="admin-header-strip">
      <div class="admin-title-group">
        <h2><i data-lucide="shopping-cart" style="color: var(--primary);"></i> Orders Management</h2>
        <p>Monitor live orders, filter by state, and update workflow status directly</p>
      </div>
      <button class="table-action-btn" onclick="loadAdminOrders('all')">
        <i data-lucide="refresh-cw" style="width: 14px;"></i> Refresh Orders
      </button>
    </div>

    <!-- Admin Subnavigation Bar -->
    <div class="admin-subnav">
      <a href="index.php" class="admin-nav-item">
        <i data-lucide="gauge" style="width: 16px;"></i> Dashboard
      </a>
      <a href="orders.php" class="admin-nav-item active">
        <i data-lucide="shopping-cart" style="width: 16px;"></i> Orders
      </a>
      <a href="products.php" class="admin-nav-item">
        <i data-lucide="utensils" style="width: 16px;"></i> Products
      </a>
      <a href="users.php" class="admin-nav-item">
        <i data-lucide="users" style="width: 16px;"></i> Users
      </a>
      <a href="drivers.php" class="admin-nav-item">
        <i data-lucide="bike" style="width: 16px;"></i> Drivers
      </a>
      <a href="carts.php" class="admin-nav-item">
        <i data-lucide="shopping-bag" style="width: 16px;"></i> Carts
      </a>
      <a href="restaurants.php" class="admin-nav-item">
        <i data-lucide="store" style="width: 16px;"></i> Outlets
      </a>
    </div>

    <!-- Order Status Filter Pills -->
    <div class="filter-tags-strip" style="margin-bottom: 1.5rem;">
      <button class="filter-chip active" onclick="setOrderFilter(this, 'all')">All Orders</button>
      <button class="filter-chip" onclick="setOrderFilter(this, 'placed')">Placed</button>
      <button class="filter-chip" onclick="setOrderFilter(this, 'cooking')">Cooking</button>
      <button class="filter-chip" onclick="setOrderFilter(this, 'out_for_delivery')">Out for Delivery</button>
      <button class="filter-chip" onclick="setOrderFilter(this, 'delivered')">Delivered</button>
    </div>

    <!-- Orders Table Card -->
    <div class="admin-card">
      <div class="admin-card-header">
        <h3><i data-lucide="list-ordered" style="color: var(--primary);"></i> Platform Order Tickets</h3>
        <span style="font-size: 0.85rem; color: var(--text-muted);">Real-time database sync</span>
      </div>
      <div class="admin-table-wrapper">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Customer</th>
              <th>Restaurant & Items</th>
              <th>Amount</th>
              <th>Payment</th>
              <th>Current Status</th>
              <th>Action Status</th>
            </tr>
          </thead>
          <tbody id="orders-table-body">
            <!-- Populated via admin.js -->
          </tbody>
        </table>
      </div>
    </div>

  </main>

  <script src="../assets/js/admin.js"></script>
  <script>
    let currentFilter = 'all';
    document.addEventListener('DOMContentLoaded', () => {
      loadAdminOrders('all');
    });

    function setOrderFilter(btn, status) {
      document.querySelectorAll('.filter-tags-strip .filter-chip').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      currentFilter = status;
      renderOrdersTable(status);
    }
  </script>
</body>
</html>

