/**
 * Food Management System (FMS) - BITEBUDDY Platform User Side Controller
 * Handles Restaurant Browsing, Cart, Checkout, Animated Live Tracking & Reviews
 */

const API_BASE = window.location.pathname.includes('/user/') ? '../api/index.php' : 'api/index.php';

// Global State
const state = {
  restaurants: [],
  filteredRestaurants: [],
  selectedRestaurant: null,
  cart: {}, // { [menu_id]: { dish, qty } }
  appliedCoupon: null,
  activeFilter: 'all',
  addresses: [],
  selectedAddressId: 1,
  activeOrder: null
};

// Fallback Mock Data in case server is viewed without MySQL running
const fallbackRestaurants = [
  {
    restaurant_id: "1",
    name: "Tandoor & Curry Express",
    cuisine_type: "North Indian, Mughlai",
    rating: "4.6",
    address: "Food Galleria, Andheri West, Mumbai",
    menu: [
      { menu_id: "1", name: "Paneer Butter Masala", price: "320.00", is_veg: "1", description: "Rich cottage cheese cooked in creamy buttery tomato gravy.", image_url: "https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=500&auto=format&fit=crop&q=60" },
      { menu_id: "2", name: "Butter Garlic Naan", price: "60.00", is_veg: "1", description: "Fresh tandoori flatbread topped with minced garlic and melted butter.", image_url: "https://images.unsplash.com/photo-1626074353765-517a681e40be?w=500&auto=format&fit=crop&q=60" },
      { menu_id: "3", name: "Dum Hyderabadi Biryani", price: "380.00", is_veg: "0", description: "Aromatic basmati rice cooked with authentic spices and fried onions.", image_url: "https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=500&auto=format&fit=crop&q=60" },
      { menu_id: "4", name: "Gulab Jamun (2 Pcs)", price: "90.00", is_veg: "1", description: "Warm milk dumplings soaked in cardamom infused saffron sugar syrup.", image_url: "https://images.unsplash.com/photo-1541832676-9b763b0239ab?w=500&auto=format&fit=crop&q=60" }
    ]
  },
  {
    restaurant_id: "2",
    name: "Pizza Gusto & Italian Cafe",
    cuisine_type: "Italian, Fast Food",
    rating: "4.4",
    address: "Bandra Kurla Complex, Mumbai",
    menu: [
      { menu_id: "5", name: "Margherita Pizza (12 inch)", price: "450.00", is_veg: "1", description: "Classic wood-fired pizza with San Marzano tomato sauce and mozzarella.", image_url: "https://images.unsplash.com/photo-1604382355076-af4b0eb60143?w=500&auto=format&fit=crop&q=60" },
      { menu_id: "6", name: "Classic Farmhouse Pizza", price: "480.00", is_veg: "1", description: "Loaded with crunchy capsicum, sweet corn, mushrooms, and mozzarella cheese.", image_url: "https://images.unsplash.com/photo-1513104890138-7c749659a591?w=500&auto=format&fit=crop&q=60" }
    ]
  },
  {
    restaurant_id: "3",
    name: "Royal Dum Biryani House",
    cuisine_type: "Biryani, Mughlai, Kebabs",
    rating: "4.8",
    address: "Heritage Arcade, Bandra West, Mumbai",
    menu: [
      { menu_id: "7", name: "Special Chicken Dum Biryani", price: "360.00", is_veg: "0", description: "Fragrant basmati rice layered with spiced marinated chicken and saffron.", image_url: "https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=500&auto=format&fit=crop&q=60" },
      { menu_id: "8", name: "Hyderabadi Veg Dum Biryani", price: "280.00", is_veg: "1", description: "Rich vegetable medley layered with aromatic basmati rice.", image_url: "https://images.unsplash.com/photo-1642821373181-696a54913e9a?w=500&auto=format&fit=crop&q=60" },
      { menu_id: "9", name: "Galouti Kebab Platter", price: "420.00", is_veg: "0", description: "Melt-in-mouth smoked minced mutton kebabs served with mint chutney.", image_url: "https://images.unsplash.com/photo-1599488615731-7e5c2823ff28?w=500&auto=format&fit=crop&q=60" }
    ]
  },
  {
    restaurant_id: "4",
    name: "Wok & Roll Asian Kitchen",
    cuisine_type: "Chinese, Momos, Asian, Thai",
    rating: "4.5",
    address: "Phoenix Marketcity, Kurla, Mumbai",
    menu: [
      { menu_id: "10", name: "Steamed Chicken Dimsums (6 pcs)", price: "240.00", is_veg: "0", description: "Delicate dumplings filled with seasoned chicken, served with spicy dip.", image_url: "https://images.unsplash.com/photo-1496116218417-1a781b1c416c?w=500&auto=format&fit=crop&q=60" },
      { menu_id: "11", name: "Schezwan Hakka Noodles", price: "210.00", is_veg: "1", description: "Wok-tossed noodles with crunchy bell peppers and fiery schezwan sauce.", image_url: "https://images.unsplash.com/photo-1585032226651-759b368d7246?w=500&auto=format&fit=crop&q=60" },
      { menu_id: "12", name: "Crispy Chilli Paneer", price: "260.00", is_veg: "1", description: "Batter-fried paneer cubes tossed in garlic, ginger, and soy sauce glaze.", image_url: "https://images.unsplash.com/photo-1567188040759-fb8a883dc6d8?w=500&auto=format&fit=crop&q=60" }
    ]
  },
  {
    restaurant_id: "5",
    name: "The Burger Club & Cafe",
    cuisine_type: "Burgers, American, Fast Food",
    rating: "4.3",
    address: "Lane 5, Koregaon Park, Pune",
    menu: [
      { menu_id: "13", name: "Double Cheese Smash Burger", price: "299.00", is_veg: "0", description: "Crispy grilled patty topped with double cheddar and caramelized onions.", image_url: "https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=500&auto=format&fit=crop&q=60" },
      { menu_id: "14", name: "Crispy Peri Peri Fries", price: "140.00", is_veg: "1", description: "Golden french fries dusted in spicy peri-peri seasoning.", image_url: "https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=500&auto=format&fit=crop&q=60" },
      { menu_id: "15", name: "Belgian Chocolate Thickshake", price: "190.00", is_veg: "1", description: "Decadent dark chocolate milkshake with real cocoa.", image_url: "https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=500&auto=format&fit=crop&q=60" }
    ]
  },
  {
    restaurant_id: "6",
    name: "South Feast Dosa Corner",
    cuisine_type: "South Indian, Dosas, Coffee",
    rating: "4.7",
    address: "Near Matunga Station Central, Mumbai",
    menu: [
      { menu_id: "16", name: "Mysore Masala Butter Dosa", price: "160.00", is_veg: "1", description: "Crispy golden crepe with spicy red chutney spread and potato masala.", image_url: "https://images.unsplash.com/photo-1668236543090-82eba5ee5976?w=500&auto=format&fit=crop&q=60" },
      { menu_id: "17", name: "Steamed Button Idli (with Sambar)", price: "110.00", is_veg: "1", description: "Fluffy steamed rice cakes served with hot lentil vegetable sambar.", image_url: "https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=500&auto=format&fit=crop&q=60" },
      { menu_id: "18", name: "Degree Filter Coffee", price: "70.00", is_veg: "1", description: "Traditional South Indian frothy filter brew made with chicory-blended coffee.", image_url: "https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=500&auto=format&fit=crop&q=60" }
    ]
  }
];

