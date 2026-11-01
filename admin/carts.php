<?php
/**
 * Food Management System (FMS) - BITEBUDDY Admin Portal
 * Cart Management: Active Shopping Baskets, Abandoned Carts, Items & Revenue Recovery
 */
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
  <title>Manage Carts - BITEBUDDY Admin Portal</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
  
  <script src="https://unpkg.com/lucide@latest"></script>
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
  <style>
    .modal-dialog-custom {
      background: var(--bg-card);
      border-radius: var(--radius-lg);
      padding: 2rem;
      max-width: 580px;
      width: 90%;
      border: 1px solid var(--border);
      box-shadow: 0 20px 45px rgba(0,0,0,0.2);
    }
  </style>
</head>
<body data-theme="light">

  <!-- TOP NAVBAR -->
  <nav class="navbar">
    <div class="navbar-container">
      <a href="index.php" class="nav-brand">
        <div class="logo-badge" style="background: linear-gradient(135deg, #1E293B, #0F172A);">admin</div>
        <div class="logo-text">
          <h1>BITEBUDDY</h1>
          <span>Platform Administration</span>
        </div>
      </a>

      <!-- Admin Top Nav Links -->
      <div class="nav-actions">
        <a href="index.php" class="nav-link-item">
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
        <a href="carts.php" class="nav-link-item active">
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
    
    <!-- Header Strip -->
    <div class="admin-header-strip">
      <div class="admin-title-group">
        <h2><i data-lucide="shopping-bag" style="color: var(--primary);"></i> Manage Customer Carts</h2>
        <p>Monitor live customer shopping baskets, view abandoned order items, and clear stale cart sessions</p>
      </div>
      <div style="display: flex; gap: 10px;">
        <button class="table-action-btn" onclick="loadAdminCarts()">
          <i data-lucide="refresh-cw" style="width: 14px;"></i> Refresh Carts
        </button>
      </div>
    </div>

    <!-- Admin Subnav Bar -->
    <div class="admin-subnav">
      <a href="index.php" class="admin-nav-item">
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
      <a href="carts.php" class="admin-nav-item active">
        <i data-lucide="shopping-bag" style="width: 16px;"></i> Carts
      </a>
      <a href="restaurants.php" class="admin-nav-item">
        <i data-lucide="store" style="width: 16px;"></i> Outlets
      </a>
    </div>

    <!-- Metric Cards -->
    <div class="admin-stats-grid">
      <div class="admin-stat-card">
        <div class="stat-icon-wrapper" style="background: rgba(235, 43, 86, 0.1); color: var(--primary);">
          <i data-lucide="shopping-bag"></i>
        </div>
        <div class="stat-info">
          <span class="stat-label">Active Customer Carts</span>
          <h3 class="stat-value" id="stat-cart-total">0</h3>
        </div>
      </div>
      <div class="admin-stat-card">
        <div class="stat-icon-wrapper" style="background: rgba(16, 185, 129, 0.1); color: var(--veg-green);">
          <i data-lucide="package"></i>
        </div>
        <div class="stat-info">
          <span class="stat-label">Total Dishe Units in Carts</span>
          <h3 class="stat-value" id="stat-cart-items">0</h3>
        </div>
      </div>
      <div class="admin-stat-card">
        <div class="stat-icon-wrapper" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
          <i data-lucide="indian-rupee"></i>
        </div>
        <div class="stat-info">
          <span class="stat-label">Potential Cart Pipeline</span>
          <h3 class="stat-value" id="stat-cart-revenue">₹0.00</h3>
        </div>
      </div>
      <div class="admin-stat-card">
        <div class="stat-icon-wrapper" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
          <i data-lucide="clock"></i>
        </div>
        <div class="stat-info">
          <span class="stat-label">Average Basket Size</span>
          <h3 class="stat-value" id="stat-cart-avg">₹0.00</h3>
        </div>
      </div>
    </div>

    <!-- Carts Table -->
    <div class="admin-card">
      <div class="admin-table-wrapper">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Cart ID</th>
              <th>Customer Name</th>
              <th>Contact Details</th>
              <th>Restaurant</th>
              <th>Items In Cart</th>
              <th>Cart Value</th>
              <th>Last Active</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="carts-table-body">
            <!-- Rendered dynamically via admin.js -->
          </tbody>
        </table>
      </div>
    </div>

  </main>

  <!-- VIEW CART ITEMS MODAL -->
  <div class="modal-overlay" id="cart-modal" onclick="if(event.target === this) closeCartModal()">
    <div class="modal-dialog-custom">
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border);">
        <h3 id="cart-modal-title" style="font-size: 1.3rem; font-weight: 800;">Cart Items Breakdown</h3>
        <button class="close-modal-btn" onclick="closeCartModal()"><i data-lucide="x"></i></button>
      </div>

      <div id="cart-modal-items-list" style="max-height: 380px; overflow-y: auto;">
        <!-- Populated via admin.js -->
      </div>

      <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border);">
        <button type="button" class="table-action-btn" onclick="closeCartModal()">Close View</button>
      </div>
    </div>
  </div>

  <script src="../assets/js/admin.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      loadAdminCarts();
    });
  </script>
</body>
</html>

