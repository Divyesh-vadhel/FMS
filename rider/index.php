<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delivery Partner Dispatch - BITEBUDDY</title>
  
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
        <div class="logo-badge" style="background: linear-gradient(135deg, #10B981, #059669);">rider</div>
        <div class="logo-text">
          <h1>BITEBUDDY</h1>
          <span>Rider Dispatch</span>
        </div>
      </a>

      <div class="nav-actions">
        <div class="nav-link-item" style="cursor: default; background: var(--bg-card); border-color: var(--border);">
          <i data-lucide="bike" style="color: var(--veg-green);"></i>
          <span>Rider Amit Verma</span>
        </div>
        <a href="../admin/index.php" class="nav-link-item" title="Admin Portal">
          <i data-lucide="shield"></i>
          <span>Admin</span>
        </a>
        <a href="../user/index.php" class="nav-link-item nav-btn-outline" title="Live Store">
          <i data-lucide="external-link"></i>
          <span>Live Store</span>
        </a>
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
          <i data-lucide="bike" style="color: var(--primary);"></i> Delivery Partner Dispatch
        </h2>
        <p style="color: var(--text-muted); font-size: 0.9rem;">
          Rider: <strong>Amit Verma</strong> • Vehicle: <strong>MH-02-BZ-4321 (Bike)</strong> • Status: <span style="color: var(--veg-green); font-weight: 700;">Online</span>
        </p>
      </div>
      <div style="background: var(--bg-card); border: 1px solid var(--border); padding: 8px 16px; border-radius: 10px; font-weight: 700;">
        Today's Earnings: <span style="color: var(--veg-green);">₹940.00 (8 Trips Completed)</span>
      </div>
    </div>

    <!-- Rider Active Deliveries Grid -->
    <div class="orders-ticket-grid" id="rider-orders-grid">
      <!-- Populated via JS -->
    </div>
  </main>

  <script src="../assets/js/admin.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      loadRiderDeliveries();
    });

    async function loadRiderDeliveries() {
      const container = document.getElementById('rider-orders-grid');
      if (!container) return;

      try {
        const res = await fetch('../api/index.php?action=get_orders&role=rider&boy_id=1');
        const json = await res.json();
        renderRiderDeliveries(json.data || []);
      } catch (e) {
        renderRiderDeliveries([
          { order_id: 1, restaurant_name: 'Tandoor & Curry Express', street_address: 'Flat 402, Royal Palms, Link Road', tot_amount: '470.00', status: 'out_for_delivery' }
        ]);
      }
    }

    function renderRiderDeliveries(orders) {
      const container = document.getElementById('rider-orders-grid');
      if (!container) return;

      if (orders.length === 0) {
        container.innerHTML = `<p style="grid-column: 1/-1; text-align: center; color: var(--text-muted); padding: 3rem;">No pending delivery pickups assigned.</p>`;
        return;
      }

      container.innerHTML = orders.map(o => `
        <div class="ticket-card">
          <div class="ticket-header">
            <div>
              <strong>Trip #FMS-${o.order_id}</strong>
              <div style="font-size: 0.8rem; color: var(--primary); font-weight: 700;">₹${parseFloat(o.tot_amount).toFixed(2)} (Cash / UPI)</div>
            </div>
            <span class="status-badge status-${o.status}">${o.status}</span>
          </div>
          <div style="font-size: 0.82rem; color: var(--text-muted); line-height: 1.6; margin: 0.5rem 0;">
            <div><strong><i data-lucide="store" style="width: 12px;"></i> Pickup:</strong> ${o.restaurant_name || 'Restaurant'}</div>
            <div><strong><i data-lucide="map-pin" style="width: 12px;"></i> Drop:</strong> ${o.street_address || 'Delivery Address'}</div>
          </div>
          <div style="display: flex; gap: 8px; margin-top: auto; padding-top: 10px; border-top: 1px solid var(--border);">
            <button class="filter-chip active" onclick="markDelivered(${o.order_id})" style="width: 100%; justify-content: center;">
              <i data-lucide="check-circle-2" style="width: 16px;"></i> Mark as Delivered
            </button>
          </div>
        </div>
      `).join('');
      initLucide();
    }

    async function markDelivered(orderId) {
      try {
        const fd = new FormData();
        fd.append('order_id', orderId);
        fd.append('status', 'delivered');
        await fetch('../api/index.php?action=update_order_status', { method: 'POST', body: fd });
        alert(`Order #FMS-${orderId} marked as DELIVERED!`);
        loadRiderDeliveries();
      } catch (e) {
        alert('Marked as Delivered!');
      }
    }
  </script>
</body>
</html>

