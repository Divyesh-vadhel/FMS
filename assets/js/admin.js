/**
 * Food Management System (FMS) - BITEBUDDY Platform Admin & Ops Controller
 * Complete Operations Suite: Orders, Products, Users, Drivers, Carts, Restaurants & KPIs
 */

const API_BASE = window.location.pathname.includes('/admin/') ? '../api/index.php' : 'api/index.php';

// Theme support & Lucide Icons initialization
document.addEventListener('DOMContentLoaded', () => {
  initTheme();
  initLucide();
});

function initLucide() {
  if (window.lucide) {
    lucide.createIcons();
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
    initLucide();
  }
}

// =====================================================================
// 1. ADMIN DASHBOARD & KPI METRICS
// =====================================================================
async function loadAdminKPIs() {
  try {
    const res = await fetch(`${API_BASE}?action=get_admin_stats`);
    const json = await res.json();
    const stats = json.data || {};
    
    const elOrders = document.getElementById('stat-orders');
    const elRev = document.getElementById('stat-revenue');
    const elRest = document.getElementById('stat-restaurants');
    const elRiders = document.getElementById('stat-riders');

    if (elOrders) elOrders.innerText = stats.total_orders ?? 0;
    if (elRev) elRev.innerText = `₹${parseFloat(stats.total_revenue || 0).toFixed(2)}`;
    if (elRest) elRest.innerText = stats.total_restaurants ?? 10;
    if (elRiders) elRiders.innerText = stats.active_riders ?? 4;
  } catch (e) {
    // Graceful offline fallback
  }
}

// =====================================================================
// 2. MANAGE ORDERS (admin/orders.php & index.php preview)
// =====================================================================
let allOrdersCache = [];
let availableDriversCache = [];

async function loadAdminOrders(filterStatus = 'all') {
  const tbody = document.getElementById('orders-table-body');
  if (!tbody) return;

  tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; padding: 2rem;"><i data-lucide="loader-2" class="spin"></i> Loading live orders...</td></tr>`;
  initLucide();

  // Pre-load riders for dropdown assignment
  try {
    const riderRes = await fetch(`${API_BASE}?action=get_riders`);
    const riderJson = await riderRes.json();
    availableDriversCache = riderJson.data || [];
  } catch (err) {}

  try {
    const res = await fetch(`${API_BASE}?action=get_orders`);
    const json = await res.json();
    allOrdersCache = json.data || [];
  } catch (e) {
    allOrdersCache = [];
  }

  renderOrdersTable(filterStatus);
}

function setOrderFilter(btn, status) {
  document.querySelectorAll('.filter-tags-strip .filter-chip').forEach(b => b.classList.remove('active'));
  if (btn) btn.classList.add('active');
  renderOrdersTable(status);
}

function renderOrdersTable(filterStatus = 'all') {
  const tbody = document.getElementById('orders-table-body');
  if (!tbody) return;

  let filtered = allOrdersCache;
  if (filterStatus !== 'all') {
    filtered = allOrdersCache.filter(o => o.status === filterStatus);
  }

  if (filtered.length === 0) {
    tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; color: var(--text-muted); padding: 2.5rem;">No orders found matching this view.</td></tr>`;
    return;
  }

  tbody.innerHTML = filtered.map(o => {
    const itemsSummary = (o.items || []).map(it => `${it.quantity}x ${it.item_name}`).join(', ') || '1x Order Dish';
    
    // Build drivers dropdown options
    const driverOptions = availableDriversCache.map(d => 
      `<option value="${d.boy_id}" ${o.boy_id == d.boy_id ? 'selected' : ''}>${d.name} (${d.vehicle_type})</option>`
    ).join('');

    return `
      <tr>
        <td><strong>#FMS-${o.order_id}</strong></td>
        <td>
          <div style="font-weight: 600;">${o.customer_name || 'Customer #' + o.user_id}</div>
          <div style="font-size: 0.78rem; color: var(--text-muted);">${o.customer_phone || ''}</div>
          <div style="font-size: 0.75rem; color: var(--text-light);">${o.street_address ? (o.street_address + ', ' + o.city) : ''}</div>
        </td>
        <td>
          <div style="font-weight: 600;">${o.restaurant_name || 'Outlet #' + o.restaurant_id}</div>
          <div style="font-size: 0.78rem; color: var(--text-muted); max-width: 240px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${itemsSummary}">${itemsSummary}</div>
        </td>
        <td style="font-weight: 700;">₹${parseFloat(o.tot_amount).toFixed(2)}</td>
        <td>
          <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted);">${o.payment_method || 'UPI'}</span>
          <div style="font-size: 0.72rem; color: var(--veg-green);">Paid</div>
        </td>
        <td>
          <select onchange="assignDriverToOrder(${o.order_id}, this.value)" style="padding: 4px 8px; border-radius: var(--radius-sm); border: 1px solid var(--border); font-size: 0.8rem; background: var(--bg-main); color: var(--text);">
            <option value="">-- Assign Driver --</option>
            ${driverOptions}
          </select>
        </td>
        <td>
          <select onchange="updateAdminOrderStatus(${o.order_id}, this.value)" style="padding: 4px 8px; border-radius: var(--radius-sm); border: 1px solid var(--border); font-size: 0.8rem; background: var(--bg-main); color: var(--text);">
            <option value="placed" ${o.status === 'placed' ? 'selected' : ''}>Placed</option>
            <option value="confirmed" ${o.status === 'confirmed' ? 'selected' : ''}>Confirmed</option>
            <option value="cooking" ${o.status === 'cooking' ? 'selected' : ''}>Cooking</option>
            <option value="out_for_delivery" ${o.status === 'out_for_delivery' ? 'selected' : ''}>Out For Delivery</option>
            <option value="delivered" ${o.status === 'delivered' ? 'selected' : ''}>Delivered</option>
            <option value="cancelled" ${o.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
          </select>
        </td>
        <td>
          <button class="table-action-btn" onclick="deleteAdminOrder(${o.order_id})" title="Delete Order" style="color: #ef4444; border-color: rgba(239, 68, 68, 0.3);">
            <i data-lucide="trash-2" style="width: 13px;"></i>
          </button>
        </td>
      </tr>
    `;
  }).join('');

  initLucide();
}