// Initialize
document.addEventListener('DOMContentLoaded', () => {
  initTheme();
  initLucide();
  highlightActiveNav();
  checkAuthState();
  initCartFromStorage();
  initCategoryCarousel();
  loadData();
  setupEventListeners();
});

function highlightActiveNav() {
  const currentPath = window.location.pathname.toLowerCase();
  const navMenu = document.getElementById('nav-menu-link');
  const navOrders = document.getElementById('nav-orders-link');
  const navCart = document.getElementById('nav-cart-link');

  if (navMenu) navMenu.classList.remove('active');
  if (navOrders) navOrders.classList.remove('active');
  if (navCart) navCart.classList.remove('active');

  if (currentPath.includes('cart.php')) {
    if (navCart) navCart.classList.add('active');
  } else if (currentPath.includes('orders.php')) {
    if (navOrders) navOrders.classList.add('active');
  } else {
    if (navMenu) navMenu.classList.add('active');
  }
}

function initCartFromStorage() {
  const savedCart = localStorage.getItem('fms_cart');
  if (savedCart) {
    try {
      state.cart = JSON.parse(savedCart);
    } catch (e) {}
  }
  updateCartUI();
  if (document.getElementById('full-cart-content')) {
    renderFullCartPage();
  }
}

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
// DATA LOADING
// =====================================================================
async function loadData() {
  try {
    const res = await fetch(`${API_BASE}?action=get_restaurants`);
    const json = await res.json();
    if (json.success && json.data.length > 0) {
      state.restaurants = json.data;
    } else {
      state.restaurants = fallbackRestaurants;
    }
  } catch (err) {
    state.restaurants = fallbackRestaurants;
  }

  state.filteredRestaurants = [...state.restaurants];
  renderRestaurants();
  checkAutoAddPendingDish();

  // Load Saved Addresses
  try {
    const addrRes = await fetch(`${API_BASE}?action=get_addresses&user_id=1`);
    const addrJson = await addrRes.json();
    if (addrJson.success && addrJson.data.length > 0) {
      state.addresses = addrJson.data;
    } else {
      state.addresses = [
        { address_id: 1, address_type: 'Home', street_address: 'Flat 402, Royal Palms, Link Road', landmark: 'Near City Mall', city: 'Mumbai' },
        { address_id: 2, address_type: 'Work', street_address: 'Tower B, 7th Floor, Mindspace IT Park', landmark: 'Opposite Metro Station', city: 'Mumbai' }
      ];
    }
  } catch (e) {
    state.addresses = [
      { address_id: 1, address_type: 'Home', street_address: 'Flat 402, Royal Palms, Link Road', landmark: 'Near City Mall', city: 'Mumbai' }
    ];
  }
}

// =====================================================================
// RENDER RESTAURANTS & FILTERS
// =====================================================================
function renderRestaurants() {
  const grid = document.getElementById('restaurant-grid');
  if (!grid) return;

  if (state.filteredRestaurants.length === 0) {
    grid.innerHTML = `
      <div style="grid-column: 1/-1; text-align: center; padding: 3rem 1rem;">
        <i data-lucide="utensils-crossed" style="width: 48px; height: 48px; color: var(--text-light); margin-bottom: 1rem;"></i>
        <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 0.5rem;">No restaurants found</h3>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Try searching for a different dish, cuisine, or clearing filters.</p>
      </div>
    `;
    initLucide();
    return;
  }

  grid.innerHTML = state.filteredRestaurants.map(r => {
    const defaultImg = (r.menu && r.menu[0] && r.menu[0].image_url) ? r.menu[0].image_url : 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=500&auto=format&fit=crop&q=60';
    return `
      <div class="restaurant-card" onclick="openRestaurantMenu('${r.restaurant_id}')">
        <div class="restaurant-image-wrapper">
          <img src="${defaultImg}" alt="${r.name}" loading="lazy">
          <div class="discount-tag">
            <i data-lucide="tag" style="width: 14px; height: 14px;"></i> 50% OFF up to ₹100
          </div>
          <div class="delivery-badge">25-30 min • 2.4 km</div>
        </div>
        <div class="restaurant-details">
          <div class="restaurant-title-row">
            <h3>${r.name}</h3>
            <div class="rating-badge">★ ${r.rating || '4.5'}</div>
          </div>
          <div class="cuisine-row">${r.cuisine_type || 'Multi-Cuisine, Fast Food'}</div>
          <div class="meta-row">
            <span>₹350 for two</span>
            <span style="color: var(--primary); font-weight: 600;">View Menu →</span>
          </div>
        </div>
      </div>
    `;
  }).join('');

  initLucide();
}

function handleSearch(term) {
  const q = term.toLowerCase().trim();
  const clearBtn = document.getElementById('search-clear-btn');
  if (clearBtn) {
    clearBtn.style.display = q.length > 0 ? 'flex' : 'none';
  }

  if (!q) {
    applyCurrentFilter();
    return;
  }
  state.filteredRestaurants = state.restaurants.filter(r => {
    const matchesName = r.name.toLowerCase().includes(q);
    const matchesCuisine = (r.cuisine_type || '').toLowerCase().includes(q);
    const matchesDish = (r.menu || []).some(m => m.name.toLowerCase().includes(q) || (m.category || '').toLowerCase().includes(q));
    return matchesName || matchesCuisine || matchesDish;
  });
  renderRestaurants();
}

function clearSearch() {
  const searchInput = document.getElementById('search-input');
  if (searchInput) {
    searchInput.value = '';
  }
  const clearBtn = document.getElementById('search-clear-btn');
  if (clearBtn) {
    clearBtn.style.display = 'none';
  }
  applyCurrentFilter();
  renderRestaurants();
}

function scrollCategoryCarousel(direction) {
  const strip = document.getElementById('category-strip');
  if (!strip) return;
  const scrollDistance = Math.max(320, strip.clientWidth * 0.7);
  strip.scrollBy({
    left: direction * scrollDistance,
    behavior: 'smooth'
  });
}

