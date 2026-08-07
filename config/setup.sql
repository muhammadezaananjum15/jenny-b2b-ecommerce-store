-- ============================================================
-- Jenny's Cosmetics & Jewelry — Full Database Setup (v2.0)
-- Database: jennys_db
-- Admin: admin@jenny.com / 1683217
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------
-- Table: users (enhanced with profile fields)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','admin') DEFAULT 'customer',
  `is_admin` tinyint(1) DEFAULT 0,
  `full_name` varchar(150) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `status` enum('active','banned','inactive') DEFAULT 'active',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Safely add new columns if upgrading
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `address` text DEFAULT NULL;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `city` varchar(100) DEFAULT NULL;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `profile_image` varchar(255) DEFAULT NULL;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `bio` text DEFAULT NULL;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `status` enum('active','banned','inactive') DEFAULT 'active';
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `last_login` timestamp NULL DEFAULT NULL;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `full_name` varchar(150) DEFAULT NULL;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `phone` varchar(20) DEFAULT NULL;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `is_admin` tinyint(1) DEFAULT 0;

-- Default admin user (password: 1683217, bcrypt)
INSERT IGNORE INTO `users` (`username`, `email`, `password`, `role`, `is_admin`, `full_name`, `status`) VALUES
('admin', 'admin@jenny.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, 'Jenny Admin', 'active');

-- --------------------------------------------------------
-- Table: admin_settings
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL UNIQUE,
  `setting_value` text DEFAULT NULL,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `admin_settings` (`setting_key`, `setting_value`) VALUES
('site_name', "Jenny's Cosmetics & Jewelry"),
('site_tagline', 'Premium Beauty & Elegance'),
('site_email', 'admin@jenny.com'),
('site_phone', '+92 300 0000000'),
('site_address', 'Karachi, Pakistan'),
('facebook_url', '#'),
('instagram_url', '#'),
('whatsapp_number', '+923000000000'),
('currency_symbol', 'Rs.'),
('orders_email_notify', '1'),
('maintenance_mode', '0'),
('admin_notifications', '1');

-- --------------------------------------------------------
-- Table: site_settings (global site config)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key_name` varchar(100) NOT NULL UNIQUE,
  `value` text DEFAULT NULL,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: hero_slides (carousel management)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `hero_slides` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `badge_text` varchar(100) DEFAULT NULL,
  `subtitle` varchar(150) DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `title_highlight` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `btn1_text` varchar(80) DEFAULT 'Shop Now',
  `btn1_link` varchar(255) DEFAULT 'products.php',
  `btn2_text` varchar(80) DEFAULT 'Explore',
  `btn2_link` varchar(255) DEFAULT 'products.php',
  `image` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `hero_slides` (`id`, `badge_text`, `subtitle`, `title`, `title_highlight`, `description`, `btn1_text`, `btn1_link`, `btn2_text`, `btn2_link`, `image`, `sort_order`, `is_active`) VALUES
(1, '✨ Premium Collection', 'Elevate Your', 'Beauty &', 'Shine', 'Premium cosmetics & imitation jewelry that brings out the best in you. Quality crafted for the modern woman.', 'Shop Now', 'products.php', 'Explore', 'cosmetics.php', 'hero-ai-beauty.png', 1, 1),
(2, '💎 New Arrivals', 'Discover Our', 'Jewelry', 'Collection', 'Stunning imitation gold jewelry that looks and feels like the real thing. Crafted with love for every occasion.', 'View Jewelry', 'imitation-jewelry.php', 'New Arrivals', 'new-arrivals.php', 'hero-ai-jewelry.png', 2, 1),
(3, '💄 Beauty Secrets', 'Unleash Your', 'Inner', 'Glow', 'Professional-grade makeup that empowers your beauty. From bold lips to flawless skin — we have it all.', 'Shop Cosmetics', 'cosmetics.php', 'Best Sellers', 'best-sellers.php', 'hero-ai-cosmetics.png', 3, 1),
(4, '🌹 Skincare Luxe', 'Pamper Your', 'Skin with', 'Love', 'Premium skincare and beauty essentials to keep your skin glowing, radiant, and healthy every day.', 'Shop Now', 'products.php', 'See Offers', 'offers.php', 'hero-ai-skincare.png', 4, 1),
(5, '👑 Royal Selection', 'Wear the', 'Crown of', 'Elegance', 'Intricate rings, necklaces, and earrings made for queens. Complete any look with our royal jewelry range.', 'Shop Jewelry', 'imitation-jewelry.php', 'Special Offers', 'offers.php', 'hero-ai-royal.png', 5, 1);

-- --------------------------------------------------------
-- Table: products (enhanced)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `category` varchar(100) NOT NULL,
  `sub_category` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `old_price` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock` int(11) DEFAULT 100,
  `rating` decimal(2,1) DEFAULT 4.5,
  `review_count` int(11) DEFAULT 0,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_new` tinyint(1) DEFAULT 0,
  `is_bestseller` tinyint(1) DEFAULT 0,
  `discount_percent` int(11) DEFAULT 0,
  `tags` varchar(255) DEFAULT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `weight` decimal(8,2) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `sub_category` varchar(100) DEFAULT NULL;
ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `brand` varchar(100) DEFAULT 'Jenny Luxe';
ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `tags` varchar(255) DEFAULT NULL;
ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `sku` varchar(100) DEFAULT NULL;
ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `weight` decimal(8,2) DEFAULT NULL;
ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `is_active` tinyint(1) DEFAULT 1;
ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- B2B user fields
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `account_type` enum('retail','b2b') DEFAULT 'retail';
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `company_name` varchar(150) DEFAULT NULL;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `business_email` varchar(150) DEFAULT NULL;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `tax_id` varchar(100) DEFAULT NULL;

-- --------------------------------------------------------
-- Seed: Products (Cosmetics)
-- --------------------------------------------------------
INSERT IGNORE INTO `products` (`name`, `category`, `price`, `old_price`, `description`, `image`, `stock`, `rating`, `is_featured`, `is_new`, `is_bestseller`, `discount_percent`) VALUES
('Velvet Matte Lipstick', 'Cosmetics', 699, 999, 'Long-lasting velvet matte lipstick with rich pigmentation. Available in 12 shades.', 'Lip stick.jpg', 150, 4.8, 1, 1, 1, 30),
('HD Foundation SPF 30', 'Cosmetics', 1299, 1799, 'Lightweight HD foundation with SPF 30 for flawless full coverage all day.', 'foundation.jpg', 80, 4.7, 1, 0, 1, 28),
('Smoky Eye Shadow Palette', 'Cosmetics', 899, 1199, '12-pan eyeshadow palette with smoky and shimmer shades for every occasion.', 'Eye Shadow.jpg', 120, 4.9, 1, 1, 0, 25),
('Precision Eyeliner Pen', 'Cosmetics', 449, 599, 'Ultra-fine tip waterproof eyeliner pen. Smudge-proof formula lasts 24 hours.', 'Eye liner.jpg', 200, 4.6, 0, 0, 1, 25),
('Rose Glow Blush', 'Cosmetics', 599, 799, 'Silky smooth blush in rose gold tones. Buildable pigment for natural glow.', 'Blush.jpg', 90, 4.5, 1, 0, 0, 25),
('Luxury Lip Gloss', 'Cosmetics', 349, 499, 'Glossy, non-sticky lip gloss with plumping effect. 8 stunning shades.', 'Lip Gloss.jpg', 180, 4.7, 0, 1, 0, 30),
('Lash Volumizing Mascara', 'Cosmetics', 549, 749, 'Dramatic volume mascara with conditioning formula. Curl and lengthen lashes.', 'Eyelashes Mascara.jpg', 140, 4.8, 1, 0, 1, 27),
('Satin Compact Powder', 'Cosmetics', 799, 1099, 'Ultra-fine compact powder for a flawless satin finish. Oil-control formula.', 'COMPACT POWDER.jpg', 100, 4.6, 0, 0, 0, 27),
('Hydrating Primer', 'Cosmetics', 999, 1399, 'Silicone-free hydrating primer that blurs pores and extends makeup wear.', 'Primer.jpg', 75, 4.7, 1, 1, 0, 29),
('Black Kajal with White', 'Cosmetics', 299, 399, 'Intense black kajal with bonus white kajal for inner corner brightening.', 'Kajal.jpg', 250, 4.9, 0, 0, 1, 25),
('Luminous Concealer', 'Cosmetics', 649, 899, 'Full coverage concealer that brightens and conceals. 8-hour wear.', 'Concealer.jpg', 110, 4.5, 0, 1, 0, 28),
('Makeup Fixer Spray', 'Cosmetics', 499, 699, 'Setting spray that locks makeup for 16 hours. Refreshing rose water formula.', 'make up fixer.jpg', 130, 4.8, 1, 0, 1, 29),
('Tinted Lip Tint', 'Cosmetics', 399, 549, 'Sheer tinted lip tint with moisturizing shea butter. 6 natural shades.', 'liptint.jpg', 160, 4.6, 0, 1, 0, 27),
('Base Foundation Stick', 'Cosmetics', 849, 1199, 'Creamy foundation stick for buildable coverage. Perfect for on-the-go touch-ups.', 'Base Stick.jpg', 85, 4.7, 0, 0, 0, 29),
-- Jewelry
('Vintage Gold Necklace Set', 'Jewelry', 1499, 2199, 'Elegant vintage-inspired gold-plated necklace with matching earrings. Perfect for weddings.', 'necklace.jpg', 50, 4.9, 1, 1, 1, 32),
('Statement Chandelier Earrings', 'Jewelry', 899, 1299, 'Stunning gold chandelier earrings with intricate filigree work. Lightweight design.', 'earing.jpg', 80, 4.8, 1, 0, 1, 31),
('Royal Kundan Bracelet', 'Jewelry', 1199, 1699, 'Hand-crafted kundan bracelet with colorful stone inlay. Traditional meets modern.', 'bracelet.jpg', 60, 4.7, 1, 1, 0, 29),
('Crystal Cocktail Ring', 'Jewelry', 599, 899, 'Bold statement ring with Swarovski-inspired crystal setting. Adjustable band.', 'ring.jpg', 120, 4.6, 0, 0, 1, 33),
('Pearl Layered Necklace', 'Jewelry', 1299, 1899, 'Elegant multi-strand pearl layered necklace. Classic and timeless.', 'necklace2.jpg', 45, 4.9, 1, 0, 0, 32),
('Gold Jhumka Earrings', 'Jewelry', 749, 1099, 'Traditional gold jhumka earrings with pearl drops. Perfect for festive occasions.', 'earing2.jpg', 90, 4.8, 0, 1, 1, 32),
('Diamond-Cut Gold Bangle', 'Jewelry', 1799, 2499, 'Diamond-cut gold-plated bangle with geometric pattern. Sold as a set of 2.', 'bracelet2.jpg', 40, 4.7, 1, 0, 0, 28),
('Floral Midi Ring Set', 'Jewelry', 499, 749, 'Set of 5 floral-design midi rings in gold and rose gold. Mix and match.', 'ring2.jpg', 150, 4.5, 0, 1, 0, 33),
('Antique Choker Necklace', 'Jewelry', 1599, 2199, 'Oxidized antique choker with pendant. Boho-chic style for modern women.', 'necklace3.jpg', 35, 4.8, 1, 1, 1, 27);

-- --------------------------------------------------------
-- Table: testimonials
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_name` varchar(100) NOT NULL,
  `client_title` varchar(100) DEFAULT 'Verified Customer',
  `client_img` varchar(255) DEFAULT NULL,
  `review` text NOT NULL,
  `rating` int(1) DEFAULT 5,
  `is_visible` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `testimonials` ADD COLUMN IF NOT EXISTS `sort_order` int(11) DEFAULT 0;
ALTER TABLE `testimonials` ADD COLUMN IF NOT EXISTS `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

INSERT IGNORE INTO `testimonials` (`client_name`, `client_title`, `client_img`, `review`, `rating`, `sort_order`) VALUES
('Lina Farooq', 'Beauty Blogger', 'testimonial-img 1.jpg', 'Jenny\'s quality is absolutely amazing! The lipsticks are so pigmented and the jewelry looks exactly like real gold. I\'ve been a loyal customer for over a year now and I cannot recommend them enough!', 5, 1),
('Olga Mirza', 'Makeup Artist', 'testimonial-img 2.jpg', 'I ordered a necklace and earrings set for my sister\'s wedding. Everyone thought it was real gold! The fast delivery and beautiful packaging made it even more special. 5 stars!', 5, 2),
('Danil Shah', 'Fashion Enthusiast', 'testimonial-img 3.jpg', 'The skincare products are so gentle and effective. My skin feels incredibly soft and glowing. Finally found a trustworthy local brand that delivers premium quality!', 5, 3),
('Ayesha Malik', 'College Student', NULL, 'The compact powder is my HG product now! Great coverage, doesn\'t cake, and the price is unbeatable. Jenny\'s is my go-to for all cosmetics needs.', 5, 4),
('Sana Rehman', 'Homemaker', NULL, 'Ordered the chandelier earrings for Eid and got so many compliments! The quality is outstanding for the price. Will definitely order again.', 5, 5),
('Zara Khan', 'Entrepreneur', NULL, 'The mascara is incredible — gives my lashes so much volume without clumping. The eyeshadow palette is also gorgeous. Highly recommend Jenny\'s to everyone!', 5, 6);

-- --------------------------------------------------------
-- Table: reviews
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `reviewer_name` varchar(100) NOT NULL,
  `reviewer_email` varchar(150) DEFAULT NULL,
  `rating` int(1) DEFAULT 5,
  `comment` text NOT NULL,
  `admin_reply` text DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT 1,
  `is_flagged` tinyint(1) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `reviews` ADD COLUMN IF NOT EXISTS `reviewer_email` varchar(150) DEFAULT NULL;
ALTER TABLE `reviews` ADD COLUMN IF NOT EXISTS `admin_reply` text DEFAULT NULL;
ALTER TABLE `reviews` ADD COLUMN IF NOT EXISTS `is_flagged` tinyint(1) DEFAULT 0;

-- --------------------------------------------------------
-- Table: orders (enhanced)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `customer_name` varchar(150) NOT NULL,
  `customer_email` varchar(150) NOT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT 0,
  `discount_amount` decimal(10,2) DEFAULT 0,
  `shipping_fee` decimal(10,2) DEFAULT 0,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled','refunded') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT 'COD',
  `payment_status` enum('pending','paid','failed','refunded') DEFAULT 'pending',
  `coupon_code` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `city` varchar(100) DEFAULT NULL;
ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `subtotal` decimal(10,2) DEFAULT 0;
ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `discount_amount` decimal(10,2) DEFAULT 0;
ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `shipping_fee` decimal(10,2) DEFAULT 0;
ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `payment_status` enum('pending','paid','failed','refunded') DEFAULT 'pending';
ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `coupon_code` varchar(50) DEFAULT NULL;
ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- --------------------------------------------------------
-- Table: order_items (enhanced)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(200) NOT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `order_items` ADD COLUMN IF NOT EXISTS `product_image` varchar(255) DEFAULT NULL;
ALTER TABLE `order_items` ADD COLUMN IF NOT EXISTS `subtotal` decimal(10,2) DEFAULT 0;

-- --------------------------------------------------------
-- Table: payments
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `method` varchar(50) NOT NULL DEFAULT 'COD',
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','paid','failed','refunded') DEFAULT 'pending',
  `transaction_id` varchar(200) DEFAULT NULL,
  `gateway` varchar(100) DEFAULT NULL,
  `gateway_response` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: coupons
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL UNIQUE,
  `description` varchar(255) DEFAULT NULL,
  `discount_type` enum('percentage','fixed') DEFAULT 'percentage',
  `discount_value` decimal(10,2) NOT NULL,
  `min_order` decimal(10,2) DEFAULT 0,
  `max_uses` int(11) DEFAULT NULL,
  `used_count` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `expires_at` date DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `coupons` (`code`, `description`, `discount_type`, `discount_value`, `min_order`, `max_uses`, `is_active`, `expires_at`) VALUES
('JENNY10', '10% off on orders above Rs.500', 'percentage', 10.00, 500.00, 100, 1, '2026-12-31'),
('WELCOME20', '20% welcome discount for new customers', 'percentage', 20.00, 0.00, 50, 1, '2026-12-31'),
('FLAT200', 'Flat Rs.200 off on any order', 'fixed', 200.00, 1000.00, NULL, 1, '2026-12-31'),
('EID50', '50% off Eid special offer', 'percentage', 50.00, 2000.00, 30, 0, '2026-07-31');

-- --------------------------------------------------------
-- Table: contact_messages (enhanced)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `admin_reply` text DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `replied_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `contact_messages` ADD COLUMN IF NOT EXISTS `admin_reply` text DEFAULT NULL;
ALTER TABLE `contact_messages` ADD COLUMN IF NOT EXISTS `replied_at` timestamp NULL DEFAULT NULL;

-- --------------------------------------------------------
-- Table: notifications
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL DEFAULT 'info',
  `title` varchar(200) NOT NULL,
  `message` text DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed sample orders with payment_status
INSERT IGNORE INTO `orders` (`customer_name`, `customer_email`, `customer_phone`, `shipping_address`, `city`, `subtotal`, `total`, `status`, `payment_method`, `payment_status`) VALUES
('Ayesha Khan', 'ayesha@example.com', '+92 300 1234567', 'House 12, Block 5', 'Karachi', 2199.00, 2199.00, 'delivered', 'COD', 'paid'),
('Sara Ahmed', 'sara@example.com', '+92 321 9876543', 'Flat 3B, DHA Phase 6', 'Lahore', 1698.00, 1698.00, 'shipped', 'COD', 'pending'),
('Fatima Noor', 'fatima@example.com', '+92 333 4567890', 'Street 7, Model Town', 'Islamabad', 899.00, 899.00, 'processing', 'Online', 'paid'),
('Zainab Ali', 'zainab@example.com', '+92 345 6789012', 'Plot 45, Gulshan-e-Iqbal', 'Karachi', 3497.00, 3497.00, 'delivered', 'COD', 'paid'),
('Mariam Shah', 'mariam@example.com', '+92 311 2345678', 'House 22, F-7/3', 'Islamabad', 1299.00, 1299.00, 'pending', 'COD', 'pending'),
('Hina Malik', 'hina@example.com', '+92 302 3456789', 'Apartment 101, Clifton', 'Karachi', 749.00, 749.00, 'delivered', 'Online', 'paid'),
('Nadia Qureshi', 'nadia@example.com', '+92 315 6789012', 'Villa 8, Bahria Town', 'Lahore', 2998.00, 2998.00, 'shipped', 'COD', 'pending'),
('Saba Perveen', 'saba@example.com', '+92 322 8901234', 'House 67, Nazimabad', 'Karachi', 599.00, 599.00, 'processing', 'Online', 'paid'),
('Rida Baig', 'rida@example.com', '+92 312 4567890', 'Block D, North Nazimabad', 'Karachi', 1799.00, 1799.00, 'delivered', 'COD', 'paid'),
('Amna Tariq', 'amna@example.com', '+92 331 2345678', 'Sector G-9/2', 'Islamabad', 1198.00, 1198.00, 'pending', 'COD', 'pending'),
('Lubna Iqbal', 'lubna@example.com', '+92 300 9876543', 'House 5, Johar Town', 'Lahore', 449.00, 449.00, 'delivered', 'Online', 'paid'),
('Shaista Karim', 'shaista@example.com', '+92 321 5678901', 'Street 3, PECHS', 'Karachi', 2698.00, 2698.00, 'delivered', 'COD', 'paid');

-- Seed payments from delivered/paid orders
INSERT IGNORE INTO `payments` (`order_id`, `method`, `amount`, `status`, `paid_at`) VALUES
(1, 'COD', 2199.00, 'paid', NOW()),
(3, 'Online', 899.00, 'paid', NOW()),
(4, 'COD', 3497.00, 'paid', NOW()),
(6, 'Online', 749.00, 'paid', NOW()),
(8, 'Online', 599.00, 'paid', NOW()),
(9, 'COD', 1799.00, 'paid', NOW()),
(11, 'Online', 449.00, 'paid', NOW()),
(12, 'COD', 2698.00, 'paid', NOW());

-- Sample customer users
INSERT IGNORE INTO `users` (`username`, `email`, `password`, `role`, `is_admin`, `full_name`, `phone`, `city`, `status`) VALUES
('ayesha_k', 'ayesha@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 0, 'Ayesha Khan', '+92 300 1234567', 'Karachi', 'active'),
('sara_a', 'sara@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 0, 'Sara Ahmed', '+92 321 9876543', 'Lahore', 'active'),
('fatima_n', 'fatima@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 0, 'Fatima Noor', '+92 333 4567890', 'Islamabad', 'active'),
('zainab_a', 'zainab@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 0, 'Zainab Ali', '+92 345 6789012', 'Karachi', 'active'),
('mariam_s', 'mariam@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 0, 'Mariam Shah', '+92 311 2345678', 'Islamabad', 'active');

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- DONE! Admin: admin@jenny.com / 1683217
-- ============================================================