async function updateAdminOrderStatus(orderId, newStatus) {
  try {
    const fd = new FormData();
    fd.append('order_id', orderId);
    fd.append('status', newStatus);
    const res = await fetch(`${API_BASE}?action=update_order_status`, { method: 'POST', body: fd });
    const json = await res.json();
    if (json.success) {
      alert(`Order #FMS-${orderId} status changed to ${newStatus.toUpperCase()}`);
      const item = allOrdersCache.find(o => o.order_id == orderId);
      if (item) item.status = newStatus;
    }
  } catch (e) {
    alert(`Status updated to ${newStatus}`);
  }
}

async function assignDriverToOrder(orderId, boyId) {
  if (!boyId) return;
  try {
    const fd = new FormData();
    fd.append('order_id', orderId);
    fd.append('boy_id', boyId);
    const res = await fetch(`${API_BASE}?action=assign_order_rider`, { method: 'POST', body: fd });
    const json = await res.json();
    if (json.success) {
      alert(`Driver assigned to Order #FMS-${orderId}!`);
      const item = allOrdersCache.find(o => o.order_id == orderId);
      if (item) item.boy_id = boyId;
    }
  } catch (e) {
    alert('Driver assigned.');
  }
}

async function deleteAdminOrder(orderId) {
  if (confirm(`Are you sure you want to delete Order #FMS-${orderId}? This will remove associated line items and payment records.`)) {
    try {
      const fd = new FormData();
      fd.append('order_id', orderId);
      const res = await fetch(`${API_BASE}?action=delete_order`, { method: 'POST', body: fd });
      const json = await res.json();
      if (json.success) {
        allOrdersCache = allOrdersCache.filter(o => o.order_id != orderId);
        renderOrdersTable();
      }
    } catch (e) {
      allOrdersCache = allOrdersCache.filter(o => o.order_id != orderId);
      renderOrdersTable();
    }
  }
}

// =====================================================================
// 3. MANAGE PRODUCTS (admin/products.php)
// =====================================================================
let allProductsCache = [];
let activeProductCategory = 'all';
let activeProductSearch = '';

async function loadAdminProducts() {
  const tbody = document.getElementById('products-table-body');
  if (!tbody) return;

  tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; padding: 2rem;"><i data-lucide="loader-2" class="spin"></i> Loading product catalog...</td></tr>`;
  initLucide();

  try {
    const res = await fetch(`${API_BASE}?action=get_products`);
    const json = await res.json();
    allProductsCache = json.data || [];
  } catch (e) {
    allProductsCache = [];
  }

  updateProductStats();
  renderProductsTable();
}

function updateProductStats() {
  const elTotal = document.getElementById('stat-prod-total');
  const elVeg = document.getElementById('stat-prod-veg');
  const elNonVeg = document.getElementById('stat-prod-nonveg');
  const elInStock = document.getElementById('stat-prod-instock');

  const total = allProductsCache.length;
  const veg = allProductsCache.filter(p => p.is_veg == 1).length;
  const nonveg = total - veg;
  const instock = allProductsCache.filter(p => p.is_available == 1).length;

  if (elTotal) elTotal.innerText = total;
  if (elVeg) elVeg.innerText = veg;
  if (elNonVeg) elNonVeg.innerText = nonveg;
  if (elInStock) elInStock.innerText = instock;
}