function initCategoryCarousel() {
  const strip = document.getElementById('category-strip');
  if (!strip) return;

  // Horizontal mouse wheel scrolling
  strip.addEventListener('wheel', (e) => {
    if (Math.abs(e.deltaY) > Math.abs(e.deltaX)) {
      e.preventDefault();
      strip.scrollLeft += e.deltaY;
    }
  }, { passive: false });
}

function setFilter(filterType) {
  state.activeFilter = filterType;
  document.querySelectorAll('.filter-chip').forEach(btn => {
    btn.classList.toggle('active', btn.dataset.filter === filterType);
  });
  document.querySelectorAll('.category-circle-item').forEach(el => {
    el.classList.toggle('active', el.dataset.cat === filterType);
  });
  applyCurrentFilter();
}

function applyCurrentFilter() {
  let list = [...state.restaurants];
  const filter = (state.activeFilter || 'all').toLowerCase();

  if (filter === 'all') {
    // Show all
  } else if (filter === 'veg') {
    list = list.filter(r => (r.menu || []).some(m => m.is_veg == 1));
  } else if (filter === 'rating') {
    list = list.filter(r => parseFloat(r.rating) >= 4.5);
  } else if (filter === 'biryani') {
    list = list.filter(r => 
      (r.cuisine_type || '').toLowerCase().includes('biryani') || 
      (r.name || '').toLowerCase().includes('biryani') ||
      (r.menu || []).some(m => (m.category || '').toLowerCase().includes('biryani') || m.name.toLowerCase().includes('biryani'))
    );
  } else if (filter === 'pizza') {
    list = list.filter(r => 
      (r.cuisine_type || '').toLowerCase().includes('italian') || 
      (r.cuisine_type || '').toLowerCase().includes('pizza') || 
      (r.name || '').toLowerCase().includes('pizza') ||
      (r.menu || []).some(m => (m.category || '').toLowerCase().includes('pizza') || m.name.toLowerCase().includes('pizza'))
    );
  } else {
    // Dynamic matching for any category name across cuisine, restaurant name, and dishes
    list = list.filter(r => {
      const cType = (r.cuisine_type || '').toLowerCase();
      const rName = (r.name || '').toLowerCase();
      const matchMenu = (r.menu || []).some(m => 
        (m.category || '').toLowerCase().includes(filter) || 
        m.name.toLowerCase().includes(filter) ||
        (m.description || '').toLowerCase().includes(filter)
      );
      return cType.includes(filter) || rName.includes(filter) || matchMenu;
    });
  }

  state.filteredRestaurants = list;
  renderRestaurants();
}

function filterByCategory(category) {
  const cat = (category || '').toLowerCase();
  const currentItem = document.querySelector(`.category-circle-item[data-cat="${cat}"]`);
  const isAlreadyActive = currentItem && currentItem.classList.contains('active');

  // Clear active from all category circles
  document.querySelectorAll('.category-circle-item').forEach(el => el.classList.remove('active'));

  if (isAlreadyActive) {
    // Toggle off to 'all'
    state.activeFilter = 'all';
    document.querySelectorAll('.filter-chip').forEach(btn => {
      btn.classList.toggle('active', btn.dataset.filter === 'all');
    });
  } else {
    if (currentItem) currentItem.classList.add('active');
    state.activeFilter = cat;
    document.querySelectorAll('.filter-chip').forEach(btn => {
      btn.classList.toggle('active', btn.dataset.filter === cat);
    });
  }

  applyCurrentFilter();

  const target = document.getElementById('restaurants-section');
  if (target) {
    target.scrollIntoView({ behavior: 'smooth' });
  }
}

// =====================================================================
// RESTAURANT MENU MODAL
// =====================================================================
function openRestaurantMenu(restaurantId) {
  const r = state.restaurants.find(item => item.restaurant_id == restaurantId);
  if (!r) return;

  state.selectedRestaurant = r;
  const modal = document.getElementById('menu-modal');
  const title = document.getElementById('menu-modal-title');
  const cuisine = document.getElementById('menu-modal-cuisine');
  const addr = document.getElementById('menu-modal-address');

  title.innerText = r.name;
  cuisine.innerText = r.cuisine_type || 'Multi-Cuisine';
  addr.innerText = `${r.address} • ★ ${r.rating} (500+ reviews)`;

  renderMenuItems(r.menu || []);
  modal.classList.add('active');
  initLucide();
}

function renderMenuItems(menuList) {
  const body = document.getElementById('menu-items-body');
  if (!body) return;

  if (menuList.length === 0) {
    body.innerHTML = `<p style="text-align: center; color: var(--text-muted); padding: 2rem;">No menu items listed yet.</p>`;
    return;
  }

  body.innerHTML = menuList.map(item => {
    const qty = state.cart[item.menu_id] ? state.cart[item.menu_id].qty : 0;
    const isVeg = item.is_veg == 1;
    return `
      <div class="dish-item-card">
        <div class="dish-info">
          <div class="dish-type-badge ${isVeg ? 'veg' : 'nonveg'}" title="${isVeg ? 'Vegetarian' : 'Non-Vegetarian'}"></div>
          <div class="dish-name">${item.name}</div>
          <div class="dish-price">₹${parseFloat(item.price).toFixed(2)}</div>
          <div class="dish-desc">${item.description || 'Delicious freshly prepared recipe with authentic spices and chef special ingredients.'}</div>
        </div>
        <div class="dish-action-col">
          <div class="dish-thumb">
            <img src="${item.image_url || 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400'}" alt="${item.name}" loading="lazy">
          </div>
          ${qty === 0 ? `
            <button class="add-btn" onclick="addToCart('${item.menu_id}')">
              ADD <i data-lucide="plus" style="width: 14px; height: 14px;"></i>
            </button>
          ` : `
            <div class="qty-counter">
              <button class="qty-btn" onclick="updateCartQty('${item.menu_id}', -1)">−</button>
              <span>${qty}</span>
              <button class="qty-btn" onclick="updateCartQty('${item.menu_id}', 1)">+</button>
            </div>
          `}
        </div>
      </div>
    `;
  }).join('');

  initLucide();
}

function closeMenuModal() {
  const modal = document.getElementById('menu-modal');
  if (modal) modal.classList.remove('active');
}

