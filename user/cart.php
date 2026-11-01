<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Food Basket & Checkout - BITEBUDDY</title>
  
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
  
  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>
  
  <!-- Shared Style System -->
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body data-theme="light">

  <!-- =====================================================================
       NAVBAR (IDENTICAL USER SIDE NAVBAR)
       ===================================================================== -->
  <nav class="navbar">
    <div class="navbar-container">
      
      <!-- Brand -->
      <a href="index.php" class="nav-brand">
        <div class="logo-badge">bitebuddy</div>
        <div class="logo-text">
          <h1>BITEBUDDY</h1>
          <span>Customer Food Portal</span>
        </div>
      </a>

      <!-- Product Search Bar -->
      <div class="search-product-wrap">
        <div class="search-input-box">
          <i data-lucide="search" style="color: var(--primary); width: 18px; height: 18px;"></i>
          <input type="text" id="search-input" placeholder="Search food items, dishes, pizzas, biryani or restaurants..." autocomplete="off">
          <button type="button" id="search-clear-btn" class="search-clear-btn" onclick="clearSearch()" style="display: none;" title="Clear search">
            <i data-lucide="x" style="width: 14px; height: 14px;"></i>
          </button>
        </div>
      </div>

      <!-- Navigation & User Actions (Menu, Orders, Cart, Login, Register, Profile, Theme) -->
      <div class="nav-actions">
        
        <!-- Menu Link -->
        <a href="index.php#restaurants-section" id="nav-menu-link" class="nav-link-item" title="Browse Dishes & Restaurants">
          <i data-lucide="utensils"></i>
          <span>Menu</span>
        </a>

        <!-- Auth-Protected Navigation: Only shown when logged in (Orders & Cart) -->
        <div id="auth-protected-nav" style="display: none; align-items: center; gap: 8px;">
          <!-- Orders Link -->
          <a href="orders.php" id="nav-orders-link" class="nav-link-item" title="My Orders & Status">
            <i data-lucide="package"></i>
            <span>Orders</span>
          </a>

          <!-- Cart Button (Active on Cart Page) -->
          <a href="cart.php" id="nav-cart-link" class="nav-cart-btn active" title="View Food Basket" style="text-decoration: none;">
            <i data-lucide="shopping-bag"></i>
            <span>Cart</span>
            <span class="nav-cart-badge" id="cart-badge" style="display: none;">0</span>
          </a>
        </div>

        <!-- Login / Register Buttons (Guest State: Only shown without login) -->
        <div id="auth-guest-links" style="display: flex; align-items: center; gap: 8px;">
          <button class="nav-link-item nav-btn-outline" onclick="openAuthModal('login')" title="Customer Login">
            <i data-lucide="log-in"></i>
            <span>Login</span>
          </button>
          <button class="nav-link-item nav-btn-primary" onclick="openAuthModal('register')" title="Create Account">
            <i data-lucide="user-plus"></i>
            <span>Register</span>
          </button>
        </div>

        <!-- Logged In User State: Profile Icon & Menu -->
        <div id="auth-user-links" style="display: none; align-items: center; gap: 8px;">
          <div class="profile-dropdown-wrap">
            <button type="button" class="nav-profile-btn" id="nav-profile-toggle" onclick="toggleProfileDropdown(event)" title="My Account">
              <div class="nav-avatar-icon">
                <i data-lucide="user"></i>
              </div>
              <span id="user-display-name" class="user-display-name">Rahul Sharma</span>
              <i data-lucide="chevron-down" style="width: 14px; height: 14px; opacity: 0.7;"></i>
            </button>

            <!-- Profile Dropdown Menu -->
            <div class="profile-dropdown-menu" id="profile-dropdown-menu">
              <div class="profile-dropdown-header">
                <div class="profile-avatar-circle">
                  <i data-lucide="user" style="width: 20px; height: 20px;"></i>
                </div>
                <div class="profile-header-info">
                  <strong id="dropdown-user-name">Rahul Sharma</strong>
                  <span id="dropdown-user-email">customer@bitebuddy.com</span>
                </div>
              </div>
              <div class="profile-dropdown-divider"></div>
              <a href="orders.php" class="profile-dropdown-item">
                <i data-lucide="package"></i>
                <span>My Orders</span>
              </a>
              <a href="cart.php" class="profile-dropdown-item">
                <i data-lucide="shopping-bag"></i>
                <span>My Cart</span>
              </a>
              <div class="profile-dropdown-divider"></div>
              <button type="button" class="profile-dropdown-item logout-item" onclick="handleLogout()">
                <i data-lucide="log-out"></i>
                <span>Logout</span>
              </button>
            </div>
          </div>
        </div>

        <!-- Dark / Light Theme Toggle -->
        <button class="btn-icon" onclick="toggleTheme()" title="Toggle Dark/Light Mode">
          <i data-lucide="moon" id="theme-icon"></i>
        </button>

      </div>

    </div>
  </nav>

  <!-- =====================================================================
       FULL CART & CHECKOUT PAGE
       ===================================================================== -->
  <main class="full-cart-container">
    
    <div class="full-cart-header">
      <div>
        <h2><i data-lucide="shopping-bag" style="color: var(--primary);"></i> Your Food Basket & Checkout</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 4px;">Review your ordered dishes, delivery address, and proceed to checkout</p>
      </div>
      <a href="index.php" class="table-action-btn" style="text-decoration: none;">
        <i data-lucide="arrow-left" style="width: 14px;"></i> Add More Dishes
      </a>
    </div>

    <!-- Dynamic Cart Container -->
    <div id="full-cart-content">
      <!-- Populated via user.js -->
    </div>

  </main>

  <!-- =====================================================================
       LIVE ORDER TRACKING MODAL (Zomato Animated Tracker)
       ===================================================================== -->
  <div class="modal-overlay" id="tracking-modal">
    <div class="payment-modal-dialog" style="max-width: 580px;">
      
      <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); padding-bottom: 12px;">
        <div>
          <h3 style="font-size: 1.2rem; font-weight: 800;">Live Order Tracking</h3>
          <p style="font-size: 0.8rem; color: var(--text-muted);">Order ID: <span id="track-order-id" style="font-family: var(--font-mono); color: var(--primary); font-weight: 700;">#FMS-1001</span></p>
        </div>
        <button class="btn-icon" onclick="closeTrackingModal()"><i data-lucide="x"></i></button>
      </div>

      <!-- Animated Simulated Map Route -->
      <div class="simulated-map">
        <div class="map-route-line">
          <div class="map-route-active" id="map-route-progress"></div>
        </div>
        <div class="map-bike-marker" id="map-bike">
          <i data-lucide="bike" style="width: 20px;"></i>
        </div>
        <div style="position: absolute; bottom: 10px; left: 14px; background: rgba(0,0,0,0.65); color: #fff; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem;">
          Estimated Delivery: <strong style="color: #FBBF24;">24 mins</strong>
        </div>
      </div>

      <!-- Live Animated Stepper -->
      <div class="tracking-stepper">
        <div class="tracking-step done" id="step-1">
          <div class="step-icon-wrap"><i data-lucide="check" style="width: 18px;"></i></div>
          <div class="step-info">
            <h4>Order Placed & Confirmed</h4>
            <p>Your order has been received by restaurant.</p>
          </div>
        </div>

        <div class="tracking-step active" id="step-2">
          <div class="step-icon-wrap"><i data-lucide="chef-hat" style="width: 18px;"></i></div>
          <div class="step-info">
            <h4>Chef Vikram is Preparing Your Food</h4>
            <p>Kitchen is actively preparing your fresh delicacies.</p>
          </div>
        </div>

        <div class="tracking-step" id="step-3">
          <div class="step-icon-wrap"><i data-lucide="bike" style="width: 18px;"></i></div>
          <div class="step-info">
            <h4>Rider Amit Verma on the Way</h4>
            <p>Delivery boy assigned on Bike (MH-02-BZ-4321).</p>
          </div>
        </div>

        <div class="tracking-step" id="step-4">
          <div class="step-icon-wrap"><i data-lucide="home" style="width: 18px;"></i></div>
          <div class="step-info">
            <h4>Delivered at Doorstep</h4>
            <p>Order delivered safely at Flat 402, Royal Palms.</p>
          </div>
        </div>
      </div>

      <!-- Review & Rating Box -->
      <div id="order-review-box" style="display: none; background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 1rem; text-align: center;">
        <h4 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 6px;">How was your food and delivery?</h4>
        <div style="display: flex; justify-content: center; gap: 8px; font-size: 1.4rem; color: var(--star-gold); cursor: pointer; margin-bottom: 10px;">
          <span onclick="submitOrderRating(1)">★</span>
          <span onclick="submitOrderRating(2)">★</span>
          <span onclick="submitOrderRating(3)">★</span>
          <span onclick="submitOrderRating(4)">★</span>
          <span onclick="submitOrderRating(5)">★</span>
        </div>
      </div>

    </div>
  </div>

  <!-- =====================================================================
       LOGIN & REGISTER MODAL
       ===================================================================== -->
  <div class="modal-overlay" id="auth-modal" onclick="if(event.target === this) closeAuthModal()">
    <div class="payment-modal-dialog" style="max-width: 440px;">
      <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); padding-bottom: 12px; margin-bottom: 1rem;">
        <div>
          <h3 id="auth-modal-title" style="font-size: 1.25rem; font-weight: 800;">Welcome to BITEBUDDY</h3>
          <p style="font-size: 0.8rem; color: var(--text-muted);">Sign in to access your saved addresses & faster checkout</p>
        </div>
        <button class="btn-icon" onclick="closeAuthModal()"><i data-lucide="x"></i></button>
      </div>

      <div class="auth-tabs">
        <div class="auth-tab active" id="tab-login" onclick="switchAuthTab('login')">
          <i data-lucide="log-in" style="width: 15px; display: inline-block; vertical-align: middle;"></i> Sign In
        </div>
        <div class="auth-tab" id="tab-register" onclick="switchAuthTab('register')">
          <i data-lucide="user-plus" style="width: 15px; display: inline-block; vertical-align: middle;"></i> New Register
        </div>
      </div>

      <form id="login-form" onsubmit="handleLoginSubmit(event)">
        <div class="auth-input-group">
          <label>Email Address</label>
          <input type="email" id="login-email" placeholder="e.g. rahul@gmail.com" required value="rahul@gmail.com">
        </div>
        <div class="auth-input-group">
          <label>Password</label>
          <input type="password" id="login-pass" placeholder="Enter password" required value="password123">
        </div>
        <button type="submit" class="btn-checkout" style="width: 100%; justify-content: center; margin-top: 1rem;">
          <i data-lucide="log-in" style="width: 16px;"></i> Sign In & Order
        </button>
      </form>

      <form id="register-form" onsubmit="handleRegisterSubmit(event)" style="display: none;">
        <div class="auth-input-group">
          <label>Full Name</label>
          <input type="text" id="reg-name" placeholder="e.g. Priya Patel" required>
        </div>
        <div class="auth-input-group">
          <label>Email Address</label>
          <input type="email" id="reg-email" placeholder="e.g. priya@gmail.com" required>
        </div>
        <div class="auth-input-group">
          <label>Phone Number</label>
          <input type="tel" id="reg-phone" placeholder="e.g. +91 9822233344" required>
        </div>
        <div class="auth-input-group">
          <label>Password</label>
          <input type="password" id="reg-pass" placeholder="Create password" required>
        </div>
        <button type="submit" class="btn-checkout" style="width: 100%; justify-content: center; margin-top: 1rem;">
          <i data-lucide="user-plus" style="width: 16px;"></i> Create BITEBUDDY Account
        </button>
      </form>

      <p style="font-size: 0.76rem; color: var(--text-muted); text-align: center; margin-top: 1.25rem;">
        🔒 Safe & Secure 256-bit SSL authentication.
      </p>
    </div>
  </div>

  <!-- User Controller Script -->
  <script src="../assets/js/user.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      renderFullCartPage();
    });
  </script>
</body>
</html>