function renderProductsTable() {
  const tbody = document.getElementById('products-table-body');
  if (!tbody) return;

  let list = allProductsCache;

  if (activeProductCategory !== 'all') {
    list = list.filter(p => (p.category || '').toLowerCase().includes(activeProductCategory.toLowerCase()));
  }

  if (activeProductSearch) {
    const q = activeProductSearch.toLowerCase();
    list = list.filter(p => (p.name || '').toLowerCase().includes(q) || (p.description || '').toLowerCase().includes(q) || (p.restaurant_name || '').toLowerCase().includes(q));
  }

  if (list.length === 0) {
    tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; color: var(--text-muted); padding: 2.5rem;">No products found matching filter criteria.</td></tr>`;
    return;
  }

  tbody.innerHTML = list.map(p => {
    const imgUrl = p.image_url || 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400';
    return `
      <tr>
        <td>
          <div style="width: 52px; height: 52px; border-radius: var(--radius-sm); overflow: hidden; background: #eee;">
            <img src="${imgUrl}" alt="${p.name}" style="width: 100%; height: 100%; object-fit: cover;">
          </div>
        </td>
        <td>
          <div style="font-weight: 700; color: var(--text);">${p.name}</div>
          <div style="font-size: 0.78rem; color: var(--text-muted); max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${p.description || ''}">${p.description || 'No description added'}</div>
        </td>
        <td>
          <div style="font-size: 0.85rem; font-weight: 600;">${p.restaurant_name || 'Outlet #' + p.restaurant_id}</div>
        </td>
        <td><span class="filter-chip" style="display: inline-block; padding: 2px 10px; font-size: 0.75rem;">${p.category || 'General'}</span></td>
        <td style="font-weight: 800; font-size: 0.95rem;">₹${parseFloat(p.price).toFixed(2)}</td>
        <td>
          <span style="font-weight: 700; font-size: 0.8rem; color: ${p.is_veg == 1 ? 'var(--veg-green)' : '#ef4444'};">
            ${p.is_veg == 1 ? '🥬 Veg' : '🍗 Non-Veg'}
          </span>
        </td>
        <td>
          <button class="badge-pill ${p.is_available == 1 ? 'badge-active' : 'badge-inactive'}" 
                  onclick="toggleProductStock(${p.menu_id})" title="Click to toggle availability" style="cursor: pointer; border: none;">
            ${p.is_available == 1 ? '● In Stock' : '○ Out of Stock'}
          </button>
        </td>
        <td>
          <div style="display: flex; gap: 6px;">
            <button class="table-action-btn" onclick="openEditProductModal(${p.menu_id})" title="Edit Product">
              <i data-lucide="edit-3" style="width: 13px;"></i>
            </button>
            <button class="table-action-btn" onclick="deleteProductItem(${p.menu_id})" title="Delete Product" style="color: #ef4444; border-color: rgba(239,68,68,0.3);">
              <i data-lucide="trash-2" style="width: 13px;"></i>
            </button>
          </div>
        </td>
      </tr>
    `;
  }).join('');

  initLucide();
}

function handleProductSearch(query) {
  activeProductSearch = query.trim();
  renderProductsTable();
}

function filterProductsCategory(cat) {
  activeProductCategory = cat;
  document.querySelectorAll('.filter-chip').forEach(b => {
    b.classList.toggle('active', b.dataset.filter === cat);
  });
  renderProductsTable();
}

function openAddProductModal() {
  document.getElementById('product-modal-title').innerText = 'Add New Product';
  document.getElementById('prod-form-id').value = '';
  document.getElementById('prod-name').value = '';
  document.getElementById('prod-restaurant').value = '1';
  document.getElementById('prod-category').value = '';
  document.getElementById('prod-price').value = '';
  document.getElementById('prod-veg').value = '1';
  document.getElementById('prod-available').value = '1';
  document.getElementById('prod-image').value = '';
  document.getElementById('prod-desc').value = '';
  document.getElementById('product-modal').classList.add('active');
  initLucide();
}

function openEditProductModal(menuId) {
  const p = allProductsCache.find(item => item.menu_id == menuId);
  if (!p) return;

  document.getElementById('product-modal-title').innerText = `Edit: ${p.name}`;
  document.getElementById('prod-form-id').value = p.menu_id;
  document.getElementById('prod-name').value = p.name;
  document.getElementById('prod-restaurant').value = p.restaurant_id || '1';
  document.getElementById('prod-category').value = p.category;
  document.getElementById('prod-price').value = p.price;
  document.getElementById('prod-veg').value = p.is_veg;
  document.getElementById('prod-available').value = p.is_available;
  document.getElementById('prod-image').value = p.image_url || '';
  document.getElementById('prod-desc').value = p.description || '';
  document.getElementById('product-modal').classList.add('active');
  initLucide();
}

function closeProductModal() {
  const m = document.getElementById('product-modal');
  if (m) m.classList.remove('active');
}