// =====================================================================
// CART MANAGEMENT & AUTHENTICATION REDIRECT
// =====================================================================
function checkAutoAddPendingDish() {
  const urlParams = new URLSearchParams(window.location.search);
  const autoAddId = urlParams.get('auto_add') || sessionStorage.getItem('fms_pending_dish');
  const savedUser = localStorage.getItem('fms_user');

  if (autoAddId && savedUser) {
    sessionStorage.removeItem('fms_pending_dish');
    sessionStorage.removeItem('fms_redirect_url');
    if (urlParams.has('auto_add')) {
      window.history.replaceState({}, document.title, window.location.pathname);
    }
    // Add dish
    addToCart(autoAddId);
  }
}

function addToCart(menuId) {
  // REQUIRE LOGIN: If not signed in or registered, redirect directly to login page!
  const savedUser = localStorage.getItem('fms_user');
  if (!savedUser) {
    sessionStorage.setItem('fms_pending_dish', menuId);
    sessionStorage.setItem('fms_redirect_url', window.location.href);
    window.location.href = 'login.php';
    return;
  }

  let dish = null;
  for (const r of state.restaurants) {
    const found = (r.menu || []).find(m => m.menu_id == menuId);
    if (found) {
      dish = { ...found, restaurant_id: r.restaurant_id, restaurant_name: r.name };
      break;
    }
  }

  if (!dish) return;

  if (!state.cart[menuId]) {
    state.cart[menuId] = {
      dish: dish,
      qty: 1
    };
  } else {
    state.cart[menuId].qty += 1;
  }

  localStorage.setItem('fms_cart', JSON.stringify(state.cart));
  updateCartUI();
  syncCartToBackend();
  if (state.selectedRestaurant) {
    renderMenuItems(state.selectedRestaurant.menu || []);
  }
}

function updateCartQty(menuId, delta) {
  // If attempting to increase qty without login, redirect to login page
  const savedUser = localStorage.getItem('fms_user');
  if (!savedUser && delta > 0) {
    sessionStorage.setItem('fms_pending_dish', menuId);
    sessionStorage.setItem('fms_redirect_url', window.location.href);
    window.location.href = 'login.php';
    return;
  }

  if (!state.cart[menuId]) return;

  state.cart[menuId].qty += delta;
  if (state.cart[menuId].qty <= 0) {
    delete state.cart[menuId];
  }

  localStorage.setItem('fms_cart', JSON.stringify(state.cart));
  updateCartUI();
  syncCartToBackend();
  if (state.selectedRestaurant) {
    renderMenuItems(state.selectedRestaurant.menu || []);
  }
}

function removeFromCart(menuId) {
  delete state.cart[menuId];
  localStorage.setItem('fms_cart', JSON.stringify(state.cart));
  updateCartUI();
  syncCartToBackend();
  if (state.selectedRestaurant) {
    renderMenuItems(state.selectedRestaurant.menu || []);
  }
}

function clearFullCart() {
  if (confirm('Are you sure you want to clear your food basket?')) {
    state.cart = {};
    localStorage.removeItem('fms_cart');
    updateCartUI();
    syncCartToBackend();
  }
}

function syncCartToBackend() {
  const savedUser = localStorage.getItem('fms_user');
  if (!savedUser) return;
  try {
    const user = JSON.parse(savedUser);
    const userId = user.user_id || 1;
    const items = Object.keys(state.cart).map(mId => ({
      menu_id: mId,
      quantity: state.cart[mId].qty
    }));
    const rId = Object.values(state.cart)[0]?.dish?.restaurant_id || 1;

    fetch(`${API_BASE}?action=sync_cart`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        user_id: userId,
        restaurant_id: rId,
        items: items
      })
    }).catch(() => {});
  } catch (e) {}
}

function updateCartUI() {
  const keys = Object.keys(state.cart);
  const totalCount = keys.reduce((acc, k) => acc + state.cart[k].qty, 0);

  // Update all badges on page
  document.querySelectorAll('#cart-badge').forEach(badge => {
    badge.innerText = totalCount;
    badge.style.display = totalCount > 0 ? 'inline-flex' : 'none';
  });

  // Re-render full cart page if present
  if (document.getElementById('full-cart-content')) {
    renderFullCartPage();
  }

  const container = document.getElementById('cart-items-list');
  if (!container) return;

  if (keys.length === 0) {
    container.innerHTML = `
      <div style="text-align: center; padding: 3rem 1rem;">
        <i data-lucide="shopping-bag" style="width: 48px; height: 48px; color: var(--text-light); margin-bottom: 1rem;"></i>
        <h4 style="font-weight: 700; margin-bottom: 4px;">Your cart is empty</h4>
        <p style="color: var(--text-muted); font-size: 0.85rem;">Explore top restaurants and add your favourite dishes!</p>
      </div>
    `;
    updateBillTotals(0);
    initLucide();
    return;
  }

  let subtotal = 0;
  container.innerHTML = keys.map(k => {
    const item = state.cart[k];
    const itemTotal = item.qty * parseFloat(item.dish.price);
    subtotal += itemTotal;
    return `
      <div class="cart-item-row">
        <div>
          <div class="cart-item-name">
            <span class="dish-type-badge ${item.dish.is_veg == 1 ? 'veg' : 'nonveg'}"></span>
            ${item.dish.name}
          </div>
          <div style="font-size: 0.8rem; color: var(--text-muted);">₹${parseFloat(item.dish.price).toFixed(2)} × ${item.qty}</div>
        </div>
        <div style="display: flex; align-items: center; gap: 10px;">
          <div class="qty-counter" style="margin-top: 0; padding: 2px 8px;">
            <button class="qty-btn" onclick="updateCartQty('${k}', -1)">−</button>
            <span style="font-size: 0.82rem;">${item.qty}</span>
            <button class="qty-btn" onclick="updateCartQty('${k}', 1)">+</button>
          </div>
          <span style="font-weight: 700; font-size: 0.88rem; width: 60px; text-align: right;">₹${itemTotal.toFixed(2)}</span>
        </div>
      </div>
    `;
  }).join('');

  updateBillTotals(subtotal);
  initLucide();
}

