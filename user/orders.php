<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Orders - BITEBUDDY Food Management System</title>
  
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
        <div class="logo-badge">bitebuddy</div>
        <div class="logo-text">
          <h1>BITEBUDDY</h1>
          <span>My Orders</span>
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
          <!-- Orders Link (Active on Orders Page) -->
          <a href="orders.php" id="nav-orders-link" class="nav-link-item active" title="My Orders & Status">
            <i data-lucide="package"></i>
            <span>Orders</span>
          </a>

          <!-- Cart Link -->
          <a href="cart.php" id="nav-cart-link" class="nav-cart-btn" title="View Food Basket" style="text-decoration: none;">
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

  <main class="section-container" style="padding-top: 2rem;">
    <div class="portal-header">
      <div>
        <h2 style="font-size: 1.6rem; font-weight: 800; display: flex; align-items: center; gap: 10px;">
          <i data-lucide="package" style="color: var(--primary);"></i> Order History & Status
        </h2>
        <p style="color: var(--text-muted); font-size: 0.9rem;">
          Viewing recent food orders for customer: <strong id="orders-customer-name">Rahul Sharma</strong>
        </p>
      </div>
      <a href="index.php" class="filter-chip active" style="text-decoration: none;">
        <i data-lucide="plus" style="width: 14px;"></i> Order More Food
      </a>
    </div>

    <!-- Active Orders Grid -->
    <div class="orders-ticket-grid" id="customer-orders-grid">
      <!-- Populated via script -->
    </div>
  </main>

  <!-- Live Tracking Modal -->
  <div class="modal-overlay" id="tracking-modal">
    <div class="payment-modal-dialog" style="max-width: 580px;">
      <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); padding-bottom: 12px;">
        <div>
          <h3 style="font-size: 1.2rem; font-weight: 800;">Live Order Tracking</h3>
          <p style="font-size: 0.8rem; color: var(--text-muted);">Order ID: <span id="track-order-id" style="font-family: var(--font-mono); color: var(--primary); font-weight: 700;">#FMS-1001</span></p>
        </div>
        <button class="btn-icon" onclick="closeTrackingModal()"><i data-lucide="x"></i></button>
      </div>

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
    </div>
  </div>

  <!-- LOGIN & REGISTER MODAL -->
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
    </div>
  </div>

  <script src="../assets/js/user.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', async () => {
      loadCustomerOrdersPage();
    });

    async function loadCustomerOrdersPage() {
      const grid = document.getElementById('customer-orders-grid');
      const savedUser = localStorage.getItem('fms_user');
      const nameEl = document.getElementById('orders-customer-name');

      if (!savedUser) {
        if (nameEl) nameEl.parentElement.style.display = 'none';
        if (grid) {
          grid.innerHTML = `
            <div class="cart-empty-box" style="grid-column: 1/-1;">
              <div class="cart-empty-icon" style="background: rgba(226, 55, 68, 0.1); color: var(--primary);">
                <i data-lucide="lock" style="width: 36px; height: 36px;"></i>
              </div>
              <h3 style="font-size: 1.4rem; font-weight: 800; margin-bottom: 8px;">Please Sign In to View Your Orders</h3>
              <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 2rem;">
                You must be logged in to track live food deliveries and view your order history.
              </p>
              <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                <button class="nav-btn-primary" onclick="openAuthModal('login')" style="padding: 10px 24px; border-radius: var(--radius-full); font-weight: 700; cursor: pointer; border: none;">
                  <i data-lucide="log-in"></i> Sign In Now
                </button>
                <a href="index.php" class="nav-link-item nav-btn-outline" style="padding: 10px 24px; border-radius: var(--radius-full); font-weight: 700; text-decoration: none;">
                  <i data-lucide="utensils"></i> Browse Food Menu
                </a>
              </div>
            </div>
          `;
          initLucide();
        }
        return;
      }

      try {
        const u = JSON.parse(savedUser);
        if (nameEl) {
          nameEl.parentElement.style.display = 'block';
          nameEl.innerText = u.name || 'Rahul Sharma';
        }
      } catch (e) {}

      try {
        const res = await fetch('../api/index.php?action=get_orders&role=customer&user_id=1');
        const json = await res.json();
        const orders = json.data || [];
        renderCustomerOrders(orders);
      } catch(e) {
        renderCustomerOrders([
          { order_id: 1, restaurant_name: 'Tandoor & Curry Express', tot_amount: '470.00', status: 'delivered', items: [{ item_name: 'Paneer Butter Masala', quantity: 1 }, { item_name: 'Butter Garlic Naan', quantity: 2 }] },
          { order_id: 3, restaurant_name: 'Royal Dum Biryani House', tot_amount: '450.00', status: 'out_for_delivery', items: [{ item_name: 'Special Chicken Dum Biryani', quantity: 1 }] }
        ]);
      }
    }

    function renderCustomerOrders(orders) {
      const grid = document.getElementById('customer-orders-grid');
      if (!grid) return;
      if (orders.length === 0) {
        grid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: var(--text-muted); padding: 3rem;">No past orders found.</p>';
        return;
      }
      grid.innerHTML = orders.map(o => `
        <div class="ticket-card">
          <div class="ticket-header">
            <div>
              <strong>Order #FMS-${o.order_id}</strong>
              <div style="font-size: 0.8rem; color: var(--text-muted);">${o.restaurant_name}</div>
            </div>
            <span class="status-badge status-${o.status}">${o.status}</span>
          </div>
          <div style="font-size: 0.85rem; line-height: 1.6;">
            ${(o.items || []).map(it => `<div>• <strong>${it.quantity}x</strong> ${it.item_name}</div>`).join('')}
          </div>
          <div style="margin-top: auto; padding-top: 10px; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <div style="font-weight: 800; color: var(--primary);">₹${parseFloat(o.tot_amount).toFixed(2)}</div>
            <button class="filter-chip active" onclick="launchLiveOrderTracking(${o.order_id})">
              <i data-lucide="map-pin" style="width: 14px;"></i> Track
            </button>
          </div>
        </div>
      `).join('');
      initLucide();
    }
  </script>
</body>
</html>

