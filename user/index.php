<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BITEBUDDY - Customer Online Food Ordering</title>
  
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
  
  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>
  
  <!-- Shared Style System -->
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body data-theme="light">

  <!-- =====================================================================
       NAVBAR
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
        
        <!-- Menu Link (Active on Index) -->
        <a href="#restaurants-section" id="nav-menu-link" class="nav-link-item active" title="Browse Dishes & Restaurants">
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

          <!-- Cart Button -->
          <a href="cart.php" id="nav-cart-link" class="nav-cart-btn" title="View Food Basket" style="text-decoration: none;">
            <i data-lucide="shopping-bag"></i>
            <span>Cart</span>
            <span class="nav-cart-badge" id="cart-badge" style="display: none;">0</span>
          </a>
        </div>

        <!-- Login / Register Buttons (Guest State: Only shown without login) -->
        <div id="auth-guest-links" style="display: flex; align-items: center; gap: 8px;">
          <a href="login.php" class="nav-link-item nav-btn-outline" title="Customer Login">
            <i data-lucide="log-in"></i>
            <span>Login</span>
          </a>
          <a href="login.php?tab=register" class="nav-link-item nav-btn-primary" title="Create Account">
            <i data-lucide="user-plus"></i>
            <span>Register</span>
          </a>
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
       MAIN CUSTOMER VIEW
       ===================================================================== -->
  <main class="portal-view active">
    
    <!-- Hero / Inspiration Categories -->
    <section class="hero-banner">
      <div class="hero-content">
        <div class="hero-heading-row">
          <div class="hero-heading">
            <h2>Inspiration for your first order</h2>
            <p>Explore mouth-watering dishes crafted by top chefs</p>
          </div>
          <div class="carousel-nav-controls">
            <button class="carousel-arrow-btn" id="cat-scroll-prev" onclick="scrollCategoryCarousel(-1)" title="Previous categories" aria-label="Previous">
              <i data-lucide="chevron-left"></i>
            </button>
            <button class="carousel-arrow-btn" id="cat-scroll-next" onclick="scrollCategoryCarousel(1)" title="Next categories" aria-label="Next">
              <i data-lucide="chevron-right"></i>
            </button>
          </div>
        </div>

        <div class="category-carousel-wrapper">
          <div class="category-strip" id="category-strip">
            <div class="category-circle-item" data-cat="biryani" onclick="filterByCategory('biryani')">
              <div class="category-circle-img">
                <img src="https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=300&auto=format&fit=crop&q=80" alt="Biryani" loading="lazy">
              </div>
              <span>Biryani</span>
            </div>

            <div class="category-circle-item" data-cat="pizza" onclick="filterByCategory('pizza')">
              <div class="category-circle-img">
                <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?w=300&auto=format&fit=crop&q=80" alt="Pizza" loading="lazy">
              </div>
              <span>Pizza</span>
            </div>

            <div class="category-circle-item" data-cat="north indian" onclick="filterByCategory('north indian')">
              <div class="category-circle-img">
                <img src="https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=300&auto=format&fit=crop&q=80" alt="North Indian" loading="lazy">
              </div>
              <span>North Indian</span>
            </div>

            <div class="category-circle-item" data-cat="burger" onclick="filterByCategory('burger')">
              <div class="category-circle-img">
                <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=300&auto=format&fit=crop&q=80" alt="Burgers" loading="lazy">
              </div>
              <span>Burgers</span>
            </div>

            <div class="category-circle-item" data-cat="asian" onclick="filterByCategory('asian')">
              <div class="category-circle-img">
                <img src="https://images.unsplash.com/photo-1496116218417-1a781b1c416c?w=300&auto=format&fit=crop&q=80" alt="Asian & Dimsums" loading="lazy">
              </div>
              <span>Asian & Dimsums</span>
            </div>

            <div class="category-circle-item" data-cat="rolls" onclick="filterByCategory('rolls')">
              <div class="category-circle-img">
                <img src="https://images.unsplash.com/photo-1626700051175-6818013e1d4f?w=300&auto=format&fit=crop&q=80" alt="Rolls & Wraps" loading="lazy">
              </div>
              <span>Rolls & Wraps</span>
            </div>

            <div class="category-circle-item" data-cat="south indian" onclick="filterByCategory('south indian')">
              <div class="category-circle-img">
                <img src="https://images.unsplash.com/photo-1668236543090-82eba5ee5976?w=300&auto=format&fit=crop&q=80" alt="South Indian" loading="lazy">
              </div>
              <span>South Indian</span>
            </div>

            <div class="category-circle-item" data-cat="dessert" onclick="filterByCategory('dessert')">
              <div class="category-circle-img">
                <img src="https://images.unsplash.com/photo-1551024709-8f23befc6f87?w=300&auto=format&fit=crop&q=80" alt="Desserts" loading="lazy">
              </div>
              <span>Desserts</span>
            </div>

            <div class="category-circle-item" data-cat="chinese" onclick="filterByCategory('chinese')">
              <div class="category-circle-img">
                <img src="https://images.unsplash.com/photo-1585032226651-759b368d7246?w=300&auto=format&fit=crop&q=80" alt="Chinese & Noodles" loading="lazy">
              </div>
              <span>Chinese & Noodles</span>
            </div>

            <div class="category-circle-item" data-cat="pasta" onclick="filterByCategory('pasta')">
              <div class="category-circle-img">
                <img src="https://images.unsplash.com/photo-1621996346565-e3d5d6281699?w=300&auto=format&fit=crop&q=80" alt="Pasta" loading="lazy">
              </div>
              <span>Pasta</span>
            </div>

            <div class="category-circle-item" data-cat="street food" onclick="filterByCategory('street food')">
              <div class="category-circle-img">
                <img src="https://images.unsplash.com/photo-1601050690597-df0568f70950?w=300&auto=format&fit=crop&q=80" alt="Street Food" loading="lazy">
              </div>
              <span>Street Food</span>
            </div>

            <div class="category-circle-item" data-cat="beverages" onclick="filterByCategory('beverages')">
              <div class="category-circle-img">
                <img src="https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=300&auto=format&fit=crop&q=80" alt="Shakes & Drinks" loading="lazy">
              </div>
              <span>Shakes & Drinks</span>
            </div>

            <div class="category-circle-item" data-cat="healthy" onclick="filterByCategory('healthy')">
              <div class="category-circle-img">
                <img src="https://images.unsplash.com/photo-1540420773420-3366772f4999?w=300&auto=format&fit=crop&q=80" alt="Healthy Bowls" loading="lazy">
              </div>
              <span>Healthy Bowls</span>
            </div>

            <div class="category-circle-item" data-cat="ice cream" onclick="filterByCategory('ice cream')">
              <div class="category-circle-img">
                <img src="https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=300&auto=format&fit=crop&q=80" alt="Ice Cream" loading="lazy">
              </div>
              <span>Ice Cream</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Main Restaurants Section -->
    <section class="section-container" id="restaurants-section">
      
      <!-- Filter Chips -->
      <div class="filter-tags-strip">
        <button class="filter-chip active" data-filter="all" onclick="setFilter('all')">
          <i data-lucide="sliders-horizontal" style="width: 14px;"></i> All Restaurants
        </button>
        <button class="filter-chip" data-filter="rating" onclick="setFilter('rating')">
          <i data-lucide="star" style="width: 14px;"></i> Rating 4.5+
        </button>
        <button class="filter-chip" data-filter="veg" onclick="setFilter('veg')">
          <i data-lucide="leaf" style="width: 14px; color: var(--veg-green);"></i> Pure Veg
        </button>
        <button class="filter-chip" data-filter="biryani" onclick="setFilter('biryani')">
          🍗 Biryani Specials
        </button>
        <button class="filter-chip" data-filter="pizza" onclick="setFilter('pizza')">
          🍕 Wood-fired Pizza
        </button>
      </div>

      <div class="section-title">
        <h3>Top Restaurants with Online Food Delivery</h3>
      </div>

      <!-- Restaurant Grid -->
      <div class="restaurant-grid" id="restaurant-grid">
        <!-- Rendered dynamically via user.js -->
      </div>

    </section>
  </main>

  <!-- =====================================================================
       RESTAURANT MENU MODAL
       ===================================================================== -->
  <div class="modal-overlay" id="menu-modal" onclick="if(event.target === this) closeMenuModal()">
    <div class="menu-modal-dialog">
      <div class="modal-header-hero">
        <button class="close-modal-btn" onclick="closeMenuModal()"><i data-lucide="x"></i></button>
        <h2 id="menu-modal-title" style="font-size: 1.4rem; font-weight: 800; margin-bottom: 4px;">Restaurant Name</h2>
        <div id="menu-modal-cuisine" style="color: var(--text-muted); font-size: 0.88rem;">Cuisine info</div>
        <div id="menu-modal-address" style="color: var(--text-light); font-size: 0.8rem; margin-top: 4px;">Address details</div>
      </div>

      <div class="menu-list-body" id="menu-items-body">
        <!-- Rendered dynamically -->
      </div>
    </div>
  </div>

  <!-- =====================================================================
       CART SLIDE-OUT DRAWER
       ===================================================================== -->
  <div class="cart-drawer-overlay" id="cart-overlay" onclick="toggleCartDrawer()"></div>
  <div class="cart-drawer" id="cart-drawer">
    <div class="cart-header">
      <h3><i data-lucide="shopping-bag" style="color: var(--primary);"></i> Your Food Basket</h3>
      <button class="btn-icon" onclick="toggleCartDrawer()"><i data-lucide="x"></i></button>
    </div>

    <div class="cart-items-body" id="cart-items-list">
      <!-- Rendered dynamically -->
    </div>

    <!-- Delivery Address Box -->
    <div style="padding: 0 1.5rem;">
      <div class="cart-address-box" id="cart-address-select">
        <!-- Populated via user.js -->
      </div>
    </div>

    <!-- Promo Code Box -->
    <div style="padding: 0.75rem 1.5rem; display: flex; gap: 8px;">
      <input type="text" id="coupon-input" placeholder="Enter coupon code (e.g. BITEBUDDY50)" 
             style="flex: 1; padding: 8px 12px; border: 1px dashed var(--primary); border-radius: 8px; font-size: 0.82rem; background: var(--bg); color: var(--text); outline: none;">
      <button class="filter-chip active" onclick="applyCoupon()" style="padding: 8px 14px;">Apply</button>
    </div>

    <!-- Bill Summary -->
    <div class="cart-bill-summary">
      <div class="bill-row">
        <span>Item Total</span>
        <span id="bill-subtotal">₹0.00</span>
      </div>
      <div class="bill-row">
        <span>Delivery Partner Fee</span>
        <span id="bill-delivery">₹30.00</span>
      </div>
      <div class="bill-row">
        <span>Taxes & Restaurant Charges (5%)</span>
        <span id="bill-tax">₹0.00</span>
      </div>
      <div class="bill-row" style="color: var(--veg-green); font-weight: 600; display: none;">
        <span>Promo Discount</span>
        <span id="bill-discount">-₹0.00</span>
      </div>
      <div class="bill-row total">
        <span>To Pay</span>
        <span id="bill-grand-total">₹0.00</span>
      </div>
    </div>

    <div class="cart-footer-btn" style="display: flex; flex-direction: column; gap: 8px;">
      <button class="btn-checkout" onclick="openCheckoutModal()">
        <span>Proceed to Pay</span>
        <span id="checkout-btn-total">₹0.00</span>
      </button>
      <a href="cart.php" class="nav-link-item nav-btn-outline" style="justify-content: center; text-align: center; border-radius: var(--radius-sm); font-size: 0.88rem; padding: 10px;">
        <i data-lucide="shopping-cart"></i> View Full Cart & Checkout Page →
      </a>
    </div>
  </div>

  <!-- =====================================================================
       CHECKOUT & PAYMENT MODAL
       ===================================================================== -->
  <div class="modal-overlay" id="checkout-modal" onclick="if(event.target === this) closeCheckoutModal()">
    <div class="payment-modal-dialog">
      <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); padding-bottom: 12px;">
        <h3 style="font-size: 1.2rem; font-weight: 800;">Choose Payment Method</h3>
        <button class="btn-icon" onclick="closeCheckoutModal()"><i data-lucide="x"></i></button>
      </div>

      <div class="payment-options-grid">
        <div class="payment-opt-card selected" onclick="selectPaymentMethod(this, 'UPI')">
          <i data-lucide="smartphone" style="color: var(--primary);"></i>
          <div>UPI (GPay / PhonePe)</div>
        </div>
        <div class="payment-opt-card" onclick="selectPaymentMethod(this, 'Card')">
          <i data-lucide="credit-card" style="color: var(--info-blue);"></i>
          <div>Credit / Debit Card</div>
        </div>
        <div class="payment-opt-card" onclick="selectPaymentMethod(this, 'NetBanking')">
          <i data-lucide="building" style="color: var(--accent-purple);"></i>
          <div>Net Banking</div>
        </div>
        <div class="payment-opt-card" onclick="selectPaymentMethod(this, 'COD')">
          <i data-lucide="banknote" style="color: var(--veg-green);"></i>
          <div>Cash on Delivery</div>
        </div>
      </div>

      <p style="font-size: 0.8rem; color: var(--text-muted);">
        🔒 256-Bit SSL Encrypted & PCI-DSS Compliant Payment Gateway.
      </p>

      <button id="pay-now-btn" class="btn-checkout" onclick="processPaymentAndPlaceOrder()" style="justify-content: center;">
        Place Order & Pay
      </button>
    </div>
  </div>

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
       CUSTOMER LOGIN & REGISTER MODAL
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

      <!-- Auth Tabs -->
      <div class="auth-tabs">
        <div class="auth-tab active" id="tab-login" onclick="switchAuthTab('login')">
          <i data-lucide="log-in" style="width: 15px; display: inline-block; vertical-align: middle;"></i> Sign In
        </div>
        <div class="auth-tab" id="tab-register" onclick="switchAuthTab('register')">
          <i data-lucide="user-plus" style="width: 15px; display: inline-block; vertical-align: middle;"></i> New Register
        </div>
      </div>

      <!-- Login Form -->
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

      <!-- Register Form -->
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
</body>
</html>

