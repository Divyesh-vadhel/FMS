<?php
/**
 * Food Management System (FMS) - BITEBUDDY Admin Portal
 * Driver Management: Delivery Fleet, Availability, Vehicle Details, Dispatch
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
  <title>Manage Drivers - BITEBUDDY Admin Portal</title>
  
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
      max-width: 520px;
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
        <a href="drivers.php" class="nav-link-item active">
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
    
    <!-- Header Strip -->
    <div class="admin-header-strip">
      <div class="admin-title-group">
        <h2><i data-lucide="bike" style="color: var(--primary);"></i> Manage Delivery Drivers</h2>
        <p>Monitor delivery fleet, assign orders, toggle availability status, and register new delivery riders</p>
      </div>
      <div style="display: flex; gap: 10px;">
        <button class="nav-btn-primary" onclick="openAddDriverModal()" style="display: flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: var(--radius-sm); font-weight: 700; border: none; cursor: pointer;">
          <i data-lucide="plus" style="width: 16px;"></i> Add New Driver
        </button>
        <button class="table-action-btn" onclick="loadAdminDrivers()">
          <i data-lucide="refresh-cw" style="width: 14px;"></i> Refresh
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
      <a href="drivers.php" class="admin-nav-item active">
        <i data-lucide="bike" style="width: 16px;"></i> Drivers
      </a>
      <a href="carts.php" class="admin-nav-item">
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
          <i data-lucide="bike"></i>
        </div>
        <div class="stat-info">
          <span class="stat-label">Total Fleet Drivers</span>
          <h3 class="stat-value" id="stat-driver-total">4</h3>
        </div>
      </div>
      <div class="admin-stat-card">
        <div class="stat-icon-wrapper" style="background: rgba(16, 185, 129, 0.1); color: var(--veg-green);">
          <i data-lucide="check-circle"></i>
        </div>
        <div class="stat-info">
          <span class="stat-label">Available for Dispatch</span>
          <h3 class="stat-value" id="stat-driver-available">4</h3>
        </div>
      </div>
      <div class="admin-stat-card">
        <div class="stat-icon-wrapper" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
          <i data-lucide="navigation"></i>
        </div>
        <div class="stat-info">
          <span class="stat-label">Out on Delivery</span>
          <h3 class="stat-value" id="stat-driver-busy">0</h3>
        </div>
      </div>
      <div class="admin-stat-card">
        <div class="stat-icon-wrapper" style="background: rgba(100, 116, 139, 0.1); color: #64748b;">
          <i data-lucide="power"></i>
        </div>
        <div class="stat-info">
          <span class="stat-label">Offline Drivers</span>
          <h3 class="stat-value" id="stat-driver-offline">0</h3>
        </div>
      </div>
    </div>

    <!-- Drivers Table -->
    <div class="admin-card">
      <div class="admin-table-wrapper">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Driver ID</th>
              <th>Driver Name</th>
              <th>Phone & Email</th>
              <th>Vehicle Type</th>
              <th>Vehicle Plate</th>
              <th>Status</th>
              <th>Quick Status Change</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="drivers-table-body">
            <!-- Rendered dynamically via admin.js -->
          </tbody>
        </table>
      </div>
    </div>

  </main>

  <!-- ADD / EDIT DRIVER MODAL -->
  <div class="modal-overlay" id="driver-modal" onclick="if(event.target === this) closeDriverModal()">
    <div class="modal-dialog-custom">
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border);">
        <h3 id="driver-modal-title" style="font-size: 1.3rem; font-weight: 800;">Add New Driver</h3>
        <button class="close-modal-btn" onclick="closeDriverModal()"><i data-lucide="x"></i></button>
      </div>

      <form id="driver-form" onsubmit="handleDriverFormSubmit(event)">
        <input type="hidden" id="driver-form-id" value="">

        <div class="auth-form-group">
          <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 6px;">Driver Full Name</label>
          <input type="text" id="driver-name" placeholder="Amit Verma" required
                 style="width: 100%; padding: 10px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--bg-main); color: var(--text);">
        </div>

        <div class="auth-form-group">
          <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 6px;">Phone Number</label>
          <input type="tel" id="driver-phone" placeholder="+91 9855566677" required
                 style="width: 100%; padding: 10px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--bg-main); color: var(--text);">
        </div>

        <div class="auth-form-group">
          <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 6px;">Email Address</label>
          <input type="email" id="driver-email" placeholder="amit.rider@bitebuddy.com" required
                 style="width: 100%; padding: 10px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--bg-main); color: var(--text);">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <div class="auth-form-group">
            <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 6px;">Vehicle Type</label>
            <select id="driver-vehicle-type"
                    style="width: 100%; padding: 10px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--bg-main); color: var(--text);">
              <option value="Bike">Motorbike 🏍️</option>
              <option value="Scooter">Scooter 🛵</option>
              <option value="EV">Electric EV ⚡</option>
              <option value="Bicycle">Bicycle 🚲</option>
            </select>
          </div>

          <div class="auth-form-group">
            <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 6px;">Plate / Registration</label>
            <input type="text" id="driver-vehicle-no" placeholder="KA-01-BZ-4321" required
                   style="width: 100%; padding: 10px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--bg-main); color: var(--text);">
          </div>
        </div>

        <div class="auth-form-group">
          <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 6px;">Duty Status</label>
          <select id="driver-status"
                  style="width: 100%; padding: 10px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--bg-main); color: var(--text);">
            <option value="available">Available (Ready for Orders)</option>
            <option value="on_delivery">On Delivery (Busy)</option>
            <option value="offline">Offline (Off Duty)</option>
          </select>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 1.5rem;">
          <button type="button" class="table-action-btn" onclick="closeDriverModal()">Cancel</button>
          <button type="submit" class="nav-btn-primary" style="padding: 10px 22px; border: none; border-radius: var(--radius-sm); cursor: pointer; font-weight: 700;">
            Save Driver
          </button>
        </div>
      </form>
    </div>
  </div>

  <script src="../assets/js/admin.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      loadAdminDrivers();
    });
  </script>
</body>
</html>