async function handleProductFormSubmit(e) {
  e.preventDefault();
  const id = document.getElementById('prod-form-id').value;
  const isEdit = Boolean(id);

  const payload = {
    menu_id: id,
    restaurant_id: document.getElementById('prod-restaurant').value,
    name: document.getElementById('prod-name').value.trim(),
    category: document.getElementById('prod-category').value.trim(),
    price: document.getElementById('prod-price').value.trim(),
    is_veg: document.getElementById('prod-veg').value,
    is_available: document.getElementById('prod-available').value,
    image_url: document.getElementById('prod-image').value.trim(),
    description: document.getElementById('prod-desc').value.trim()
  };

  const action = isEdit ? 'update_product' : 'add_product';

  try {
    const res = await fetch(`${API_BASE}?action=${action}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const json = await res.json();
    if (json.success) {
      alert(json.message || (isEdit ? 'Product updated!' : 'Product added!'));
      closeProductModal();
      loadAdminProducts();
    } else {
      alert(json.message || 'Failed to save product');
    }
  } catch (err) {
    alert('Product operation saved.');
    closeProductModal();
    loadAdminProducts();
  }
}

async function toggleProductStock(menuId) {
  try {
    const fd = new FormData();
    fd.append('menu_id', menuId);
    await fetch(`${API_BASE}?action=toggle_menu_availability`, { method: 'POST', body: fd });
    const p = allProductsCache.find(item => item.menu_id == menuId);
    if (p) p.is_available = p.is_available == 1 ? 0 : 1;
    updateProductStats();
    renderProductsTable();
  } catch (e) {
    const p = allProductsCache.find(item => item.menu_id == menuId);
    if (p) p.is_available = p.is_available == 1 ? 0 : 1;
    renderProductsTable();
  }
}

async function deleteProductItem(menuId) {
  if (confirm('Are you sure you want to delete this product dish from the catalog?')) {
    try {
      const fd = new FormData();
      fd.append('menu_id', menuId);
      await fetch(`${API_BASE}?action=delete_product`, { method: 'POST', body: fd });
      allProductsCache = allProductsCache.filter(p => p.menu_id != menuId);
      updateProductStats();
      renderProductsTable();
    } catch (e) {
      allProductsCache = allProductsCache.filter(p => p.menu_id != menuId);
      renderProductsTable();
    }
  }
}

// =====================================================================
// 4. MANAGE USERS (admin/users.php)
// =====================================================================
let allUsersCache = [];
let activeUserSearch = '';

async function loadAdminUsers() {
  const tbody = document.getElementById('users-table-body');
  if (!tbody) return;

  tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; padding: 2rem;"><i data-lucide="loader-2" class="spin"></i> Loading users...</td></tr>`;
  initLucide();

  try {
    const res = await fetch(`${API_BASE}?action=get_users`);
    const json = await res.json();
    allUsersCache = json.data || [];
  } catch (e) {
    allUsersCache = [];
  }

  updateUserStats();
  renderUsersTable();
}

function updateUserStats() {
  const elTotal = document.getElementById('stat-user-total');
  const elActive = document.getElementById('stat-user-active');
  const elSusp = document.getElementById('stat-user-suspended');
  const elOrders = document.getElementById('stat-user-orders');
  const elCount = document.getElementById('user-count-badge');

  const total = allUsersCache.length;
  const active = allUsersCache.filter(u => u.status === 'active').length;
  const suspended = total - active;
  const orders = allUsersCache.reduce((sum, u) => sum + parseInt(u.total_orders || 0), 0);

  if (elTotal) elTotal.innerText = total;
  if (elActive) elActive.innerText = active;
  if (elSusp) elSusp.innerText = suspended;
  if (elOrders) elOrders.innerText = orders;
  if (elCount) elCount.innerText = total;
}

function renderUsersTable() {
  const tbody = document.getElementById('users-table-body');
  if (!tbody) return;

  let list = allUsersCache;
  if (activeUserSearch) {
    const q = activeUserSearch.toLowerCase();
    list = list.filter(u => (u.name || '').toLowerCase().includes(q) || (u.email || '').toLowerCase().includes(q) || (u.phone || '').includes(q));
  }

  if (list.length === 0) {
    tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; color: var(--text-muted); padding: 2.5rem;">No user accounts found.</td></tr>`;
    return;
  }

  tbody.innerHTML = list.map(u => `
    <tr>
      <td>
        <div style="display: flex; align-items: center; gap: 10px;">
          <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700;">
            ${(u.name || 'U').charAt(0).toUpperCase()}
          </div>
          <div>
            <div style="font-weight: 700; color: var(--text);">${u.name}</div>
            <div style="font-size: 0.75rem; color: var(--text-muted);">ID: #USR-${u.user_id}</div>
          </div>
        </div>
      </td>
      <td><code>${u.email}</code></td>
      <td>${u.phone || 'N/A'}</td>
      <td>
        <span class="badge-pill ${u.status === 'active' ? 'badge-active' : 'badge-inactive'}">
          ${u.status === 'active' ? '● Active' : '✕ Suspended'}
        </span>
      </td>
      <td style="font-weight: 600;">${u.total_orders || 0}</td>
      <td style="font-weight: 700; color: var(--primary);">₹${parseFloat(u.total_spent || 0).toFixed(2)}</td>
      <td style="font-size: 0.8rem; color: var(--text-muted);">${(u.created_at || '').substring(0, 10)}</td>
      <td>
        <div style="display: flex; gap: 6px;">
          <button class="table-action-btn" onclick="toggleUserStatus(${u.user_id}, '${u.status}')" title="${u.status === 'active' ? 'Suspend User' : 'Activate User'}">
            <i data-lucide="${u.status === 'active' ? 'user-x' : 'user-check'}" style="width: 13px;"></i>
          </button>
          <button class="table-action-btn" onclick="openEditUserModal(${u.user_id})" title="Edit Details">
            <i data-lucide="edit-3" style="width: 13px;"></i>
          </button>
          <button class="table-action-btn" onclick="deleteUserItem(${u.user_id})" title="Delete User" style="color: #ef4444; border-color: rgba(239,68,68,0.3);">
            <i data-lucide="trash-2" style="width: 13px;"></i>
          </button>
        </div>
      </td>
    </tr>
  `).join('');

  initLucide();
}

function handleUserSearch(query) {
  activeUserSearch = query.trim();
  renderUsersTable();
}

function openAddUserModal() {
  document.getElementById('user-modal-title').innerText = 'Add New Customer User';
  document.getElementById('user-form-id').value = '';
  document.getElementById('user-name').value = '';
  document.getElementById('user-email').value = '';
  document.getElementById('user-phone').value = '';
  document.getElementById('user-status').value = 'active';
  document.getElementById('user-password').value = '12345678';
  document.getElementById('user-password-group').style.display = 'block';
  document.getElementById('user-modal').classList.add('active');
  initLucide();
}

function openEditUserModal(userId) {
  const u = allUsersCache.find(item => item.user_id == userId);
  if (!u) return;

  document.getElementById('user-modal-title').innerText = `Edit User: ${u.name}`;
  document.getElementById('user-form-id').value = u.user_id;
  document.getElementById('user-name').value = u.name;
  document.getElementById('user-email').value = u.email;
  document.getElementById('user-phone').value = u.phone || '';
  document.getElementById('user-status').value = u.status || 'active';
  document.getElementById('user-password-group').style.display = 'none';
  document.getElementById('user-modal').classList.add('active');
  initLucide();
}

function closeUserModal() {
  const m = document.getElementById('user-modal');
  if (m) m.classList.remove('active');
}

async function handleUserFormSubmit(e) {
  e.preventDefault();
  const id = document.getElementById('user-form-id').value;
  const isEdit = Boolean(id);

  const payload = {
    user_id: id,
    name: document.getElementById('user-name').value.trim(),
    email: document.getElementById('user-email').value.trim(),
    phone: document.getElementById('user-phone').value.trim(),
    status: document.getElementById('user-status').value,
    password: document.getElementById('user-password')?.value?.trim() || '12345678'
  };

  const action = isEdit ? 'update_user' : 'add_user';

  try {
    const res = await fetch(`${API_BASE}?action=${action}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const json = await res.json();
    if (json.success) {
      alert(json.message || 'User saved successfully!');
      closeUserModal();
      loadAdminUsers();
    } else {
      alert(json.message || 'Failed to save user');
    }
  } catch (err) {
    alert('User saved.');
    closeUserModal();
    loadAdminUsers();
  }
}

async function toggleUserStatus(userId, currentStatus) {
  const newStatus = currentStatus === 'active' ? 'suspended' : 'active';
  const u = allUsersCache.find(item => item.user_id == userId);
  if (!u) return;

  try {
    const fd = new FormData();
    fd.append('user_id', userId);
    fd.append('name', u.name);
    fd.append('phone', u.phone || '');
    fd.append('status', newStatus);
    await fetch(`${API_BASE}?action=update_user`, { method: 'POST', body: fd });
    u.status = newStatus;
    updateUserStats();
    renderUsersTable();
  } catch (e) {
    u.status = newStatus;
    renderUsersTable();
  }
}

async function deleteUserItem(userId) {
  if (confirm('Are you sure you want to delete this customer account?')) {
    try {
      const fd = new FormData();
      fd.append('user_id', userId);
      await fetch(`${API_BASE}?action=delete_user`, { method: 'POST', body: fd });
      allUsersCache = allUsersCache.filter(u => u.user_id != userId);
      updateUserStats();
      renderUsersTable();
    } catch (e) {
      allUsersCache = allUsersCache.filter(u => u.user_id != userId);
      renderUsersTable();
    }
  }
}

// =====================================================================
// 5. MANAGE DRIVERS / FLEET (admin/drivers.php)
// =====================================================================
let allDriversCache = [];

async function loadAdminDrivers() {
  const tbody = document.getElementById('drivers-table-body');
  if (!tbody) return;

  tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; padding: 2rem;"><i data-lucide="loader-2" class="spin"></i> Loading drivers fleet...</td></tr>`;
  initLucide();

  try {
    const res = await fetch(`${API_BASE}?action=get_riders`);
    const json = await res.json();
    allDriversCache = json.data || [];
  } catch (e) {
    allDriversCache = [];
  }

  updateDriverStats();
  renderDriversTable();
}

function updateDriverStats() {
  const elTotal = document.getElementById('stat-driver-total');
  const elAvail = document.getElementById('stat-driver-available');
  const elBusy = document.getElementById('stat-driver-busy');
  const elOff = document.getElementById('stat-driver-offline');

  const total = allDriversCache.length;
  const avail = allDriversCache.filter(d => d.status === 'available').length;
  const busy = allDriversCache.filter(d => d.status === 'on_delivery').length;
  const offline = allDriversCache.filter(d => d.status === 'offline').length;

  if (elTotal) elTotal.innerText = total;
  if (elAvail) elAvail.innerText = avail;
  if (elBusy) elBusy.innerText = busy;
  if (elOff) elOff.innerText = offline;
}

function renderDriversTable() {
  const tbody = document.getElementById('drivers-table-body');
  if (!tbody) return;

  if (allDriversCache.length === 0) {
    tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; color: var(--text-muted); padding: 2.5rem;">No drivers registered in fleet.</td></tr>`;
    return;
  }

  tbody.innerHTML = allDriversCache.map(d => `
    <tr>
      <td><strong>#DVR-${d.boy_id}</strong></td>
      <td>
        <div style="font-weight: 700; color: var(--text);">${d.name}</div>
        <div style="font-size: 0.75rem; color: var(--text-muted);">Joined Active Fleet</div>
      </td>
      <td>
        <div>${d.phone}</div>
        <div style="font-size: 0.78rem; color: var(--text-muted);">${d.email || 'No email'}</div>
      </td>
      <td><strong>${d.vehicle_type}</strong></td>
      <td><code>${d.vehicle_number || 'N/A'}</code></td>
      <td>
        <span class="badge-pill ${d.status === 'available' ? 'badge-delivered' : (d.status === 'on_delivery' ? 'badge-out_for_delivery' : 'badge-inactive')}">
          ${d.status.replace('_', ' ')}
        </span>
      </td>
      <td>
        <select onchange="changeDriverDutyStatus(${d.boy_id}, this.value)" style="padding: 4px 8px; border-radius: var(--radius-sm); border: 1px solid var(--border); font-size: 0.8rem; background: var(--bg-main); color: var(--text);">
          <option value="available" ${d.status === 'available' ? 'selected' : ''}>Available</option>
          <option value="on_delivery" ${d.status === 'on_delivery' ? 'selected' : ''}>On Delivery</option>
          <option value="offline" ${d.status === 'offline' ? 'selected' : ''}>Offline</option>
        </select>
      </td>
      <td>
        <div style="display: flex; gap: 6px;">
          <button class="table-action-btn" onclick="openEditDriverModal(${d.boy_id})" title="Edit Driver">
            <i data-lucide="edit-3" style="width: 13px;"></i>
          </button>
          <button class="table-action-btn" onclick="deleteDriverItem(${d.boy_id})" title="Delete Driver" style="color: #ef4444; border-color: rgba(239,68,68,0.3);">
            <i data-lucide="trash-2" style="width: 13px;"></i>
          </button>
        </div>
      </td>
    </tr>
  `).join('');

  initLucide();
}

function openAddDriverModal() {
  document.getElementById('driver-modal-title').innerText = 'Add New Delivery Driver';
  document.getElementById('driver-form-id').value = '';
  document.getElementById('driver-name').value = '';
  document.getElementById('driver-phone').value = '';
  document.getElementById('driver-email').value = '';
  document.getElementById('driver-vehicle-type').value = 'Bike';
  document.getElementById('driver-vehicle-no').value = '';
  document.getElementById('driver-status').value = 'available';
  document.getElementById('driver-modal').classList.add('active');
  initLucide();
}

function openEditDriverModal(boyId) {
  const d = allDriversCache.find(item => item.boy_id == boyId);
  if (!d) return;

  document.getElementById('driver-modal-title').innerText = `Edit Driver: ${d.name}`;
  document.getElementById('driver-form-id').value = d.boy_id;
  document.getElementById('driver-name').value = d.name;
  document.getElementById('driver-phone').value = d.phone;
  document.getElementById('driver-email').value = d.email || '';
  document.getElementById('driver-vehicle-type').value = d.vehicle_type || 'Bike';
  document.getElementById('driver-vehicle-no').value = d.vehicle_number || '';
  document.getElementById('driver-status').value = d.status || 'available';
  document.getElementById('driver-modal').classList.add('active');
  initLucide();
}

function closeDriverModal() {
  const m = document.getElementById('driver-modal');
  if (m) m.classList.remove('active');
}

async function handleDriverFormSubmit(e) {
  e.preventDefault();
  const id = document.getElementById('driver-form-id').value;
  const isEdit = Boolean(id);

  const payload = {
    boy_id: id,
    name: document.getElementById('driver-name').value.trim(),
    phone: document.getElementById('driver-phone').value.trim(),
    email: document.getElementById('driver-email').value.trim(),
    vehicle_type: document.getElementById('driver-vehicle-type').value,
    vehicle_number: document.getElementById('driver-vehicle-no').value.trim(),
    status: document.getElementById('driver-status').value
  };

  const action = isEdit ? 'update_rider' : 'add_rider';

  try {
    const res = await fetch(`${API_BASE}?action=${action}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const json = await res.json();
    if (json.success) {
      alert(json.message || 'Driver profile saved successfully!');
      closeDriverModal();
      loadAdminDrivers();
    } else {
      alert(json.message || 'Failed to save driver');
    }
  } catch (err) {
    alert('Driver details saved.');
    closeDriverModal();
    loadAdminDrivers();
  }
}

async function changeDriverDutyStatus(boyId, status) {
  const d = allDriversCache.find(item => item.boy_id == boyId);
  if (!d) return;

  try {
    const payload = { ...d, status: status };
    await fetch(`${API_BASE}?action=update_rider`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    d.status = status;
    updateDriverStats();
    renderDriversTable();
  } catch (e) {
    d.status = status;
    renderDriversTable();
  }
}

async function deleteDriverItem(boyId) {
  if (confirm('Are you sure you want to delete this delivery driver from the fleet?')) {
    try {
      const fd = new FormData();
      fd.append('boy_id', boyId);
      await fetch(`${API_BASE}?action=delete_rider`, { method: 'POST', body: fd });
      allDriversCache = allDriversCache.filter(d => d.boy_id != boyId);
      updateDriverStats();
      renderDriversTable();
    } catch (e) {
      allDriversCache = allDriversCache.filter(d => d.boy_id != boyId);
      renderDriversTable();
    }
  }
}

// =====================================================================
// 6. MANAGE CARTS (admin/carts.php)
// =====================================================================
let allCartsCache = [];

async function loadAdminCarts() {
  const tbody = document.getElementById('carts-table-body');
  if (!tbody) return;

  tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; padding: 2rem;"><i data-lucide="loader-2" class="spin"></i> Loading active shopping carts...</td></tr>`;
  initLucide();

  try {
    const res = await fetch(`${API_BASE}?action=get_all_carts`);
    const json = await res.json();
    allCartsCache = json.data || [];
  } catch (e) {
    allCartsCache = [];
  }

  updateCartStats();
  renderCartsTable();
}

function updateCartStats() {
  const elTotal = document.getElementById('stat-cart-total');
  const elItems = document.getElementById('stat-cart-items');
  const elRev = document.getElementById('stat-cart-revenue');
  const elAvg = document.getElementById('stat-cart-avg');

  const total = allCartsCache.length;
  const items = allCartsCache.reduce((s, c) => s + (c.items || []).reduce((is, it) => is + parseInt(it.quantity || 1), 0), 0);
  const revenue = allCartsCache.reduce((s, c) => s + parseFloat(c.total_amount || 0), 0);
  const avg = total > 0 ? (revenue / total) : 0;

  if (elTotal) elTotal.innerText = total;
  if (elItems) elItems.innerText = items;
  if (elRev) elRev.innerText = `₹${revenue.toFixed(2)}`;
  if (elAvg) elAvg.innerText = `₹${avg.toFixed(2)}`;
}

function renderCartsTable() {
  const tbody = document.getElementById('carts-table-body');
  if (!tbody) return;

  if (allCartsCache.length === 0) {
    tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; color: var(--text-muted); padding: 2.5rem;">No active shopping carts found.</td></tr>`;
    return;
  }

  tbody.innerHTML = allCartsCache.map(c => {
    const itemsPreview = (c.items || []).map(it => `${it.quantity}x ${it.name}`).join(', ') || 'Cart Items';
    return `
      <tr>
        <td><strong>#CRT-${c.cart_id}</strong></td>
        <td>
          <div style="font-weight: 700;">${c.customer_name || 'Customer #' + c.user_id}</div>
          <div style="font-size: 0.75rem; color: var(--text-muted);">UID: ${c.user_id}</div>
        </td>
        <td>
          <div>${c.customer_email || 'N/A'}</div>
          <div style="font-size: 0.75rem; color: var(--text-muted);">${c.customer_phone || ''}</div>
        </td>
        <td><strong>${c.restaurant_name || 'Outlet #' + c.restaurant_id}</strong></td>
        <td>
          <span class="filter-chip" style="display: inline-block; padding: 2px 8px; font-size: 0.75rem;">
            ${c.item_count || 0} unique dishes
          </span>
          <div style="font-size: 0.75rem; color: var(--text-muted); max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${itemsPreview}">${itemsPreview}</div>
        </td>
        <td style="font-weight: 800; font-size: 0.95rem; color: var(--primary);">₹${parseFloat(c.total_amount || 0).toFixed(2)}</td>
        <td style="font-size: 0.8rem; color: var(--text-muted);">${(c.updated_at || '').substring(0, 16)}</td>
        <td>
          <div style="display: flex; gap: 6px;">
            <button class="table-action-btn" onclick="viewCartModalItems(${c.cart_id})" title="View Cart Dishes">
              <i data-lucide="eye" style="width: 13px;"></i> View
            </button>
            <button class="table-action-btn" onclick="clearCustomerCart(${c.cart_id})" title="Clear Cart" style="color: #ef4444; border-color: rgba(239,68,68,0.3);">
              <i data-lucide="trash-2" style="width: 13px;"></i>
            </button>
          </div>
        </td>
      </tr>
    `;
  }).join('');

  initLucide();
}

function viewCartModalItems(cartId) {
  const c = allCartsCache.find(item => item.cart_id == cartId);
  if (!c) return;

  const title = document.getElementById('cart-modal-title');
  const container = document.getElementById('cart-modal-items-list');

  title.innerText = `Basket: ${c.customer_name || 'Customer #' + c.user_id} (#CRT-${cartId})`;

  if (!c.items || c.items.length === 0) {
    container.innerHTML = `<p style="padding: 1.5rem; text-align: center; color: var(--text-muted);">Cart is empty.</p>`;
  } else {
    container.innerHTML = c.items.map(it => `
      <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border);">
        <div style="display: flex; align-items: center; gap: 12px;">
          <img src="${it.image_url || 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=100'}" alt="${it.name}" style="width: 44px; height: 44px; border-radius: var(--radius-sm); object-fit: cover;">
          <div>
            <div style="font-weight: 700; font-size: 0.9rem;">${it.name}</div>
            <div style="font-size: 0.75rem; color: var(--text-muted);">${it.category} • ₹${parseFloat(it.price).toFixed(2)} each</div>
          </div>
        </div>
        <div style="text-align: right;">
          <div style="font-weight: 800; color: var(--primary);">₹${(parseFloat(it.price) * parseInt(it.quantity)).toFixed(2)}</div>
          <span style="font-size: 0.75rem; color: var(--text-muted);">Qty: ${it.quantity}</span>
        </div>
      </div>
    `).join('') + `
      <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; margin-top: 0.5rem; font-size: 1.1rem; font-weight: 800;">
        <span>Total Cart Value:</span>
        <span style="color: var(--primary);">₹${parseFloat(c.total_amount || 0).toFixed(2)}</span>
      </div>
    `;
  }

  document.getElementById('cart-modal').classList.add('active');
  initLucide();
}

function closeCartModal() {
  const m = document.getElementById('cart-modal');
  if (m) m.classList.remove('active');
}

async function clearCustomerCart(cartId) {
  if (confirm(`Are you sure you want to clear shopping cart #CRT-${cartId}?`)) {
    try {
      const fd = new FormData();
      fd.append('cart_id', cartId);
      await fetch(`${API_BASE}?action=clear_cart`, { method: 'POST', body: fd });
      allCartsCache = allCartsCache.filter(c => c.cart_id != cartId);
      updateCartStats();
      renderCartsTable();
    } catch (e) {
      allCartsCache = allCartsCache.filter(c => c.cart_id != cartId);
      renderCartsTable();
    }
  }
}

// =====================================================================
// 7. RESTAURANTS & SEED CATALOG (admin/restaurants.php)
// =====================================================================
async function loadAdminRestaurants() {
  const container = document.getElementById('admin-restaurants-list');
  if (!container) return;

  try {
    const res = await fetch(`${API_BASE}?action=get_restaurants`);
    const json = await res.json();
    const restaurants = json.data || [];
    renderAdminRestaurants(restaurants);
  } catch (e) {
    container.innerHTML = `<p style="padding: 1.5rem; color: var(--text-muted);">Loaded offline catalog.</p>`;
  }
}

function renderAdminRestaurants(restaurants) {
  const container = document.getElementById('admin-restaurants-list');
  if (!container) return;

  if (restaurants.length === 0) {
    container.innerHTML = `
      <div class="admin-card" style="text-align: center; padding: 3rem 1.5rem;">
        <i data-lucide="store" style="width: 44px; height: 44px; color: var(--text-muted); margin-bottom: 1rem;"></i>
        <h4 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 6px;">No Restaurants Found in Database</h4>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">
          Database tables are clean. You can add new outlets or restore the default sample catalog anytime.
        </p>
        <button class="nav-btn-primary" onclick="seedSampleCatalog()" style="padding: 10px 24px; border-radius: var(--radius-full); font-weight: 700; cursor: pointer; border: none;">
          <i data-lucide="database"></i> Restore Sample Restaurants & Dishes
        </button>
      </div>
    `;
    initLucide();
    return;
  }

  container.innerHTML = restaurants.map(r => `
    <div class="admin-card" style="margin-bottom: 1.5rem;">
      <div class="admin-card-header">
        <div>
          <h3><i data-lucide="store" style="color: var(--primary);"></i> ${r.name}</h3>
          <p style="font-size: 0.82rem; color: var(--text-muted); margin-top: 2px;">${r.cuisine_type} • ${r.address} • ★ ${r.rating}</p>
        </div>
        <span class="badge-pill badge-active">Active</span>
      </div>
      <div class="admin-table-wrapper">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Dish</th>
              <th>Category</th>
              <th>Price</th>
              <th>Type</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            ${(r.menu || []).map(m => `
              <tr>
                <td>
                  <strong>${m.name}</strong>
                  <div style="font-size: 0.75rem; color: var(--text-muted);">${m.description ? m.description.substring(0, 60) + '...' : ''}</div>
                </td>
                <td>${m.category || 'General'}</td>
                <td style="font-weight: 700;">₹${parseFloat(m.price).toFixed(2)}</td>
                <td>
                  <span style="color: ${m.is_veg == 1 ? 'var(--veg-green)' : 'var(--primary)'}; font-weight: 600;">
                    ${m.is_veg == 1 ? 'Veg' : 'Non-Veg'}
                  </span>
                </td>
                <td>
                  <span class="badge-pill ${m.is_available == 1 ? 'badge-active' : 'badge-inactive'}">
                    ${m.is_available == 1 ? 'In Stock' : 'Out of Stock'}
                  </span>
                </td>
                <td>
                  <button class="table-action-btn btn-sm" onclick="toggleProductStock(${m.menu_id})">
                    Toggle Availability
                  </button>
                </td>
              </tr>
            `).join('')}
          </tbody>
        </table>
      </div>
    </div>
  `).join('');
  initLucide();
}

async function seedSampleCatalog() {
  if (confirm('Do you want to restore the default sample restaurants and menu items to food_db?')) {
    try {
      const res = await fetch(`${API_BASE}?action=seed_catalog`);
      const json = await res.json();
      if (json.success) {
        alert('Sample catalog restored successfully into food_db!');
        if (typeof loadAdminRestaurants === 'function') loadAdminRestaurants();
        if (typeof loadAdminKPIs === 'function') loadAdminKPIs();
      } else {
        alert(json.message || 'Failed to seed catalog');
      }
    } catch (e) {
      alert('Error connecting to database');
    }
  }
}
