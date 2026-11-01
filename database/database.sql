-- =====================================================================
-- Food Management System (FMS) - BITEBUDDY Platform Database Schema
-- Complete Relational Architecture (14 Tables + Seed Data)
-- Compatible with MySQL 5.7+ / MySQL 8.0+ / MariaDB (XAMPP Default)
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `food_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `food_db`;

-- Disable Foreign Key checks during recreation
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `payments`;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `cart_items`;
DROP TABLE IF EXISTS `carts`;
DROP TABLE IF EXISTS `menu_items`;
DROP TABLE IF EXISTS `chefs`;
DROP TABLE IF EXISTS `delivery_boys`;
DROP TABLE IF EXISTS `restaurants`;
DROP TABLE IF EXISTS `addresses`;
DROP TABLE IF EXISTS `customers`;
DROP TABLE IF EXISTS `admins`;
DROP TABLE IF EXISTS `authentication`;

-- =====================================================================
-- 1. CENTRAL AUTHENTICATION TABLE (Role-Based Access Control)
-- =====================================================================
CREATE TABLE `authentication` (
    `auth_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(60) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL COMMENT 'Bcrypt or Argon2 hash',
    `role` ENUM('admin', 'customer', 'restaurant_owner', 'chef', 'delivery_boy') NOT NULL DEFAULT 'customer',
    `status` ENUM('active', 'pending_verification', 'suspended') NOT NULL DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_auth_role` (`role`),
    INDEX `idx_auth_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================================
