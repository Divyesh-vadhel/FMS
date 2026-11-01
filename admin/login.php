<?php
/**
 * Food Management System (FMS) - BITEBUDDY Admin Portal Login
 * Credentials: Username: admin | Password: 12345678
 */
session_start();

// If already authenticated, redirect to admin dashboard
if (!empty($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    require_once __DIR__ . '/../config/db.php';

    if ($db_connected) {
        $stmt = $conn->prepare("SELECT a.auth_id, a.username, a.password, a.role, ad.name, ad.email 
                                FROM authentication a 
                                LEFT JOIN admins ad ON a.auth_id = ad.auth_id 
                                WHERE (a.username = ? OR a.email = ?) AND a.role = 'admin' LIMIT 1");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $user = $res->fetch_assoc()) {
            $is_valid = password_verify($password, $user['password']) || ($password === '12345678') || ($password === $user['password']);
            if ($is_valid) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_auth_id'] = $user['auth_id'];
                $_SESSION['admin_name'] = $user['name'] ?: $user['username'];
                $_SESSION['admin_email'] = $user['email'];

                header("Location: index.php");
                exit();
            }
        }
    }

    // Fallback if DB is offline or user enters credentials
    if ($username === 'admin' && $password === '12345678') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_name'] = 'admin';
        $_SESSION['admin_email'] = 'admin@bitebuddy.com';
        header("Location: index.php");
        exit();
    }

    $error = 'Invalid admin credentials. Please enter username: <strong>admin</strong> and password: <strong>12345678</strong>.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Sign In - BITEBUDDY Platform Operations</title>
  
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
  
  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>
  
  <!-- Shared CSS -->
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="../assets/css/admin.css">

  <style>
    body {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      background: var(--bg);
    }

    .admin-login-wrapper {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
    }

    .admin-login-card {
      width: 100%;
      max-width: 440px;
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      box-shadow: var(--shadow-lg);
      padding: 2.5rem;
      position: relative;
      overflow: hidden;
    }

    .admin-login-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--primary), #FF6B6B);
    }

    .login-brand-header {
      text-align: center;
      margin-bottom: 2rem;
    }

    .login-brand-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: linear-gradient(135deg, #1E293B, #0F172A);
      color: #fff;
      font-weight: 800;
      font-size: 0.85rem;
      padding: 6px 14px;
      border-radius: var(--radius-full);
      margin-bottom: 1rem;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .login-brand-header h2 {
      font-size: 1.6rem;
      font-weight: 800;
      letter-spacing: -0.02em;
      margin-bottom: 0.4rem;
    }

    .login-brand-header p {
      font-size: 0.88rem;
      color: var(--text-muted);
    }

    .login-alert-box {
      background: rgba(226, 55, 68, 0.1);
      border: 1px solid rgba(226, 55, 68, 0.3);
      color: var(--primary);
      padding: 10px 14px;
      border-radius: var(--radius-sm);
      font-size: 0.85rem;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .input-field-group {
      margin-bottom: 1.25rem;
    }

    .input-field-group label {
      display: block;
      font-size: 0.85rem;
      font-weight: 700;
      margin-bottom: 6px;
      color: var(--text);
    }

    .input-with-icon {
      position: relative;
      display: flex;
      align-items: center;
    }

    .input-with-icon i {
      position: absolute;
      left: 14px;
      width: 18px;
      height: 18px;
      color: var(--text-muted);
    }

    .input-with-icon input {
      width: 100%;
      padding: 11px 14px 11px 42px;
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      background: var(--bg);
      color: var(--text);
      font-size: 0.95rem;
      font-family: inherit;
      outline: none;
      transition: var(--transition);
    }

    .input-with-icon input:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(226, 55, 68, 0.15);
    }

    .login-submit-btn {
      width: 100%;
      padding: 12px;
      background: var(--primary);
      color: #fff;
      border: none;
      border-radius: var(--radius-sm);
      font-size: 0.95rem;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: var(--transition);
      box-shadow: 0 4px 14px rgba(226, 55, 68, 0.3);
      margin-top: 1.5rem;
    }

    .login-submit-btn:hover {
      background: var(--primary-hover);
      transform: translateY(-1px);
    }

    .credentials-hint-box {
      margin-top: 1.75rem;
      padding: 12px 14px;
      background: var(--bg);
      border: 1px dashed var(--border);
      border-radius: var(--radius-sm);
      font-size: 0.82rem;
      color: var(--text-muted);
      text-align: center;
    }

    .credentials-hint-box strong {
      color: var(--primary);
      font-family: var(--font-mono);
    }
  </style>
</head>
<body data-theme="light">

  <!-- Minimal Nav -->
  <nav class="navbar" style="border-bottom: 1px solid var(--border);">
    <div class="navbar-container" style="justify-content: space-between;">
      <a href="../user/index.php" class="nav-brand">
        <div class="logo-badge">bitebuddy</div>
        <div class="logo-text">
          <h1>BITEBUDDY</h1>
          <span>Operations Admin Portal</span>
        </div>
      </a>

      <div style="display: flex; align-items: center; gap: 10px;">
        <a href="../user/index.php" class="nav-link-item" title="Go to Customer Storefront">
          <i data-lucide="arrow-left"></i>
          <span>Customer Site</span>
        </a>
        <button class="btn-icon" onclick="toggleTheme()" title="Toggle Dark/Light Mode">
          <i data-lucide="moon" id="theme-icon"></i>
        </button>
      </div>
    </div>
  </nav>

  <!-- Login Form Box -->
  <main class="admin-login-wrapper">
    <div class="admin-login-card">
      
      <div class="login-brand-header">
        <div class="login-brand-badge">
          <i data-lucide="shield-check" style="width: 14px; height: 14px;"></i>
          <span>Secure Admin Access</span>
        </div>
        <h2>Sign In to Console</h2>
        <p>Enter your administrator credentials to access platform controls</p>
      </div>

      <?php if (!empty($error)): ?>
        <div class="login-alert-box">
          <i data-lucide="alert-circle" style="width: 18px; height: 18px; flex-shrink: 0;"></i>
          <div><?= $error ?></div>
        </div>
      <?php endif; ?>

      <form method="POST" action="login.php">
        
        <div class="input-field-group">
          <label for="username">Username / Admin Name</label>
          <div class="input-with-icon">
            <i data-lucide="user"></i>
            <input type="text" id="username" name="username" value="admin" required autofocus autocomplete="username" placeholder="admin">
          </div>
        </div>

        <div class="input-field-group">
          <label for="password">Password</label>
          <div class="input-with-icon">
            <i data-lucide="lock"></i>
            <input type="password" id="password" name="password" value="12345678" required autocomplete="current-password" placeholder="12345678">
          </div>
        </div>

        <button type="submit" class="login-submit-btn">
          <i data-lucide="log-in" style="width: 18px; height: 18px;"></i>
          <span>Sign In to Admin Dashboard</span>
        </button>

      </form>

      <div class="credentials-hint-box">
        <div>Default Admin Login:</div>
        <div>Username: <strong>admin</strong> • Password: <strong>12345678</strong></div>
      </div>

    </div>
  </main>

  <script src="../assets/js/admin.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      initTheme();
      initLucide();
    });
  </script>
</body>
</html>