function updateBillTotals(subtotal) {
  const delFee = subtotal > 0 ? (subtotal >= 500 ? 0 : 30.00) : 0;
  const tax = subtotal > 0 ? (subtotal * 0.05) : 0;
  
  let discount = 0;
  if (state.appliedCoupon === 'BITEBUDDY50' || state.appliedCoupon === 'COMATOZZE50' || state.appliedCoupon === 'ZOMATO50') {
    discount = Math.min(subtotal * 0.5, 150);
  } else if (state.appliedCoupon === 'WELCOME') {
    discount = Math.min(100, subtotal);
  }

  const grandTotal = Math.max(0, subtotal + delFee + tax - discount);

  const elSub = document.getElementById('bill-subtotal');
  const elDel = document.getElementById('bill-delivery');
  const elTax = document.getElementById('bill-tax');
  const elDisc = document.getElementById('bill-discount');
  const elTotal = document.getElementById('bill-grand-total');
  const elBtnTotal = document.getElementById('checkout-btn-total');

  if (elSub) elSub.innerText = `₹${subtotal.toFixed(2)}`;
  if (elDel) elDel.innerText = delFee === 0 ? 'FREE' : `₹${delFee.toFixed(2)}`;
  if (elTax) elTax.innerText = `₹${tax.toFixed(2)}`;
  if (elDisc) {
    elDisc.innerText = `-₹${discount.toFixed(2)}`;
    elDisc.parentElement.style.display = discount > 0 ? 'flex' : 'none';
  }
  if (elTotal) elTotal.innerText = `₹${grandTotal.toFixed(2)}`;
  if (elBtnTotal) elBtnTotal.innerText = `₹${grandTotal.toFixed(2)}`;

  const addrBox = document.getElementById('cart-address-select');
  if (addrBox && state.addresses.length > 0) {
    const cur = state.addresses[0];
    addrBox.innerHTML = `
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px;">
        <strong><i data-lucide="map-pin" style="width: 14px; height: 14px; color: var(--primary);"></i> Deliver to ${cur.address_type || 'Home'}</strong>
        <span style="font-size: 0.75rem; color: var(--primary); cursor: pointer;">Default</span>
      </div>
      <p style="color: var(--text-muted); font-size: 0.8rem;">${cur.street_address}, ${cur.landmark ? cur.landmark + ', ' : ''}${cur.city}</p>
    `;
    initLucide();
  }
}

function applyCoupon() {
  const input = document.getElementById('coupon-input');
  const code = (input.value || '').trim().toUpperCase();
  if (code === 'BITEBUDDY50' || code === 'COMATOZZE50' || code === 'ZOMATO50' || code === 'WELCOME') {
    state.appliedCoupon = code;
    alert(`🎉 Coupon "${code}" applied successfully!`);
    updateCartUI();
  } else {
    alert('Invalid coupon code. Try "BITEBUDDY50" or "WELCOME"');
  }
}

function toggleCartDrawer() {
  const drawer = document.getElementById('cart-drawer');
  const overlay = document.getElementById('cart-overlay');
  if (!drawer) return;
  const isActive = drawer.classList.contains('active');

  if (isActive) {
    drawer.classList.remove('active');
    overlay.classList.remove('active');
  } else {
    updateCartUI();
    drawer.classList.add('active');
    overlay.classList.add('active');
  }
}

// =====================================================================
// CHECKOUT & PAYMENT MODAL
// =====================================================================
function openCheckoutModal() {
  if (Object.keys(state.cart).length === 0) {
    alert('Please add items to your cart before proceeding!');
    return;
  }
  toggleCartDrawer();
  const modal = document.getElementById('checkout-modal');
  if (modal) modal.classList.add('active');
}

function closeCheckoutModal() {
  const modal = document.getElementById('checkout-modal');
  if (modal) modal.classList.remove('active');
}

function selectPaymentMethod(cardElem, method) {
  document.querySelectorAll('.payment-opt-card').forEach(c => c.classList.remove('selected'));
  cardElem.classList.add('selected');
  cardElem.dataset.method = method;
}

