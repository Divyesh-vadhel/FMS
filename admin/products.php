<?php
/**
 * Food Management System (FMS) - BITEBUDDY Admin Portal
 * Product Management: Dishes, Pricing, Veg/Non-Veg, Categories, Live Stock
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
  <title>Manage Products - BITEBUDDY Admin Portal</title>
  
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
      max-width: 600px;
      width: 90%;
      border: 1px solid var(--border);
      box-shadow: 0 20px 45px rgba(0,0,0,0.2);
    }
    .form-grid-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }
    @media (max-width: 640px) {
      .form-grid-2 { grid-template-columns: 1fr; }
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
        <a href="products.php" class="nav-link-item active">
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
    
    <!-- Header Strip -->
    <div class="admin-header-strip">
      <div class="admin-title-group">
        <h2><i data-lucide="utensils" style="color: var(--primary);"></i> Manage Products & Dishes</h2>
        <p>Add new dishes, update pricing, change categories, toggle stock availability & photos</p>
      </div>
      <div style="display: flex; gap: 10px;">
        <button class="nav-btn-primary" onclick="openAddProductModal()" style="display: flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: var(--radius-sm); font-weight: 700; border: none; cursor: pointer;">
          <i data-lucide="plus" style="width: 16px;"></i> Add New Product
        </button>
        <button class="table-action-btn" onclick="loadAdminProducts()">
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
      <a href="products.php" class="admin-nav-item active">
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

    <!-- Metric Cards -->
    <div class="admin-stats-grid">
      <div class="admin-stat-card">
        <div class="stat-icon-wrapper" style="background: rgba(235, 43, 86, 0.1); color: var(--primary);">
          <i data-lucide="utensils"></i>
        </div>
        <div class="stat-info">
          <span class="stat-label">Total Products</span>
          <h3 class="stat-value" id="stat-prod-total">39</h3>
        </div>
      </div>
      <div class="admin-stat-card">
        <div class="stat-icon-wrapper" style="background: rgba(16, 185, 129, 0.1); color: var(--veg-green);">
          <i data-lucide="leaf"></i>
        </div>
        <div class="stat-info">
          <span class="stat-label">Pure Veg Items</span>
          <h3 class="stat-value" id="stat-prod-veg">24</h3>
        </div>
      </div>
      <div class="admin-stat-card">
        <div class="stat-icon-wrapper" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
          <i data-lucide="drumstick"></i>
        </div>
        <div class="stat-info">
          <span class="stat-label">Non-Veg Items</span>
          <h3 class="stat-value" id="stat-prod-nonveg">15</h3>
        </div>
      </div>
      <div class="admin-stat-card">
        <div class="stat-icon-wrapper" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
          <i data-lucide="check-circle-2"></i>
        </div>
        <div class="stat-info">
          <span class="stat-label">In Stock Active</span>
          <h3 class="stat-value" id="stat-prod-instock">39</h3>
        </div>
      </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="admin-card" style="margin-bottom: 1.5rem; padding: 1.25rem 1.5rem;">
      <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem;">
        
        <div style="display: flex; align-items: center; gap: 10px; flex: 1; max-width: 400px;">
          <div class="search-box-wrap" style="width: 100%; position: relative;">
            <input type="text" id="product-search-input" placeholder="Search dish by name or description..." 
                   oninput="handleProductSearch(this.value)"
                   style="width: 100%; padding: 10px 14px 10px 38px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--bg-main); color: var(--text);">
            <i data-lucide="search" style="position: absolute; left: 12px; top: 12px; width: 16px; height: 16px; color: var(--text-muted);"></i>
          </div>
        </div>

        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
          <button class="filter-chip active" data-filter="all" onclick="filterProductsCategory('all')">All</button>
          <button class="filter-chip" data-filter="Biryani" onclick="filterProductsCategory('Biryani')">Biryani</button>
          <button class="filter-chip" data-filter="Pizza" onclick="filterProductsCategory('Pizza')">Pizza</button>
          <button class="filter-chip" data-filter="North Indian" onclick="filterProductsCategory('North Indian')">North Indian</button>
          <button class="filter-chip" data-filter="Burgers" onclick="filterProductsCategory('Burgers')">Burgers</button>
          <button class="filter-chip" data-filter="Asian" onclick="filterProductsCategory('Asian')">Asian</button>
          <button class="filter-chip" data-filter="South Indian" onclick="filterProductsCategory('South Indian')">South Indian</button>
          <button class="filter-chip" data-filter="Desserts" onclick="filterProductsCategory('Desserts')">Desserts</button>
        </div>

      </div>
    </div>

    <!-- Products Table -->
    <div class="admin-card">
      <div class="admin-table-wrapper">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Dish Photo</th>
              <th>Dish Details</th>
              <th>Restaurant</th>
              <th>Category</th>
              <th>Price</th>
              <th>Type</th>
              <th>Stock Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="products-table-body">
            <!-- Rendered dynamically via admin.js -->
          </tbody>
        </table>
      </div>
    </div>

  </main>

  <!-- ADD / EDIT PRODUCT MODAL -->
  <div class="modal-overlay" id="product-modal" onclick="if(event.target === this) closeProductModal()">
    <div class="modal-dialog-custom">
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border);">
        <h3 id="product-modal-title" style="font-size: 1.3rem; font-weight: 800;">Add New Product</h3>
        <button class="close-modal-btn" onclick="closeProductModal()"><i data-lucide="x"></i></button>
      </div>

      <form id="product-form" onsubmit="handleProductFormSubmit(event)">
        <input type="hidden" id="prod-form-id" value="">

        <div class="form-grid-2">
          <div class="auth-form-group">
            <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 6px;">Product Name</label>
            <input type="text" id="prod-name" placeholder="Special Chicken Biryani" required
                   style="width: 100%; padding: 10px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--bg-main); color: var(--text);">
          </div>

          <div class="auth-form-group">
            <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 6px;">Restaurant Outlet</label>
            <select id="prod-restaurant" required
                    style="width: 100%; padding: 10px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--bg-main); color: var(--text);">
              <option value="1">Royal Dum Biryani House</option>
              <option value="2">Pizza Gusto & Italian Cafe</option>
              <option value="3">Tandoor & Curry Express</option>
              <option value="4">The Burger Club & Cafe</option>
              <option value="5">Wok & Roll Asian Kitchen</option>
              <option value="6">South Feast Dosa Corner</option>
              <option value="7">The Rollery & Shawarma House</option>
              <option value="8">Sweet Treats Bakery & Desserts</option>
              <option value="9">Green Bowl & Healthy Kitchen</option>
              <option value="10">Chaat Gali & Street Bites</option>
            </select>
          </div>
        </div>

        <div class="form-grid-2">
          <div class="auth-form-group">
            <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 6px;">Category</label>
            <input type="text" id="prod-category" placeholder="Biryani, Pizza, Starters..." required
                   style="width: 100%; padding: 10px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--bg-main); color: var(--text);">
          </div>

          <div class="auth-form-group">
            <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 6px;">Price (₹)</label>
            <input type="number" id="prod-price" step="0.01" min="1" placeholder="299.00" required
                   style="width: 100%; padding: 10px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--bg-main); color: var(--text);">
          </div>
        </div>

        <div class="form-grid-2">
          <div class="auth-form-group">
            <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 6px;">Food Type</label>
            <select id="prod-veg"
                    style="width: 100%; padding: 10px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--bg-main); color: var(--text);">
              <option value="1">Pure Veg 🥬</option>
              <option value="0">Non-Veg 🍗</option>
            </select>
          </div>

          <div class="auth-form-group">
            <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 6px;">Stock Status</label>
            <select id="prod-available"
                    style="width: 100%; padding: 10px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--bg-main); color: var(--text);">
              <option value="1">In Stock (Available)</option>
              <option value="0">Out of Stock</option>
            </select>
          </div>
        </div>

        <div class="auth-form-group">
          <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 6px;">Image URL</label>
          <input type="url" id="prod-image" placeholder="https://images.unsplash.com/photo-..." 
                 style="width: 100%; padding: 10px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--bg-main); color: var(--text);">
        </div>

        <div class="auth-form-group">
          <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 6px;">Description</label>
          <textarea id="prod-desc" rows="3" placeholder="Appetizing description of flavors, ingredients, preparation..."
                    style="width: 100%; padding: 10px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--bg-main); color: var(--text);"></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 1.5rem;">
          <button type="button" class="table-action-btn" onclick="closeProductModal()">Cancel</button>
          <button type="submit" class="nav-btn-primary" style="padding: 10px 22px; border: none; border-radius: var(--radius-sm); cursor: pointer; font-weight: 700;">
            Save Product
          </button>
        </div>
      </form>
    </div>
  </div>

  <script src="../assets/js/admin.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      loadAdminProducts();
    });
  </script>
</body>
</html>

