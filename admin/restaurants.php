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
  <title>Restaurants & Menus - BITEBUDDY Admin Portal</title>
  
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
        <a href="orders.php" class="nav-link-item">
          <i data-lucide="shopping-cart"></i>
          <span>Orders</span>
        </a>
        <a href="restaurants.php" class="nav-link-item active">
          <i data-lucide="utensils"></i>
          <span>Restaurants</span>
        </a>
        <a href="riders.php" class="nav-link-item">
          <i data-lucide="bike"></i>
          <span>Fleet</span>
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
        <h2><i data-lucide="utensils" style="color: var(--primary);"></i> Restaurant Outlets & Menu Catalogs</h2>
        <p>Manage partner restaurants, dishes, pricing, veg/non-veg flags, and live item stock</p>
      </div>
      <button class="table-action-btn" onclick="loadAdminRestaurants()">
        <i data-lucide="refresh-cw" style="width: 14px;"></i> Refresh Catalog
      </button>
    </div>

    <!-- Admin Subnavigation Bar -->
    <div class="admin-subnav">
      <a href="index.php" class="admin-nav-item">
        <i data-lucide="gauge" style="width: 16px;"></i> Dashboard Overview
      </a>
      <a href="orders.php" class="admin-nav-item">
        <i data-lucide="shopping-cart" style="width: 16px;"></i> Orders Management
      </a>
      <a href="restaurants.php" class="admin-nav-item active">
        <i data-lucide="utensils" style="width: 16px;"></i> Restaurants & Menus
      </a>
      <a href="riders.php" class="admin-nav-item">
        <i data-lucide="bike" style="width: 16px;"></i> Delivery Fleet
      </a>
    </div>

    <!-- Restaurants & Dishes Container -->
    <div id="admin-restaurants-list">
      <!-- Populated via admin.js -->
    </div>

  </main>

  <script src="../assets/js/admin.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      loadAdminRestaurants();
    });
  </script>
</body>
</html>