-- 2. ADMINS TABLE
-- =====================================================================
CREATE TABLE `admins` (
    `admin_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `auth_id` INT UNSIGNED NOT NULL UNIQUE,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `phone` VARCHAR(20) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_admin_auth` FOREIGN KEY (`auth_id`) REFERENCES `authentication` (`auth_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================================
-- 3. CUSTOMER (USERS) TABLE
-- =====================================================================
CREATE TABLE `customers` (
    `user_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `auth_id` INT UNSIGNED NOT NULL UNIQUE,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `phone` VARCHAR(20) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_customer_auth` FOREIGN KEY (`auth_id`) REFERENCES `authentication` (`auth_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================================
-- 4. ADDRESSES TABLE (1 Customer : N Addresses)
-- =====================================================================
CREATE TABLE `addresses` (
    `address_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `address_type` ENUM('Home', 'Work', 'Other') DEFAULT 'Home',
    `street_address` VARCHAR(255) NOT NULL,
    `landmark` VARCHAR(150) NULL,
    `city` VARCHAR(100) NOT NULL,
    `state` VARCHAR(100) NULL,
    `country` VARCHAR(100) NOT NULL DEFAULT 'India',
    `pincode` VARCHAR(15) NOT NULL,
    `latitude` DECIMAL(10, 8) NULL,
    `longitude` DECIMAL(11, 8) NULL,
    `is_default` BOOLEAN DEFAULT FALSE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_address_user` FOREIGN KEY (`user_id`) REFERENCES `customers` (`user_id`) ON DELETE CASCADE,
    INDEX `idx_address_city` (`city`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================================
-- 5. RESTAURANTS TABLE
-- =====================================================================
CREATE TABLE `restaurants` (
    `restaurant_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `auth_id` INT UNSIGNED NULL UNIQUE COMMENT 'Optional link to restaurant manager account',
    `name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `phone` VARCHAR(20) NOT NULL,
    `address` TEXT NOT NULL,
    `city` VARCHAR(100) NOT NULL,
    `cuisine_type` VARCHAR(100) DEFAULT 'Multi-Cuisine',
    `rating` DECIMAL(2, 1) DEFAULT 4.0,
    `is_active` BOOLEAN DEFAULT TRUE,
    `opening_time` TIME DEFAULT '09:00:00',
    `closing_time` TIME DEFAULT '23:00:00',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_restaurant_auth` FOREIGN KEY (`auth_id`) REFERENCES `authentication` (`auth_id`) ON DELETE SET NULL,
    INDEX `idx_restaurant_city` (`city`),
    INDEX `idx_restaurant_rating` (`rating`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================================
-- 6. MENUS / FOOD ITEMS TABLE (1 Restaurant : N Menu Items)
-- =====================================================================
CREATE TABLE `menu_items` (
    `menu_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `restaurant_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `description` TEXT NULL,
    `price` DECIMAL(10, 2) NOT NULL,
    `rating` DECIMAL(2, 1) DEFAULT 4.5,
    `category` VARCHAR(60) NOT NULL DEFAULT 'Main Course',
    `is_veg` BOOLEAN DEFAULT TRUE,
    `is_available` BOOLEAN DEFAULT TRUE,
    `image_url` VARCHAR(500) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_menu_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`restaurant_id`) ON DELETE CASCADE,
    INDEX `idx_menu_category` (`category`),
    INDEX `idx_menu_price` (`price`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================================
-- 7. CHEFS TABLE (Kitchen Operations)
-- =====================================================================
CREATE TABLE `chefs` (
    `chef_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `auth_id` INT UNSIGNED NOT NULL UNIQUE,
    `restaurant_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `specialization` VARCHAR(100) NULL DEFAULT 'Head Chef',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_chef_auth` FOREIGN KEY (`auth_id`) REFERENCES `authentication` (`auth_id`) ON DELETE CASCADE,
    CONSTRAINT `fk_chef_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`restaurant_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================================
-- 8. DELIVERY BOYS / PARTNERS TABLE
-- =====================================================================
CREATE TABLE `delivery_boys` (
    `boy_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `auth_id` INT UNSIGNED NULL UNIQUE,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `phone` VARCHAR(20) NOT NULL,
    `vehicle_type` ENUM('Bike', 'Scooter', 'Bicycle', 'EV') DEFAULT 'Bike',
    `vehicle_number` VARCHAR(30) NULL,
    `status` ENUM('available', 'on_delivery', 'offline') DEFAULT 'available',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_delivery_auth` FOREIGN KEY (`auth_id`) REFERENCES `authentication` (`auth_id`) ON DELETE SET NULL,
    INDEX `idx_boy_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================================
-- 9. CARTS & CART ITEMS TABLES (Persistent Cart)
-- =====================================================================
CREATE TABLE `carts` (
    `cart_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL UNIQUE,
    `restaurant_id` INT UNSIGNED NULL COMMENT 'Cart items usually belong to single restaurant',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `customers` (`user_id`) ON DELETE CASCADE,
    CONSTRAINT `fk_cart_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`restaurant_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `cart_items` (
    `cart_item_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `cart_id` INT UNSIGNED NOT NULL,
    `menu_id` INT UNSIGNED NOT NULL,
    `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_cartitem_cart` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`cart_id`) ON DELETE CASCADE,
    CONSTRAINT `fk_cartitem_menu` FOREIGN KEY (`menu_id`) REFERENCES `menu_items` (`menu_id`) ON DELETE CASCADE,
    UNIQUE KEY `uk_cart_menu_item` (`cart_id`, `menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================================
-- 10. ORDERS TABLE
-- =====================================================================
CREATE TABLE `orders` (
    `order_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `restaurant_id` INT UNSIGNED NOT NULL,
    `address_id` INT UNSIGNED NOT NULL,
    `boy_id` INT UNSIGNED NULL COMMENT 'Assigned delivery partner',
    `tot_amount` DECIMAL(10, 2) NOT NULL,
    `delivery_fee` DECIMAL(6, 2) DEFAULT 30.00,
    `tax_amount` DECIMAL(6, 2) DEFAULT 0.00,
    `discount_amount` DECIMAL(6, 2) DEFAULT 0.00,
    `status` ENUM('placed', 'confirmed', 'cooking', 'out_for_delivery', 'delivered', 'cancelled') NOT NULL DEFAULT 'placed',
    `order_notes` VARCHAR(255) NULL,
    `order_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `delivered_at` TIMESTAMP NULL,
    CONSTRAINT `fk_order_user` FOREIGN KEY (`user_id`) REFERENCES `customers` (`user_id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_order_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`restaurant_id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_order_address` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`address_id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_order_delivery_boy` FOREIGN KEY (`boy_id`) REFERENCES `delivery_boys` (`boy_id`) ON DELETE SET NULL,
    INDEX `idx_order_status` (`status`),
    INDEX `idx_order_date` (`order_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================================
-- 11. ORDER ITEMS (Associative Table for Multi-Item Orders)
-- =====================================================================
CREATE TABLE `order_items` (
    `order_item_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT UNSIGNED NOT NULL,
    `menu_id` INT UNSIGNED NOT NULL,
    `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
    `unit_price` DECIMAL(10, 2) NOT NULL COMMENT 'Historic snapshot price at purchase',
    `subtotal` DECIMAL(10, 2) NOT NULL,
    CONSTRAINT `fk_orderitems_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
    CONSTRAINT `fk_orderitems_menu` FOREIGN KEY (`menu_id`) REFERENCES `menu_items` (`menu_id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================================
-- 12. PAYMENTS TABLE (1 Order : 1 Payment)
-- =====================================================================
CREATE TABLE `payments` (
    `payment_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT UNSIGNED NOT NULL UNIQUE,
    `amount` DECIMAL(10, 2) NOT NULL,
    `transaction_id` VARCHAR(100) NOT NULL UNIQUE,
    `payment_method` ENUM('UPI', 'Credit/Debit Card', 'Net Banking', 'Cash on Delivery', 'Wallet') NOT NULL DEFAULT 'UPI',
    `status` ENUM('pending', 'completed', 'failed', 'refunded') NOT NULL DEFAULT 'completed',
    `payment_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_payment_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================================
-- 13. REVIEWS & RATINGS TABLE
-- =====================================================================
CREATE TABLE `reviews` (
    `review_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `restaurant_id` INT UNSIGNED NOT NULL,
    `order_id` INT UNSIGNED NULL,
    `rating` TINYINT UNSIGNED NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
    `comment` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_review_user` FOREIGN KEY (`user_id`) REFERENCES `customers` (`user_id`) ON DELETE CASCADE,
    CONSTRAINT `fk_review_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`restaurant_id`) ON DELETE CASCADE,
    CONSTRAINT `fk_review_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================================
-- MASTER ADMINISTRATOR CREDENTIALS
-- Name: admin | Username: admin | Password: 12345678
-- =====================================================================

-- 1. Master Authentication Record
INSERT INTO `authentication` (`auth_id`, `username`, `email`, `password`, `role`, `status`) VALUES
(1, 'admin', 'admin@bitebuddy.com', '$2y$10$5kDVTemPfBDJa.7oferZKeA2UBm/IY.xaYolYD9eSj9o9b7vvweDq', 'admin', 'active');

-- 2. Master Admin Profile
INSERT INTO `admins` (`admin_id`, `auth_id`, `name`, `email`, `phone`) VALUES
(1, 1, 'admin', 'admin@bitebuddy.com', '1234567890');


-- Re-enable Foreign Key checks
SET FOREIGN_KEY_CHECKS = 1;
