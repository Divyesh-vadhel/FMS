-- =====================================================================
-- BITEBUDDY Comprehensive Catalog Seed Data
-- Restaurants, Complete Menu Items by Category, and Delivery Fleet
-- Target Database: food_db
-- =====================================================================
USE `food_db`;

SET FOREIGN_KEY_CHECKS = 0;

-- Clean existing catalog tables (preserving admin and user accounts)
DELETE FROM `menu_items`;
DELETE FROM `restaurants`;
DELETE FROM `delivery_boys`;

-- -------------------------------------------------------------
-- 1. Partner Restaurants (10 Popular Restaurants)
-- -------------------------------------------------------------
INSERT INTO `restaurants` (`restaurant_id`, `auth_id`, `name`, `email`, `phone`, `address`, `city`, `cuisine_type`, `rating`, `is_active`) VALUES
(1, NULL, 'Royal Dum Biryani House', 'info@royalbiryani.in', '+91 9877711223', 'Shop 4, Heritage Arcade, Central Avenue', 'Central', 'Biryani, Mughlai, Kebabs', 4.8, 1),
(2, NULL, 'Pizza Gusto & Italian Cafe', 'info@pizzagusto.com', '+91 9844455566', 'Plot 45, Business Hub Boulevard', 'Downtown', 'Italian, Pizza, Pasta', 4.6, 1),
(3, NULL, 'Tandoor & Curry Express', 'contact@tandoorexpress.com', '+91 9833344455', 'Shop 12, Food Galleria, Main Road', 'Central', 'North Indian, Mughlai, Curry', 4.7, 1),
(4, NULL, 'The Burger Club & Cafe', 'contact@burgerclub.in', '+91 9855533445', 'Lane 5, Park Street', 'Uptown', 'Burgers, American, Fast Food', 4.5, 1),
(5, NULL, 'Wok & Roll Asian Kitchen', 'orders@wokroll.com', '+91 9866622334', 'Level 2, Grand Mall Galleria', 'Downtown', 'Chinese, Dimsums, Asian, Noodles', 4.6, 1),
(6, NULL, 'South Feast Dosa Corner', 'hello@southfeast.com', '+91 9844411998', 'Near Station Central Plaza', 'Midtown', 'South Indian, Dosas, Filter Coffee', 4.8, 1),
(7, NULL, 'The Rollery & Shawarma House', 'orders@therollery.in', '+91 9866644332', 'Shop 18, Sunset Boulevard', 'Central', 'Rolls & Wraps, Shawarma, Fast Food', 4.5, 1),
(8, NULL, 'Sweet Treats Bakery & Desserts', 'treats@sweettreats.com', '+91 9877788990', 'Galleria Mall, 1st Floor', 'Uptown', 'Desserts, Cakes, Ice Cream', 4.9, 1),
(9, NULL, 'Green Bowl & Healthy Kitchen', 'hello@greenbowl.in', '+91 9822233445', 'Eco Park Complex, Suite 4', 'Midtown', 'Healthy Bowls, Salads, Juices', 4.6, 1),
(10, NULL, 'Chaat Gali & Street Bites', 'orders@chaatgali.in', '+91 9811122334', 'Corner Junction, Market Square', 'Central', 'Street Food, Chaat, Pav Bhaji', 4.7, 1);

