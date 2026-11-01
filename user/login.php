<?php
/**
 * Food Management System (FMS) - BITEBUDDY Customer Sign In & Registration
 * Redirects customers here when attempting to add items without authentication.
 */
session_start();

// If already signed in in PHP session, preserve add_dish and redirect to storefront
if (!empty($_SESSION['user_logged_in'])) {
    $add_dish = !empty($_GET['add_dish']) ? '?auto_add=' . urlencode($_GET['add_dish']) : '';
    header("Location: index.php" . $add_dish);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Sign In & Register - BITEBUDDY</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">

  <script src="https://unpkg.com/lucide@latest"></script>
  <link rel="stylesheet" href="../assets/css/styles.css?v=<?= time() ?>">
  <style>
    .auth-page-wrapper {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      background: linear-gradient(135deg, rgba(235, 43, 86, 0.04) 0%, rgba(255, 107, 107, 0.08) 100%);
    }

    .auth-container {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2.5rem 1rem;
    }

    .auth-card-box {
      width: 100%;
      max-width: 460px;
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 2.5rem 2rem;
      box-shadow: 0 16px 40px rgba(0, 0, 0, 0.08);
      animation: fadeInScale 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes fadeInScale {
      from { opacity: 0; transform: scale(0.96) translateY(10px); }
      to { opacity: 1; transform: scale(1) translateY(0); }
    }

    .auth-banner-alert {
      background: var(--primary-light);
      border: 1px solid rgba(235, 43, 86, 0.2);
      color: var(--primary);
      padding: 0.85rem 1rem;
      border-radius: var(--radius-sm);
      font-size: 0.85rem;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 1.5rem;
    }

    .auth-toggle-tabs {
      display: flex;
      background: var(--bg-main);
      padding: 4px;
      border-radius: var(--radius-md);
      margin-bottom: 1.75rem;
      border: 1px solid var(--border);
    }

    .auth-tab-btn {
      flex: 1;
      padding: 10px;
      text-align: center;
      font-size: 0.9rem;
      font-weight: 700;
      border: none;
      background: transparent;
      color: var(--text-muted);
      border-radius: var(--radius-sm);
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .auth-tab-btn.active {
      background: var(--bg-card);
      color: var(--primary);
      box-shadow: var(--shadow-sm);
    }

    .auth-form-group {
      margin-bottom: 1.2rem;
    }

    .auth-form-group label {
      display: block;
      font-size: 0.82rem;
      font-weight: 700;
      color: var(--text);
      margin-bottom: 6px;
      text-transform: uppercase;
      letter-spacing: 0.03em;
    }

    .auth-input-wrap {
      position: relative;
      display: flex;
      align-items: center;
    }

    .auth-input-wrap i {
      position: absolute;
      left: 14px;
      width: 18px;
      height: 18px;
      color: var(--text-light);
    }

    .auth-input-wrap input {
      width: 100%;
      padding: 12px 14px 12px 42px;
      border-radius: var(--radius-sm);
      border: 1px solid var(--border);
      background: var(--bg-main);
      color: var(--text);
      font-size: 0.92rem;
      outline: none;
      transition: var(--transition);
    }

    .auth-input-wrap input:focus {
      border-color: var(--primary);
      background: var(--bg-card);
      box-shadow: 0 0 0 3px rgba(235, 43, 86, 0.12);
    }

    .auth-btn-submit {
      width: 100%;
      padding: 13px;
      border-radius: var(--radius-sm);
      background: var(--primary);
      color: #fff;
      font-size: 0.95rem;
      font-weight: 700;
      border: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      box-shadow: 0 6px 18px rgba(235, 43, 86, 0.3);
      transition: all 0.2s ease;
      margin-top: 1.25rem;
    }

    .auth-btn-submit:hover {
      background: var(--primary-hover);
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(235, 43, 86, 0.4);
    }

    .demo-login-box {
      margin-top: 1.75rem;
      padding-top: 1.25rem;
      border-top: 1px dashed var(--border);
      text-align: center;
    }

    .demo-btn-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 16px;
      border-radius: var(--radius-full);
      background: var(--bg-main);
      border: 1px solid var(--border);
      font-size: 0.82rem;
      font-weight: 600;
      color: var(--text-muted);
      cursor: pointer;
      transition: all 0.2s;
    }

    .demo-btn-pill:hover {
      color: var(--primary);
      border-color: var(--primary);
      background: var(--primary-light);
    }
  </style>
</head>
<body data-theme="light">

  <div class="auth-page-wrapper">
    
    <!-- Top Minimal Navbar -->
    <nav class="navbar" style="border-bottom: 1px solid var(--border);">
      <div class="navbar-container" style="justify-content: space-between;">
        <a href="index.php" class="nav-brand">
          <div class="logo-badge">bitebuddy</div>
          <div class="logo-text">
            <h1>BITEBUDDY</h1>
            <span>CUSTOMER FOOD PORTAL</span>
          </div>
        </a>

        <div style="display: flex; align-items: center; gap: 12px;">
          <a href="index.php" class="nav-link-item" style="font-size: 0.85rem;">
            <i data-lucide="arrow-left" style="width: 16px;"></i> Return to Store
          </a>
          <button class="btn-icon" onclick="toggleTheme()" title="Toggle Theme">
            <i data-lucide="moon" id="theme-icon"></i>
          </button>
        </div>
      </div>
    </nav>

    <!-- Main Auth Card -->
    <div class="auth-container">
      <div class="auth-card-box">
        
        <!-- Flash Notice when redirected from Adding an Item -->
        <div class="auth-banner-alert" id="auth-banner-notice" style="display: none;">
          <i data-lucide="info" style="width: 18px; height: 18px; flex-shrink: 0;"></i>
          <span>Please sign in or create an account to add delicious dishes to your order!</span>
        </div>

        <!-- Heading -->
        <div style="text-align: center; margin-bottom: 1.5rem;">
          <h2 id="auth-main-title" style="font-size: 1.55rem; font-weight: 800; letter-spacing: -0.03em;">Welcome to BITEBUDDY</h2>
          <p id="auth-main-desc" style="color: var(--text-muted); font-size: 0.88rem; margin-top: 4px;">Sign in to place orders, track live deliveries & save addresses</p>
        </div>

        <!-- Tabs -->
        <div class="auth-toggle-tabs">
          <button type="button" class="auth-tab-btn active" id="btn-tab-login" onclick="setAuthTab('login')">
            <i data-lucide="log-in" style="width: 15px; display: inline; vertical-align: middle;"></i> Sign In
          </button>
          <button type="button" class="auth-tab-btn" id="btn-tab-register" onclick="setAuthTab('register')">
            <i data-lucide="user-plus" style="width: 15px; display: inline; vertical-align: middle;"></i> Create Account
          </button>
        </div>

        <!-- Form 1: Customer Login -->
        <form id="customer-login-form" onsubmit="handleCustomerLoginSubmit(event)">
          <div class="auth-form-group">
            <label>Email Address or Username</label>
            <div class="auth-input-wrap">
              <i data-lucide="mail"></i>
              <input type="text" id="cust-login-email" placeholder="customer@bitebuddy.com" required autocomplete="username">
            </div>
          </div>

          <div class="auth-form-group">
            <label>Password</label>
            <div class="auth-input-wrap">
              <i data-lucide="lock"></i>
              <input type="password" id="cust-login-password" placeholder="••••••••" required autocomplete="current-password">
            </div>
          </div>

          <button type="submit" class="auth-btn-submit" id="cust-login-btn">
            <span>Sign In to Account</span>
            <i data-lucide="arrow-right" style="width: 16px;"></i>
          </button>
        </form>

        <!-- Form 2: Customer Register -->
        <form id="customer-register-form" style="display: none;" onsubmit="handleCustomerRegisterSubmit(event)">
          <div class="auth-form-group">
            <label>Full Name</label>
            <div class="auth-input-wrap">
              <i data-lucide="user"></i>
              <input type="text" id="cust-reg-name" placeholder="John Doe" required autocomplete="name">
            </div>
          </div>

          <div class="auth-form-group">
            <label>Email Address</label>
            <div class="auth-input-wrap">
              <i data-lucide="mail"></i>
              <input type="email" id="cust-reg-email" placeholder="john.doe@example.com" required autocomplete="email">
            </div>
          </div>

          <div class="auth-form-group">
            <label>Phone Number</label>
            <div class="auth-input-wrap">
              <i data-lucide="phone"></i>
              <input type="tel" id="cust-reg-phone" placeholder="+91 9876543210" required autocomplete="tel">
            </div>
          </div>

          <div class="auth-form-group">
            <label>Password</label>
            <div class="auth-input-wrap">
              <i data-lucide="lock"></i>
              <input type="password" id="cust-reg-password" placeholder="Create a strong password" required autocomplete="new-password">
            </div>
          </div>

          <button type="submit" class="auth-btn-submit" id="cust-reg-btn">
            <span>Register & Start Ordering</span>
            <i data-lucide="check" style="width: 16px;"></i>
          </button>
        </form>

        <!-- 1-Click Demo Login -->
        <div class="demo-login-box">
          <p style="font-size: 0.78rem; color: var(--text-muted); margin-bottom: 8px;">Testing as customer?</p>
          <button type="button" class="demo-btn-pill" onclick="quickFillDemoCustomer()">
            <i data-lucide="zap" style="width: 14px; color: #f59e0b;"></i>
            <span>1-Click Demo Customer Login</span>
          </button>
        </div>

      </div>
    </div>

  </div>

  <script>
    const API_BASE = '../api/index.php';

    document.addEventListener('DOMContentLoaded', () => {
      initTheme();
      if (window.lucide) lucide.createIcons();

      // Check URL tab parameter or pending dish
      const urlParams = new URLSearchParams(window.location.search);
      if (urlParams.get('tab') === 'register') {
        setAuthTab('register');
      }

      const pendingDish = urlParams.get('add_dish') || sessionStorage.getItem('fms_pending_dish') || localStorage.getItem('fms_pending_dish');
      if (pendingDish) {
        sessionStorage.setItem('fms_pending_dish', pendingDish);
        localStorage.setItem('fms_pending_dish', pendingDish);
        const notice = document.getElementById('auth-banner-notice');
        if (notice) {
          notice.innerHTML = `
            <i data-lucide="sparkles" style="width: 18px; height: 18px; flex-shrink: 0; color: var(--primary);"></i>
            <span><strong>Almost there!</strong> Sign in or create an account — your selected dish will be added directly to your cart automatically!</span>
          `;
          notice.style.display = 'flex';
          if (window.lucide) lucide.createIcons();
        }
      }
    });

    function setAuthTab(tab) {
      const tabLogin = document.getElementById('btn-tab-login');
      const tabReg = document.getElementById('btn-tab-register');
      const formLogin = document.getElementById('customer-login-form');
      const formReg = document.getElementById('customer-register-form');
      const title = document.getElementById('auth-main-title');
      const desc = document.getElementById('auth-main-desc');

      if (tab === 'login') {
        tabLogin.classList.add('active');
        tabReg.classList.remove('active');
        formLogin.style.display = 'block';
        formReg.style.display = 'none';
        title.innerText = 'Welcome Back to BITEBUDDY';
        desc.innerText = 'Sign in to place orders, track live deliveries & save addresses';
      } else {
        tabLogin.classList.remove('active');
        tabReg.classList.add('active');
        formLogin.style.display = 'none';
        formReg.style.display = 'block';
        title.innerText = 'Create Your BITEBUDDY Account';
        desc.innerText = 'Join thousands of food lovers enjoying instant hot deliveries';
      }
      if (window.lucide) lucide.createIcons();
    }

    async function handleCustomerLoginSubmit(e) {
      e.preventDefault();
      const email = document.getElementById('cust-login-email').value.trim();
      const password = document.getElementById('cust-login-password').value.trim();
      const btn = document.getElementById('cust-login-btn');

      btn.disabled = true;
      btn.innerHTML = 'Signing in...';

      try {
        const res = await fetch(`${API_BASE}?action=customer_login`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ email: email, password: password })
        });
        const json = await res.json();

        if (json.success && json.data) {
          const user = {
            user_id: json.data.user_id || 1,
            name: json.data.name || email.split('@')[0],
            email: json.data.email || email,
            phone: json.data.phone || '+91 9876543210'
          };
          localStorage.setItem('fms_user', JSON.stringify(user));
          handleAuthSuccessRedirect();
        } else {
          alert(json.message || 'Invalid credentials. Please try again.');
          btn.disabled = false;
          btn.innerHTML = '<span>Sign In to Account</span><i data-lucide="arrow-right" style="width: 16px;"></i>';
          if (window.lucide) lucide.createIcons();
        }
      } catch (err) {
        // Fallback for offline mode
        const user = {
          user_id: 1,
          name: email.split('@')[0].toUpperCase(),
          email: email,
          phone: '+91 9876543210'
        };
        localStorage.setItem('fms_user', JSON.stringify(user));
        handleAuthSuccessRedirect();
      }
    }

    async function handleCustomerRegisterSubmit(e) {
      e.preventDefault();
      const name = document.getElementById('cust-reg-name').value.trim();
      const email = document.getElementById('cust-reg-email').value.trim();
      const phone = document.getElementById('cust-reg-phone').value.trim();
      const password = document.getElementById('cust-reg-password').value.trim();
      const btn = document.getElementById('cust-reg-btn');

      btn.disabled = true;
      btn.innerHTML = 'Creating account...';

      try {
        const res = await fetch(`${API_BASE}?action=customer_register`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ name: name, email: email, phone: phone, password: password })
        });
        const json = await res.json();

        if (json.success && json.data) {
          const user = {
            user_id: json.data.user_id || 1,
            name: json.data.name || name,
            email: json.data.email || email,
            phone: json.data.phone || phone
          };
          localStorage.setItem('fms_user', JSON.stringify(user));
          handleAuthSuccessRedirect();
        } else {
          alert(json.message || 'Registration failed. Please try a different email.');
          btn.disabled = false;
          btn.innerHTML = '<span>Register & Start Ordering</span><i data-lucide="check" style="width: 16px;"></i>';
          if (window.lucide) lucide.createIcons();
        }
      } catch (err) {
        const user = { user_id: 1, name: name, email: email, phone: phone };
        localStorage.setItem('fms_user', JSON.stringify(user));
        handleAuthSuccessRedirect();
      }
    }

    function handleAuthSuccessRedirect() {
      const urlParams = new URLSearchParams(window.location.search);
      const pendingDish = urlParams.get('add_dish') || sessionStorage.getItem('fms_pending_dish') || localStorage.getItem('fms_pending_dish');
      const redirectUrl = urlParams.get('redirect') || sessionStorage.getItem('fms_redirect_url');

      if (pendingDish) {
        // Clear pending items from storage
        sessionStorage.removeItem('fms_pending_dish');
        localStorage.removeItem('fms_pending_dish');
        // Redirect back to storefront with auto_add parameter
        window.location.href = `index.php?auto_add=${encodeURIComponent(pendingDish)}`;
      } else if (redirectUrl && !redirectUrl.includes('login.php')) {
        window.location.href = redirectUrl;
      } else {
        window.location.href = 'index.php';
      }
    }

    function quickFillDemoCustomer() {
      document.getElementById('cust-login-email').value = 'customer@bitebuddy.com';
      document.getElementById('cust-login-password').value = '12345678';
      const form = document.getElementById('customer-login-form');
      if (form) {
        form.dispatchEvent(new Event('submit'));
      }
    }

    function initTheme() {
      const saved = localStorage.getItem('fms_theme') || 'light';
      document.body.setAttribute('data-theme', saved);
      updateThemeIcon(saved);
    }

    function toggleTheme() {
      const current = document.body.getAttribute('data-theme');
      const next = current === 'dark' ? 'light' : 'dark';
      document.body.setAttribute('data-theme', next);
      localStorage.setItem('fms_theme', next);
      updateThemeIcon(next);
    }

    function updateThemeIcon(theme) {
      const icon = document.getElementById('theme-icon');
      if (icon) {
        icon.setAttribute('data-lucide', theme === 'dark' ? 'sun' : 'moon');
        if (window.lucide) lucide.createIcons();
      }
    }
  </script>
</body>
</html>

