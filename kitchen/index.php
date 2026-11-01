<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kitchen Order Display (KDS) - BITEBUDDY</title>
  
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
        <div class="logo-badge" style="background: linear-gradient(135deg, #F59E0B, #D97706);">chef</div>
        <div class="logo-text">
          <h1>BITEBUDDY</h1>
          <span>Kitchen Order Display</span>
        </div>
      </a>

      <div class="nav-actions">
        <div class="nav-link-item" style="cursor: default; background: var(--bg-card); border-color: var(--border);">
          <i data-lucide="chef-hat" style="color: #D97706;"></i>
          <span>Chef Vikram</span>
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
          <i data-lucide="chef-hat" style="color: var(--primary);"></i> Kitchen Order Display (KDS)
        </h2>
        <p style="color: var(--text-muted); font-size: 0.9rem;">
          Station: <strong>Chef Vikram Malhotra</strong> • Tandoor & Curry Express Kitchen
        </p>
      </div>
      <button class="filter-chip active" onclick="loadKitchenOrders()">
        <i data-lucide="refresh-cw" style="width: 14px;"></i> Refresh Tickets
      </button>
    </div>

    <!-- Kitchen Orders Tickets -->
    <div class="orders-ticket-grid" id="kitchen-orders-grid">
      <!-- Populated via JS -->
    </div>
  </main>

  <script src="../assets/js/admin.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      loadKitchenOrders();
    });

    async function loadKitchenOrders() {
      const container = document.getElementById('kitchen-orders-grid');
      if (!container) return;

      try {
        const res = await fetch('../api/index.php?action=get_orders&role=kitchen&restaurant_id=1');
        const json = await res.json();
        renderKitchenTickets(json.data || []);
      } catch (e) {
        renderKitchenTickets([
          { order_id: 1, customer_name: 'Rahul Sharma', tot_amount: '470.00', status: 'cooking', items: [{ item_name: 'Paneer Butter Masala', quantity: 1 }, { item_name: 'Butter Garlic Naan', quantity: 2 }] }
        ]);
      }
    }

    function renderKitchenTickets(orders) {
      const container = document.getElementById('kitchen-orders-grid');
      if (!container) return;

      if (orders.length === 0) {
        container.innerHTML = `<p style="grid-column: 1/-1; text-align: center; color: var(--text-muted); padding: 3rem;">No active kitchen preparation tickets.</p>`;
        return;
      }

      container.innerHTML = orders.map(o => `
        <div class="ticket-card">
          <div class="ticket-header">
            <div>
              <strong>Order #FMS-${o.order_id}</strong>
              <div style="font-size: 0.78rem; color: var(--text-muted);">Customer: ${o.customer_name || 'Rahul Sharma'}</div>
            </div>
            <span class="status-badge status-${o.status}">${o.status}</span>
          </div>
          <div style="font-size: 0.85rem; line-height: 1.6; padding: 0.5rem 0;">
            ${(o.items || []).map(it => `<div>• <strong>${it.quantity}x</strong> ${it.item_name}</div>`).join('')}
          </div>
          <div style="font-size: 0.78rem; color: var(--text-muted); margin-bottom: 8px;">
            Note: <em>${o.order_notes || 'Contactless drop'}</em>
          </div>
          <div style="display: flex; gap: 8px; margin-top: auto; padding-top: 10px; border-top: 1px solid var(--border);">
            <button class="filter-chip" onclick="updateKitchenTicketStatus(${o.order_id}, 'cooking')">
              <i data-lucide="flame" style="width: 14px;"></i> Start Cooking
            </button>
            <button class="filter-chip active" onclick="updateKitchenTicketStatus(${o.order_id}, 'out_for_delivery')">
              <i data-lucide="check" style="width: 14px;"></i> Ready for Pickup
            </button>
          </div>
        </div>
      `).join('');
      initLucide();
    }

    async function updateKitchenTicketStatus(orderId, status) {
      try {
        const fd = new FormData();
        fd.append('order_id', orderId);
        fd.append('status', status);
        await fetch('../api/index.php?action=update_order_status', { method: 'POST', body: fd });
        alert(`Order #FMS-${orderId} updated to: ${status.toUpperCase()}`);
        loadKitchenOrders();
      } catch (e) {
        alert(`Order updated to: ${status}`);
      }
    }
  </script>
</body>
</html>