-- -------------------------------------------------------------
-- 2. Menu Items (Over 35 Signature Dishes Across All Categories)
-- -------------------------------------------------------------
INSERT INTO `menu_items` (`menu_id`, `restaurant_id`, `name`, `description`, `price`, `rating`, `category`, `is_veg`, `is_available`, `image_url`) VALUES
-- Restaurant 1: Biryani House
(1, 1, 'Special Chicken Dum Biryani', 'Fragrant basmati rice layered with spiced marinated chicken, saffron, and fried onions.', 360.00, 4.9, 'Biryani', 0, 1, 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=500&auto=format&fit=crop&q=80'),
(2, 1, 'Hyderabadi Veg Dum Biryani', 'Rich vegetable medley layered with aromatic basmati rice, mint, and fried cashews.', 280.00, 4.7, 'Biryani', 1, 1, 'https://images.unsplash.com/photo-1642821373181-696a54913e9a?w=500&auto=format&fit=crop&q=80'),
(3, 1, 'Melt-in-Mouth Galouti Kebab', 'Smoked minced kebabs spiced with aromatic potli masala, served with mint chutney.', 420.00, 4.8, 'Starters', 0, 1, 'https://images.unsplash.com/photo-1599488615731-7e5c2823ff28?w=500&auto=format&fit=crop&q=80'),
(4, 1, 'Paneer Tikka Dum Biryani', 'Smoky clay-oven paneer cubes layered with saffron long-grain basmati rice.', 310.00, 4.6, 'Biryani', 1, 1, 'https://images.unsplash.com/photo-1589302168068-964664d93dc0?w=500&auto=format&fit=crop&q=80'),

-- Restaurant 2: Pizza Gusto
(5, 2, 'Margherita Pizza (12 inch)', 'Classic wood-fired sourdough crust with San Marzano tomato sauce, fresh basil, and mozzarella.', 420.00, 4.8, 'Pizza', 1, 1, 'https://images.unsplash.com/photo-1604382355076-af4b0eb60143?w=500&auto=format&fit=crop&q=80'),
(6, 2, 'Classic Farmhouse Veggie Pizza', 'Loaded with crunchy bell peppers, button mushrooms, sweet corn, and mozzarella cheese.', 480.00, 4.6, 'Pizza', 1, 1, 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=500&auto=format&fit=crop&q=80'),
(7, 2, 'Spicy Pepperoni & Cheese Pizza', 'Crispy spicy pepperoni slices on rich marinara base with melted mozzarella blend.', 560.00, 4.9, 'Pizza', 0, 1, 'https://images.unsplash.com/photo-1534308983496-4fabb1a015ee?w=500&auto=format&fit=crop&q=80'),
(8, 2, 'Creamy Fettuccine Alfredo Pasta', 'Silky ribbon pasta tossed in rich parmesan garlic cream sauce and herbs.', 340.00, 4.7, 'Pasta', 1, 1, 'https://images.unsplash.com/photo-1621996346565-e3d5d6281699?w=500&auto=format&fit=crop&q=80'),
(9, 2, 'Cheesy Stuffed Garlic Bread', 'Fresh baked garlic bread loaf bursting with melted cheese and Italian herbs.', 180.00, 4.5, 'Starters', 1, 1, 'https://images.unsplash.com/photo-1573140247632-f8fd74997d5c?w=500&auto=format&fit=crop&q=80'),

-- Restaurant 3: Tandoor & Curry Express
(10, 3, 'Paneer Butter Masala', 'Tender cottage cheese simmered in velvety tomato cashew gravy with aromatic butter glaze.', 320.00, 4.8, 'North Indian', 1, 1, 'https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=500&auto=format&fit=crop&q=80'),
(11, 3, 'Butter Chicken Royale', 'Boneless tender tandoori chicken cooked in creamy tomato butter gravy with fenugreek.', 390.00, 4.9, 'North Indian', 0, 1, 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=500&auto=format&fit=crop&q=80'),
(12, 3, 'Dal Makhani (Slow Cooked)', 'Black lentils and kidney beans simmered overnight with butter, cream, and subtle spices.', 260.00, 4.8, 'North Indian', 1, 1, 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=500&auto=format&fit=crop&q=80'),
(13, 3, 'Butter Garlic Naan', 'Freshly baked clay-oven flatbread brushed with garlic butter and cilantro.', 65.00, 4.7, 'Breads', 1, 1, 'https://images.unsplash.com/photo-1626074353765-517a681e40be?w=500&auto=format&fit=crop&q=80'),
(14, 3, 'Tandoori Chicken Half', 'Classic bone-in chicken marinated in spiced yogurt and grilled crisp over charcoal.', 310.00, 4.8, 'Starters', 0, 1, 'https://images.unsplash.com/photo-1610057099443-fde8c4d50f91?w=500&auto=format&fit=crop&q=80'),

-- Restaurant 4: The Burger Club
(15, 4, 'Double Cheese Smash Burger', 'Crispy grilled juicy patty with double melted cheddar, caramelized onions, and secret sauce.', 299.00, 4.8, 'Burgers', 0, 1, 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=500&auto=format&fit=crop&q=80'),
(16, 4, 'Crispy Paneer Tower Burger', 'Spicy crunchy paneer patty topped with fresh lettuce, jalapenos, and chipotle mayo.', 249.00, 4.6, 'Burgers', 1, 1, 'https://images.unsplash.com/photo-1550547660-d9450f859349?w=500&auto=format&fit=crop&q=80'),
(17, 4, 'Crispy Peri Peri Crinkle Fries', 'Golden crinkle fries tossed in spicy bird eye peri-peri dust.', 140.00, 4.5, 'Fast Food', 1, 1, 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=500&auto=format&fit=crop&q=80'),
(18, 4, 'Belgian Chocolate Thickshake', 'Thick decadent milkshake made with pure dark chocolate ganache and ice cream.', 190.00, 4.8, 'Beverages', 1, 1, 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=500&auto=format&fit=crop&q=80'),

-- Restaurant 5: Wok & Roll Asian Kitchen
(19, 5, 'Steamed Chicken Dimsums (6 pcs)', 'Delicate dumplings filled with finely seasoned minced chicken, served with spicy dip.', 240.00, 4.8, 'Asian', 0, 1, 'https://images.unsplash.com/photo-1496116218417-1a781b1c416c?w=500&auto=format&fit=crop&q=80'),
(20, 5, 'Schezwan Veg Hakka Noodles', 'Wok-tossed noodles with bell peppers, cabbage, spring onion, and fiery red schezwan sauce.', 210.00, 4.6, 'Chinese', 1, 1, 'https://images.unsplash.com/photo-1585032226651-759b368d7246?w=500&auto=format&fit=crop&q=80'),
(21, 5, 'Crispy Chilli Paneer Dry', 'Crispy cottage cheese cubes tossed in wok with garlic, green chillies, and dark soy glaze.', 260.00, 4.7, 'Asian', 1, 1, 'https://images.unsplash.com/photo-1567188040759-fb8a883dc6d8?w=500&auto=format&fit=crop&q=80'),
(22, 5, 'Wok Egg Fried Rice', 'Aromatic jasmine rice stir-fried with farm fresh eggs, scallions, and light seasoning.', 230.00, 4.6, 'Chinese', 0, 1, 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?w=500&auto=format&fit=crop&q=80'),

-- Restaurant 6: South Feast Dosa Corner
(23, 6, 'Mysore Masala Butter Dosa', 'Golden crispy crepe coated with spicy red chutney and spiced potato mash, served with sambar.', 160.00, 4.9, 'South Indian', 1, 1, 'https://images.unsplash.com/photo-1668236543090-82eba5ee5976?w=500&auto=format&fit=crop&q=80'),
(24, 6, 'Steamed Button Idli (with Sambar)', 'Pillow soft steamed rice cakes served with hot lentil vegetable sambar and fresh coconut chutney.', 110.00, 4.7, 'South Indian', 1, 1, 'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=500&auto=format&fit=crop&q=80'),
(25, 6, 'Crispy Medu Vada (2 Pcs)', 'Crisp golden fried lentil donuts seasoned with peppercorns, curry leaves, and ginger.', 120.00, 4.6, 'South Indian', 1, 1, 'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=500&auto=format&fit=crop&q=80'),
(26, 6, 'Traditional Filter Coffee', 'Authentic frothy South Indian filter coffee brewed with fresh roasted chicory beans.', 70.00, 4.9, 'Beverages', 1, 1, 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=500&auto=format&fit=crop&q=80'),

-- Restaurant 7: The Rollery & Shawarma House
(27, 7, 'Kolkata Chicken Kathi Roll', 'Flaky paratha wrapped around tender spiced chicken, crunchy onions, and zesty lime.', 180.00, 4.7, 'Rolls', 0, 1, 'https://images.unsplash.com/photo-1626700051175-6818013e1d4f?w=500&auto=format&fit=crop&q=80'),
(28, 7, 'Paneer Tikka Mayo Roll', 'Smoky paneer cubes tossed in tandoori spices and wrapped in warm toasted flatbread.', 160.00, 4.6, 'Rolls', 1, 1, 'https://images.unsplash.com/photo-1626700051175-6818013e1d4f?w=500&auto=format&fit=crop&q=80'),
(29, 7, 'Grilled Chicken Shawarma Wrap', 'Slow-roasted shredded chicken stuffed with pickled veggies and garlic toum sauce.', 190.00, 4.8, 'Rolls', 0, 1, 'https://images.unsplash.com/photo-1529006557810-274b9b2fc783?w=500&auto=format&fit=crop&q=80'),

-- Restaurant 8: Sweet Treats & Bakery
(30, 8, 'New York Baked Cheesecake', 'Velvety cream cheese slice over crunchy graham crust, topped with berry compote.', 220.00, 4.9, 'Desserts', 1, 1, 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=500&auto=format&fit=crop&q=80'),
(31, 8, 'Warm Chocolate Molten Lava Cake', 'Gooey decadent chocolate cake with a melting warm chocolate core.', 160.00, 4.8, 'Desserts', 1, 1, 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=500&auto=format&fit=crop&q=80'),
(32, 8, 'Belgian Waffle with Ice Cream', 'Crispy hot waffle drizzled with melted hazelnut Nutella and creamy vanilla ice cream.', 240.00, 4.9, 'Ice Cream', 1, 1, 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=500&auto=format&fit=crop&q=80'),
(33, 8, 'Warm Gulab Jamun (2 Pcs)', 'Rich milk-solid dumplings soaked in saffron and green cardamom sugar syrup.', 90.00, 4.8, 'Desserts', 1, 1, 'https://images.unsplash.com/photo-1541832676-9b763b0239ab?w=500&auto=format&fit=crop&q=80'),

-- Restaurant 9: Green Bowl
(34, 9, 'Quinoa Avocado Power Salad', 'Organic mixed greens, tri-color quinoa, ripe avocado, cherry tomatoes, and citrus dressing.', 290.00, 4.7, 'Healthy', 1, 1, 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=500&auto=format&fit=crop&q=80'),
(35, 9, 'Teriyaki Tofu Brown Rice Bowl', 'Pan-seared tofu glazed with homemade teriyaki, served over brown rice and steamed edamame.', 320.00, 4.6, 'Healthy', 1, 1, 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&auto=format&fit=crop&q=80'),
(36, 9, 'Cold-Pressed Orange Juice', '100% pure fresh Valencia orange juice with no added sugar or preservatives.', 150.00, 4.8, 'Beverages', 1, 1, 'https://images.unsplash.com/photo-1613478223719-2ab802602423?w=500&auto=format&fit=crop&q=80'),

-- Restaurant 10: Chaat Gali
(37, 10, 'Crispy Puri Pani Puri (8 Pcs)', 'Crispy semolina hollow puris filled with spiced potato-chickpeas and chilled mint-tamarind waters.', 90.00, 4.8, 'Street Food', 1, 1, 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=500&auto=format&fit=crop&q=80'),
(38, 10, 'Dahi Papdi Chaat Royale', 'Crisp wheat wafers loaded with sweet curd, spicy green chutney, tangy saunth, and crunchy sev.', 120.00, 4.7, 'Street Food', 1, 1, 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=500&auto=format&fit=crop&q=80'),
(39, 10, 'Special Amul Butter Pav Bhaji', 'Thick spicy vegetable mash topped with melting butter, served with two soft butter-toasted pavs.', 180.00, 4.9, 'Street Food', 1, 1, 'https://images.unsplash.com/photo-1606491956689-2ea866880c84?w=500&auto=format&fit=crop&q=80');

-- -------------------------------------------------------------
-- 3. Delivery Fleet
-- -------------------------------------------------------------
INSERT INTO `delivery_boys` (`boy_id`, `auth_id`, `name`, `email`, `phone`, `vehicle_type`, `vehicle_number`, `status`) VALUES
(1, NULL, 'Amit Verma', 'amit.rider@bitebuddy.com', '+91 9855566677', 'Bike', 'KA-01-BZ-4321', 'available'),
(2, NULL, 'Rohit Jadhav', 'rohit.rider@bitebuddy.com', '+91 9844433221', 'EV', 'KA-01-EV-9081', 'available'),
(3, NULL, 'Sunil Kamble', 'sunil.rider@bitebuddy.com', '+91 9833322110', 'Bike', 'KA-01-AK-1122', 'available'),
(4, NULL, 'Deepak Pawar', 'deepak.rider@bitebuddy.com', '+91 9822211009', 'Scooter', 'KA-01-DL-8877', 'available');

-- -------------------------------------------------------------
-- 4. Default Customer & Delivery Addresses
-- -------------------------------------------------------------
INSERT INTO `authentication` (`auth_id`, `username`, `email`, `password`, `role`, `status`)
VALUES (2, 'customer', 'customer@bitebuddy.com', '$2y$10$5kDVTemPfBDJa.7oferZKeA2UBm/IY.xaYolYD9eSj9o9b7vvweDq', 'customer', 'active')
ON DUPLICATE KEY UPDATE `username`='customer';

INSERT INTO `customers` (`user_id`, `auth_id`, `name`, `email`, `phone`)
VALUES (1, 2, 'Default Customer', 'customer@bitebuddy.com', '+91 9876543210')
ON DUPLICATE KEY UPDATE `name`='Default Customer';

INSERT INTO `addresses` (`address_id`, `user_id`, `address_type`, `street_address`, `landmark`, `city`, `state`, `country`, `pincode`, `is_default`)
VALUES 
(1, 1, 'Home', 'Flat 402, Royal Palms, Link Road', 'Near City Center Mall', 'City Central', 'State', 'India', '400001', 1),
(2, 1, 'Work', 'Tower B, 7th Floor, Tech Park', 'Opposite Metro Station', 'City Central', 'State', 'India', '400002', 0)
ON DUPLICATE KEY UPDATE `street_address`=VALUES(`street_address`);

SET FOREIGN_KEY_CHECKS = 1;
