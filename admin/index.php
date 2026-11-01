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
  <title>Admin Dashboard - BITEBUDDY Food Management System</title>
  
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
  
  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>
  
  <!-- Stylesheets -->
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
        <a href="index.php" class="nav-link-item active">
          <i data-lucide="layout-dashboard"></i>
          <span>Dashboard</span>
        </a>
        <a href="orders.php" class="nav-link-item">
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

  <!-- MAIN ADMIN CONTENT -->
  <main class="admin-layout">
    
    <!-- Header Strip -->
    <div class="admin-header-strip">
      <div class="admin-title-group">
        <h2><i data-lucide="layout-dashboard" style="color: var(--primary);"></i> Platform Operations & Analytics</h2>
        <p>Live metrics, revenue performance, order workflow, and system database status</p>
      </div>
      <div>
        <a href="http://localhost/phpmyadmin/" target="_blank" class="table-action-btn" style="background: var(--primary); color: #fff; border-color: var(--primary); text-decoration: none;">
          <i data-lucide="external-link" style="width: 14px;"></i> phpMyAdmin
        </a>
      </div>
    </div>

    <!-- Admin Subnavigation Bar -->
    <div class="admin-subnav">
      <a href="index.php" class="admin-nav-item active">
        <i data-lucide="gauge" style="width: 16px;"></i> Dashboard
      </a>
      <a href="orders.php" class="admin-nav-item">
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

    <!-- KPI Metrics Grid -->
    <div class="admin-metrics">
      <div class="metric-card">
        <div class="metric-icon" style="background: rgba(226, 55, 68, 0.1); color: var(--primary);">
          <i data-lucide="shopping-cart"></i>
        </div>
        <div>
          <h4 id="stat-orders" style="font-size: 1.5rem; font-weight: 800;">14</h4>
          <p style="color: var(--text-muted); font-size: 0.8rem;">Total Orders Placed</p>
        </div>
      </div>

      <div class="metric-card">
        <div class="metric-icon" style="background: rgba(36, 150, 63, 0.1); color: var(--veg-green);">
          <i data-lucide="banknote"></i>
        </div>
        <div>
          <h4 id="stat-revenue" style="font-size: 1.5rem; font-weight: 800;">₹4,820.00</h4>
          <p style="color: var(--text-muted); font-size: 0.8rem;">Total Platform GMV</p>
        </div>
      </div>

      <div class="metric-card">
        <div class="metric-icon" style="background: rgba(2, 132, 199, 0.1); color: var(--info-blue);">
          <i data-lucide="store"></i>
        </div>
        <div>
          <h4 id="stat-restaurants" style="font-size: 1.5rem; font-weight: 800;">6</h4>
          <p style="color: var(--text-muted); font-size: 0.8rem;">Active Restaurants</p>
        </div>
      </div>

      <div class="metric-card">
        <div class="metric-icon" style="background: rgba(139, 92, 246, 0.1); color: #8B5CF6;">
          <i data-lucide="bike"></i>
        </div>
        <div>
          <h4 id="stat-riders" style="font-size: 1.5rem; font-weight: 800;">4</h4>
          <p style="color: var(--text-muted); font-size: 0.8rem;">Active Fleet Riders</p>
        </div>
      </div>
    </div>

    <!-- Database Health & Status Card -->
    <div class="admin-card" style="margin-top: 1.5rem;">
      <div class="admin-card-header">
        <h3><i data-lucide="check-circle-2" style="color: var(--veg-green);"></i> System Health & Architecture</h3>
        <div style="display: flex; gap: 8px;">
          <span class="badge-pill badge-delivered">MySQL Active</span>
          <span class="badge-pill badge-cooking">Database: food_db</span>
          <span class="badge-pill badge-out_for_delivery">PHP 8.2</span>
        </div>
      </div>
      <div style="padding: 1.25rem 1.5rem;">
        <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6;">
          All <strong>14 relational tables</strong> (authentication, admins, customers, addresses, restaurants, menu_items, chefs, delivery_boys, carts, cart_items, orders, order_items, payments, reviews) are active and synced.
        </p>
      </div>
    </div>

    <!-- Recent Orders Preview -->
    <div class="admin-card">
      <div class="admin-card-header">
        <h3><i data-lucide="clock" style="color: var(--primary);"></i> Recent Orders Overview</h3>
        <a href="orders.php" class="table-action-btn" style="text-decoration: none;">View All Orders →</a>
      </div>
      <div class="admin-table-wrapper">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Customer</th>
              <th>Restaurant</th>
              <th>Amount</th>
              <th>Payment</th>
              <th>Status</th>
              <th>Status Action</th>
            </tr>
          </thead>
          <tbody id="orders-table-body">
            <!-- Populated via admin.js -->
          </tbody>
        </table>
      </div>
    </div>

  </main>

  <!-- Admin Controller Script -->
  <script src="../assets/js/admin.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      loadAdminKPIs();
      loadAdminOrders('all');
    });
  </script>
</body>
</html>