async function processPaymentAndPlaceOrder() {
  const payBtn = document.getElementById('pay-now-btn');
  payBtn.disabled = true;
  payBtn.innerHTML = `Contacting Payment Gateway...`;

  const selectedOpt = document.querySelector('.payment-opt-card.selected');
  const paymentMethod = selectedOpt ? selectedOpt.innerText.trim() : 'UPI';

  const items = Object.keys(state.cart).map(k => ({
    menu_id: k,
    quantity: state.cart[k].qty,
    price: state.cart[k].dish.price
  }));

  const restaurantId = Object.values(state.cart)[0].dish.restaurant_id || 1;
  const subtotal = Object.values(state.cart).reduce((sum, it) => sum + (it.qty * parseFloat(it.dish.price)), 0);
  const total = subtotal + 30.00;

  const payload = {
    user_id: 1,
    restaurant_id: restaurantId,
    address_id: state.selectedAddressId || 1,
    tot_amount: total,
    delivery_fee: 30.00,
    payment_method: paymentMethod,
    order_notes: 'Ring the doorbell and contactless drop.',
    items: items
  };

  try {
    const res = await fetch(`${API_BASE}?action=place_order`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const result = await res.json();

    setTimeout(() => {
      payBtn.disabled = false;
      payBtn.innerHTML = `Place Order & Pay`;
      closeCheckoutModal();

      const orderId = result.data ? result.data.order_id : Math.floor(1000 + Math.random() * 9000);
      state.cart = {};
      localStorage.removeItem('fms_cart');
      updateCartUI();
      launchLiveOrderTracking(orderId);
    }, 1200);

  } catch (err) {
    setTimeout(() => {
      payBtn.disabled = false;
      payBtn.innerHTML = `Place Order & Pay`;
      closeCheckoutModal();
      state.cart = {};
      localStorage.removeItem('fms_cart');
      updateCartUI();
      launchLiveOrderTracking(Math.floor(1000 + Math.random() * 9000));
    }, 1000);
  }
}

// =====================================================================
// FULL-PAGE CART & CHECKOUT CONTROLLER (cart.php)
// =====================================================================
function renderFullCartPage() {
  const container = document.getElementById('full-cart-content');
  if (!container) return;

  const savedUser = localStorage.getItem('fms_user');
  if (!savedUser) {
    container.innerHTML = `
      <div class="cart-empty-box">
        <div class="cart-empty-icon" style="background: rgba(226, 55, 68, 0.1); color: var(--primary);">
          <i data-lucide="lock" style="width: 36px; height: 36px;"></i>
        </div>
        <h3 style="font-size: 1.4rem; font-weight: 800; margin-bottom: 8px;">Please Sign In to Access Your Cart</h3>
        <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 2rem;">
          You need to be signed in to your BITEBUDDY account to view items, apply promo codes, and complete checkout.
        </p>
        <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
          <button class="nav-btn-primary" onclick="openAuthModal('login')" style="padding: 10px 24px; border-radius: var(--radius-full); font-weight: 700; cursor: pointer; border: none;">
            <i data-lucide="log-in"></i> Sign In to Continue
          </button>
          <a href="index.php" class="nav-link-item nav-btn-outline" style="padding: 10px 24px; border-radius: var(--radius-full); font-weight: 700; text-decoration: none;">
            <i data-lucide="utensils"></i> Browse Food Menu
          </a>
        </div>
      </div>
    `;
    initLucide();
    return;
  }

  const keys = Object.keys(state.cart);

  if (keys.length === 0) {
    container.innerHTML = `
      <div class="cart-empty-box">
        <div class="cart-empty-icon">
          <i data-lucide="shopping-bag" style="width: 36px; height: 36px;"></i>
        </div>
        <h3 style="font-size: 1.4rem; font-weight: 800; margin-bottom: 8px;">Your food basket is empty</h3>
        <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 2rem;">
          Looks like you haven't added anything yet. Explore our mouth-watering dishes crafted by top chefs in your city!
        </p>
        <a href="index.php" class="btn-checkout" style="display: inline-flex; justify-content: center; text-decoration: none; padding: 12px 32px;">
          <i data-lucide="utensils" style="width: 18px;"></i> Browse Restaurants & Menu
        </a>
      </div>
    `;
    initLucide();
    return;
  }

  let subtotal = 0;
  keys.forEach(k => {
    subtotal += state.cart[k].qty * parseFloat(state.cart[k].dish.price);
  });

  const delFee = subtotal >= 500 ? 0 : 30.00;
  const tax = subtotal * 0.05;
  let discount = 0;
  if (state.appliedCoupon === 'BITEBUDDY50' || state.appliedCoupon === 'COMATOZZE50' || state.appliedCoupon === 'ZOMATO50') {
    discount = Math.min(subtotal * 0.5, 150);
  } else if (state.appliedCoupon === 'WELCOME') {
    discount = Math.min(100, subtotal);
  }
  const grandTotal = Math.max(0, subtotal + delFee + tax - discount);

  container.innerHTML = `
    <div class="full-cart-layout">
      <!-- Left Column: Items, Address, Cooking Notes -->
      <div>
        <div class="cart-items-card">
          <div class="cart-card-title">
            <span><i data-lucide="shopping-bag" style="width: 18px; color: var(--primary);"></i> Order Items (${keys.length})</span>
            <button class="table-action-btn btn-sm" onclick="clearFullCart()">Clear Cart</button>
          </div>
          <div>
            ${keys.map(k => {
              const it = state.cart[k];
              const itemTotal = it.qty * parseFloat(it.dish.price);
              const defaultImg = it.dish.image_url || 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400';
              return `
                <div class="cart-item-row-full">
                  <img src="${defaultImg}" alt="${it.dish.name}" class="cart-item-thumb">
                  <div class="cart-item-details">
                    <div class="cart-item-title">
                      <span class="dish-type-badge ${it.dish.is_veg == 1 ? 'veg' : 'nonveg'}"></span>
                      ${it.dish.name}
                    </div>
                    <div class="cart-item-subtitle">${it.dish.restaurant_name || 'BITEBUDDY Partner Kitchen'}</div>
                    <div class="cart-item-price">₹${parseFloat(it.dish.price).toFixed(2)} each</div>
                  </div>
                  <div class="cart-item-actions">
                    <div class="qty-counter" style="margin-top: 0; padding: 4px 10px;">
                      <button class="qty-btn" onclick="updateCartQty('${k}', -1)">−</button>
                      <span style="font-weight: 700; min-width: 20px; text-align: center;">${it.qty}</span>
                      <button class="qty-btn" onclick="updateCartQty('${k}', 1)">+</button>
                    </div>
                    <div style="font-weight: 800; font-size: 1.05rem; min-width: 80px; text-align: right; color: var(--text);">
                      ₹${itemTotal.toFixed(2)}
                    </div>
                    <button class="cart-item-remove-btn" onclick="removeFromCart('${k}')" title="Remove item">
                      <i data-lucide="trash-2" style="width: 18px; height: 18px;"></i>
                    </button>
                  </div>
                </div>
              `;
            }).join('')}
          </div>
        </div>

        <!-- Delivery Address Selection -->
        <div class="cart-items-card">
          <div class="cart-card-title">
            <span><i data-lucide="map-pin" style="width: 18px; color: var(--primary);"></i> Delivery Address</span>
            <span class="badge-pill badge-delivered">Default Location</span>
          </div>
          <div style="background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 1rem;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
              <strong>Home (Flat 402, Royal Palms)</strong>
              <span class="badge-pill badge-placed">Verified</span>
            </div>
            <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5;">
              Link Road, Near City Mall, Andheri West, Mumbai, Maharashtra 400053
            </p>
          </div>
        </div>

        <!-- Cooking Instructions / Special Requests -->
        <div class="cart-items-card">
          <div class="cart-card-title">
            <span><i data-lucide="message-square" style="width: 18px; color: var(--primary);"></i> Cooking or Delivery Notes</span>
          </div>
          <textarea id="full-cart-notes" placeholder="e.g. Leave with security guard, please provide extra cutlery, less spicy..." 
                    style="width: 100%; min-height: 80px; padding: 10px 14px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--bg); color: var(--text); font-family: inherit; font-size: 0.88rem; outline: none; resize: vertical;">Ring the doorbell and contactless drop.</textarea>
        </div>
      </div>

      <!-- Right Column: Bill Details, Coupons, Payment & Order Button -->
      <div>
        <div class="cart-summary-card">
          <div class="cart-card-title">
            <span><i data-lucide="receipt" style="width: 18px; color: var(--primary);"></i> Bill Summary</span>
          </div>

          <!-- Promo Code Box -->
          <div style="display: flex; gap: 8px; margin-bottom: 1.25rem;">
            <input type="text" id="full-coupon-input" value="${state.appliedCoupon || ''}" placeholder="Coupon (e.g. BITEBUDDY50)"
                   style="flex: 1; padding: 9px 12px; border: 1px dashed var(--primary); border-radius: 8px; font-size: 0.85rem; background: var(--bg); color: var(--text); outline: none;">
            <button class="filter-chip active" onclick="applyFullCartCoupon()" style="padding: 8px 16px; border-radius: 8px;">Apply</button>
          </div>

          ${discount > 0 ? `
            <div style="background: rgba(36, 150, 63, 0.1); border: 1px solid rgba(36, 150, 63, 0.2); color: var(--veg-green); padding: 8px 12px; border-radius: 8px; font-size: 0.82rem; font-weight: 600; margin-bottom: 1rem; display: flex; align-items: center; gap: 6px;">
              <i data-lucide="check" style="width: 14px;"></i> Coupon "${state.appliedCoupon}" applied! You saved ₹${discount.toFixed(2)}
            </div>
          ` : ''}

          <!-- Bill Rows -->
          <div class="cart-bill-summary" style="padding: 0; border: none; background: transparent;">
            <div class="bill-row">
              <span>Item Total</span>
              <span>₹${subtotal.toFixed(2)}</span>
            </div>
            <div class="bill-row">
              <span>Delivery Fee</span>
              <span>${delFee === 0 ? '<strong style="color:var(--veg-green);">FREE</strong>' : `₹${delFee.toFixed(2)}`}</span>
            </div>
            <div class="bill-row">
              <span>Taxes & Packaging (5%)</span>
              <span>₹${tax.toFixed(2)}</span>
            </div>
            ${discount > 0 ? `
              <div class="bill-row" style="color: var(--veg-green); font-weight: 700;">
                <span>Coupon Discount</span>
                <span>-₹${discount.toFixed(2)}</span>
              </div>
            ` : ''}
            <div class="bill-row total" style="margin-top: 1rem; padding-top: 1rem; border-top: 2px dashed var(--border);">
              <span style="font-size: 1.15rem; font-weight: 800;">To Pay</span>
              <span style="font-size: 1.25rem; font-weight: 800; color: var(--primary);">₹${grandTotal.toFixed(2)}</span>
            </div>
          </div>

          <!-- Payment Options Selection -->
          <div style="margin-top: 1.5rem;">
            <label style="font-size: 0.82rem; font-weight: 700; color: var(--text); display: block; margin-bottom: 8px;">
              Choose Payment Method:
            </label>
            <div class="payment-options-grid" style="grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 1.25rem;">
              <div class="payment-opt-card selected" onclick="selectPaymentMethod(this, 'UPI')">
                <i data-lucide="smartphone" style="color: var(--primary);"></i>
                <div style="font-size: 0.8rem; font-weight: 600;">UPI / GPay</div>
              </div>
              <div class="payment-opt-card" onclick="selectPaymentMethod(this, 'Card')">
                <i data-lucide="credit-card" style="color: var(--info-blue);"></i>
                <div style="font-size: 0.8rem; font-weight: 600;">Card</div>
              </div>
              <div class="payment-opt-card" onclick="selectPaymentMethod(this, 'NetBanking')">
                <i data-lucide="building" style="color: var(--accent-purple);"></i>
                <div style="font-size: 0.8rem; font-weight: 600;">Net Banking</div>
              </div>
              <div class="payment-opt-card" onclick="selectPaymentMethod(this, 'COD')">
                <i data-lucide="banknote" style="color: var(--veg-green);"></i>
                <div style="font-size: 0.8rem; font-weight: 600;">Cash on Del.</div>
              </div>
            </div>
          </div>

          <!-- Place Order Button -->
          <button id="full-cart-pay-btn" class="btn-checkout" onclick="processFullCartPayment(${grandTotal})" style="width: 100%; justify-content: center; padding: 14px; font-size: 1rem;">
            <i data-lucide="check-circle" style="width: 18px;"></i> Place Order • ₹${grandTotal.toFixed(2)}
          </button>

          <p style="font-size: 0.74rem; color: var(--text-muted); text-align: center; margin-top: 10px;">
            🔒 Safe & 256-Bit SSL Encrypted Payment Gateway
          </p>
        </div>
      </div>
    </div>
  `;
  initLucide();
}

function applyFullCartCoupon() {
  const input = document.getElementById('full-coupon-input');
  const code = (input.value || '').trim().toUpperCase();
  if (code === 'BITEBUDDY50' || code === 'COMATOZZE50' || code === 'ZOMATO50' || code === 'WELCOME') {
    state.appliedCoupon = code;
    alert(`🎉 Coupon "${code}" applied successfully!`);
    renderFullCartPage();
  } else {
    alert('Invalid coupon code. Try "BITEBUDDY50" or "WELCOME"');
  }
}

async function processFullCartPayment(grandTotal) {
  const payBtn = document.getElementById('full-cart-pay-btn');
  if (payBtn) {
    payBtn.disabled = true;
    payBtn.innerHTML = `Contacting Payment Gateway...`;
  }

  const selectedOpt = document.querySelector('.payment-opt-card.selected');
  const paymentMethod = selectedOpt ? selectedOpt.innerText.trim() : 'UPI';
  const notes = document.getElementById('full-cart-notes') ? document.getElementById('full-cart-notes').value : 'Contactless drop';

  const items = Object.keys(state.cart).map(k => ({
    menu_id: k,
    quantity: state.cart[k].qty,
    price: state.cart[k].dish.price
  }));

  const restaurantId = Object.values(state.cart)[0] ? Object.values(state.cart)[0].dish.restaurant_id || 1 : 1;

  const payload = {
    user_id: 1,
    restaurant_id: restaurantId,
    address_id: state.selectedAddressId || 1,
    tot_amount: grandTotal,
    delivery_fee: grandTotal >= 500 ? 0 : 30.00,
    payment_method: paymentMethod,
    order_notes: notes,
    items: items
  };

  try {
    const res = await fetch(`${API_BASE}?action=place_order`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const result = await res.json();

    setTimeout(() => {
      const orderId = result.data ? result.data.order_id : Math.floor(1000 + Math.random() * 9000);
      state.cart = {};
      localStorage.removeItem('fms_cart');
      updateCartUI();
      renderFullCartPage();
      launchLiveOrderTracking(orderId);
    }, 1200);

  } catch(err) {
    setTimeout(() => {
      const orderId = Math.floor(1000 + Math.random() * 9000);
      state.cart = {};
      localStorage.removeItem('fms_cart');
      updateCartUI();
      renderFullCartPage();
      launchLiveOrderTracking(orderId);
    }, 1000);
  }
}

// =====================================================================
// LIVE ORDER TRACKING
// =====================================================================
function launchLiveOrderTracking(orderId) {
  state.activeOrder = {
    order_id: orderId,
    status: 'placed',
    rider_name: 'Amit Verma',
    rider_phone: '+91 9855566677',
    vehicle_number: 'MH-02-BZ-4321',
    est_time: '28 mins'
  };

  const modal = document.getElementById('tracking-modal');
  const orderIdSpan = document.getElementById('track-order-id');
  if (orderIdSpan) orderIdSpan.innerText = `#FMS-${orderId}`;
  if (modal) modal.classList.add('active');

  simulateOrderProgression();
}

function simulateOrderProgression() {
  const steps = [
    { status: 'placed', label: 'Order Confirmed', sub: 'Restaurant accepted your order ticket.', delay: 2500 },
    { status: 'cooking', label: 'Chef Vikram Preparing Food', sub: 'Chef is cooking your fresh dishes in kitchen.', delay: 3500 },
    { status: 'out_for_delivery', label: 'Rider Amit Verma on the Way', sub: 'Picked up on Bike (MH-02-BZ-4321).', delay: 4500 },
    { status: 'delivered', label: 'Delivered at Doorstep', sub: 'Enjoy your hot meal! Please rate your experience.', delay: 0 }
  ];

  let currentIdx = 0;

  function runNextStep() {
    if (currentIdx >= steps.length) return;
    const step = steps[currentIdx];
    updateTrackingUI(step.status, currentIdx);

    if (step.delay > 0) {
      setTimeout(() => {
        currentIdx++;
        runNextStep();
      }, step.delay);
    }
  }

  runNextStep();
}

function updateTrackingUI(status, idx) {
  const stepsElems = document.querySelectorAll('.tracking-step');
  stepsElems.forEach((el, i) => {
    el.classList.remove('active', 'done');
    if (i < idx) {
      el.classList.add('done');
    } else if (i === idx) {
      el.classList.add('active');
    }
  });

  const mapProgress = document.getElementById('map-route-progress');
  const bikeMarker = document.getElementById('map-bike');
  if (mapProgress && bikeMarker) {
    const percentages = [20, 45, 80, 100];
    const pct = percentages[idx];
    mapProgress.style.width = `${pct}%`;
    bikeMarker.style.left = `${pct}%`;
  }

  if (status === 'delivered') {
    const reviewBtn = document.getElementById('order-review-box');
    if (reviewBtn) reviewBtn.style.display = 'block';
  }
}

function closeTrackingModal() {
  const modal = document.getElementById('tracking-modal');
  if (modal) modal.classList.remove('active');
}

function submitOrderRating(rating) {
  alert(`Thank you for your ${rating}-Star rating! Feedback submitted to restaurant.`);
  closeTrackingModal();
}

function setupEventListeners() {
  const searchInput = document.getElementById('search-input');
  if (searchInput) {
    searchInput.addEventListener('input', (e) => handleSearch(e.target.value));
  }
}

// =====================================================================
// AUTHENTICATION MODAL & STATE (LOGIN / REGISTER / PROFILE)
// =====================================================================
function checkAuthState() {
  const savedUser = localStorage.getItem('fms_user');
  const guestBox = document.getElementById('auth-guest-links');
  const userBox = document.getElementById('auth-user-links');
  const authProtectedNav = document.getElementById('auth-protected-nav');
  const nameSpan = document.getElementById('user-display-name');
  const dropName = document.getElementById('dropdown-user-name');
  const dropEmail = document.getElementById('dropdown-user-email');

  if (savedUser) {
    try {
      const u = JSON.parse(savedUser);
      if (guestBox) guestBox.style.display = 'none';
      if (userBox) userBox.style.display = 'flex';
      if (authProtectedNav) authProtectedNav.style.display = 'flex';
      if (nameSpan) nameSpan.innerText = u.name || 'Rahul Sharma';
      if (dropName) dropName.innerText = u.name || 'Rahul Sharma';
      if (dropEmail) dropEmail.innerText = u.email || 'customer@bitebuddy.com';
    } catch (e) {
      if (guestBox) guestBox.style.display = 'flex';
      if (userBox) userBox.style.display = 'none';
      if (authProtectedNav) authProtectedNav.style.display = 'none';
    }
  } else {
    if (guestBox) guestBox.style.display = 'flex';
    if (userBox) userBox.style.display = 'none';
    if (authProtectedNav) authProtectedNav.style.display = 'none';
  }

  // Refresh cart or orders page views if on those pages
  if (document.getElementById('full-cart-content')) {
    renderFullCartPage();
  }
  if (typeof loadCustomerOrdersPage === 'function') {
    loadCustomerOrdersPage();
  }
  initLucide();
}

function toggleProfileDropdown(e) {
  if (e) {
    e.stopPropagation();
    e.preventDefault();
  }
  const menu = document.getElementById('profile-dropdown-menu');
  if (menu) {
    menu.classList.toggle('active');
  }
}

// Global click outside to dismiss profile menu
document.addEventListener('click', (e) => {
  const menu = document.getElementById('profile-dropdown-menu');
  const toggleBtn = document.getElementById('nav-profile-toggle');
  if (menu && menu.classList.contains('active')) {
    if (!menu.contains(e.target) && (!toggleBtn || !toggleBtn.contains(e.target))) {
      menu.classList.remove('active');
    }
  }
});

function openAuthModal(tab = 'login') {
  const modal = document.getElementById('auth-modal');
  if (modal) {
    switchAuthTab(tab);
    modal.classList.add('active');
  }
}

function closeAuthModal() {
  const modal = document.getElementById('auth-modal');
  if (modal) modal.classList.remove('active');
}

function switchAuthTab(tab) {
  const tabLogin = document.getElementById('tab-login');
  const tabReg = document.getElementById('tab-register');
  const formLogin = document.getElementById('login-form');
  const formReg = document.getElementById('register-form');
  const title = document.getElementById('auth-modal-title');

  if (tab === 'login') {
    if (tabLogin) tabLogin.classList.add('active');
    if (tabReg) tabReg.classList.remove('active');
    if (formLogin) formLogin.style.display = 'block';
    if (formReg) formReg.style.display = 'none';
    if (title) title.innerText = 'Welcome Back to BITEBUDDY';
  } else {
    if (tabLogin) tabLogin.classList.remove('active');
    if (tabReg) tabReg.classList.add('active');
    if (formLogin) formLogin.style.display = 'none';
    if (formReg) formReg.style.display = 'block';
    if (title) title.innerText = 'Create Your BITEBUDDY Account';
  }
  initLucide();
}

function handleLoginSubmit(e) {
  e.preventDefault();
  const email = document.getElementById('login-email').value.trim();
  const name = email.split('@')[0].replace(/[\._]/g, ' ');
  const capitalized = name.charAt(0).toUpperCase() + name.slice(1);
  const user = { name: capitalized || 'Rahul Sharma', email: email };
  localStorage.setItem('fms_user', JSON.stringify(user));
  closeAuthModal();
  checkAuthState();
  alert(`Welcome back, ${user.name}! You are now signed in.`);
}

function handleRegisterSubmit(e) {
  e.preventDefault();
  const name = document.getElementById('reg-name').value.trim();
  const email = document.getElementById('reg-email').value.trim();
  const user = { name: name || 'Customer', email: email };
  localStorage.setItem('fms_user', JSON.stringify(user));
  closeAuthModal();
  checkAuthState();
  alert(`Account successfully created! Welcome to BITEBUDDY, ${user.name}!`);
}

function handleLogout() {
  if (confirm('Are you sure you want to log out?')) {
    localStorage.removeItem('fms_user');
    const menu = document.getElementById('profile-dropdown-menu');
    if (menu) menu.classList.remove('active');
    checkAuthState();
    alert('You have logged out successfully.');
    if (window.location.pathname.includes('cart.php') || window.location.pathname.includes('orders.php')) {
      window.location.href = 'index.php';
    }
  }
}

