# 🍔 BITEBUDDY Food Management System (FMS)

A modular, enterprise-grade **BITEBUDDY Food Management System** web application with complete architectural separation between **User / Customer Side** and **Admin Side**, running on local XAMPP (`PHP` + `MySQL`).

---

## 🌟 Quick Access URLs

| Portal | URL | Description |
| :--- | :--- | :--- |
| 🌐 **Master Gateway** | **[http://localhost/FMS/](http://localhost/FMS/)** | Unified platform hub with links to all portals |
| 🛒 **User Side** | **[http://localhost/FMS/user/](http://localhost/FMS/user/)** | Customer storefront, food ordering, cart, checkout & live tracker |
| 🛡️ **Admin Side** | **[http://localhost/FMS/admin/](http://localhost/FMS/admin/)** | Platform KPIs, orders management, menu catalogs & fleet monitoring |
| 👨‍🍳 **Kitchen Display** | **[http://localhost/FMS/kitchen/](http://localhost/FMS/kitchen/)** | Kitchen Order Display (KDS) for culinary preparation |
| 🛵 **Rider Dispatch** | **[http://localhost/FMS/rider/](http://localhost/FMS/rider/)** | Delivery partner dispatch & earnings tracker |

---

## 📁 Clean, Best-Practice Project Structure

```
c:\xampp\htdocs\FMS\
│
├── index.php                      # Master Gateway / Navigation Hub
├── api.php                        # Root API backward-compatibility gateway
│
├── user/                          # 🛒 DEDICATED USER / CUSTOMER SIDE
│   ├── index.php                  # Customer storefront (dishes, search, filters, cart, checkout)
│   └── orders.php                 # Customer order history & live order tracking
│
├── admin/                         # 🛡️ DEDICATED ADMIN SIDE
│   ├── index.php                  # Admin Dashboard (Platform KPIs, GMV, health monitoring)
│   ├── orders.php                 # Order tickets manager & real-time status updater
│   ├── restaurants.php            # Restaurant outlet manager & dish stock toggle
│   └── riders.php                 # Delivery fleet & logistics manager
│
├── kitchen/                       # 👨‍🍳 KITCHEN / CHEF PORTAL
│   └── index.php                  # Kitchen Order Display (KDS) for Chef Vikram
│
├── rider/                         # 🛵 DELIVERY PARTNER PORTAL
│   └── index.php                  # Rider dispatch screen for Amit Verma
│
├── api/                           # 🔌 BACKEND REST API
│   └── index.php                  # Centralized REST API endpoints
│
├── config/                        # ⚙️ CONFIGURATION
│   └── db.php                     # Centralized MySQL database connection ($conn)
│
├── assets/                        # 🎨 SHARED STATIC ASSETS
│   ├── css/
│   │   ├── styles.css             # Main design system (Zomato UI, dark/light theme, modals)
│   │   └── admin.css              # Admin-specific styling (tables, KPI cards, badges)
│   └── js/
│       ├── user.js                # Customer storefront controller (cart, payment, tracker)
│       └── admin.js               # Admin & operational controller (KPIs, status updater)
│
├── database/                      # 🗄️ DATABASE ARCHITECTURE
│   └── database.sql               # Unified complete schema (14 tables + all seed data)
│
└── README.md                      # Project documentation & usage instructions
```

---

## 🚀 Key Features by Portal

### 🛒 User Side (`/user/`)
- **Restaurant Catalog**: 6 top-rated outlets (Tandoor & Curry Express, Pizza Gusto, Royal Dum Biryani, Wok & Roll Asian, Burger Club, South Feast).
- **Live Search & Category Filtering**: Quick filtering by cuisine (Biryani, Pizza, North Indian, Burgers, Asian, South Indian, Desserts) and veg/non-veg flags.
- **Interactive Menu Modal**: View items with high-resolution photography, descriptions, and one-click cart additions.
- **Slide-out Cart Drawer**: Dynamic bill calculation (items total, 5% tax, delivery fee, coupons `BITEBUDDY50` and `WELCOME`).
- **Payment & Checkout**: UPI, Credit/Debit Card, Net Banking, and COD.
- **Live Animated Order Tracker**: Visual animated route map with bike marker progression through order stages:
  1. *Order Placed & Confirmed*
  2. *Chef Cooking in Kitchen*
  3. *Rider on the Way*
  4. *Delivered at Doorstep*
- **Post-Delivery Review**: 5-star customer rating system.

### 🛡️ Admin Side (`/admin/`)
- **Dashboard Overview**: Real-time KPI summary (Total Orders, Total Platform GMV, Active Restaurants, Fleet Riders).
- **Orders Management**: Live order tickets with one-click status transitions (`Placed` ➔ `Cooking` ➔ `Out for Delivery` ➔ `Delivered` ➔ `Cancelled`).
- **Restaurants & Menus**: View all outlets, inspect menu items, and toggle dish availability (`In Stock` / `Out of Stock`).
- **Delivery Fleet**: Monitor drivers (Amit, Rohit, Sunil, Deepak), vehicle types, and status.

### 👨‍🍳 Kitchen Side (`/kitchen/`)
- Kitchen Order Display (KDS) showing active tickets for Chef Vikram.
- Line items, customer special notes, and action buttons ("Start Cooking", "Ready for Pickup").

### 🛵 Rider Side (`/rider/`)
- Logistics dispatch showing pickup from restaurant and drop-off to customer.
- Daily earnings tracker and "Mark as Delivered" action button.

---

## 🗄️ Database Setup (`food_db`)

The database script has been merged into a single file: **[`database/database.sql`](file:///c:/xampp/htdocs/FMS/database/database.sql)**.

To import in XAMPP:
1. Open **[http://localhost/phpmyadmin/](http://localhost/phpmyadmin/)** in your browser.
2. Click **Import**.
3. Choose file: `C:\xampp\htdocs\FMS\database\database.sql`.
4. Click **Go**.
