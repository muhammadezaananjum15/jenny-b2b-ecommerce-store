<?php
// config/db.php | Resilient Auto-Setup Connection (jennys_db v2.0)
$host     = 'localhost';
$dbname   = 'jennys_db';
$username = 'root';
$password = '';

try {
    // 1. Connect to MySQL server first (no DB selected)
    $pdo_init = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo_init->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Auto-create database if not existing
    $pdo_init->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    // 3. Connect to target database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // 4. Auto-initialize tables from setup.sql if products table is missing
    $checkTable = $pdo->query("SHOW TABLES LIKE 'products'")->fetchAll();
    if (count($checkTable) === 0) {
        $sqlPath = __DIR__ . '/setup.sql';
        if (file_exists($sqlPath)) {
            $sqlContent = file_get_contents($sqlPath);
            // Split by ; and execute individually
            $statements = array_filter(array_map('trim', explode(';', $sqlContent)));
            foreach ($statements as $stmt) {
                if (!empty($stmt) && strtoupper(substr($stmt, 0, 2)) !== '--') {
                    try { $pdo->exec($stmt); } catch (PDOException $ex) { /* skip ALTER errors */ }
                }
            }
        }
    } else {
        // 5. Run schema upgrades (hero_slides, payments, coupons etc.) if missing
        $newTables = ['hero_slides', 'payments', 'coupons', 'notifications', 'admin_settings'];
        foreach ($newTables as $tbl) {
            $exists = $pdo->query("SHOW TABLES LIKE '$tbl'")->fetchAll();
            if (count($exists) === 0) {
                $sqlPath = __DIR__ . '/setup.sql';
                if (file_exists($sqlPath)) {
                    $sqlContent = file_get_contents($sqlPath);
                    $statements = array_filter(array_map('trim', explode(';', $sqlContent)));
                    foreach ($statements as $stmt) {
                        if (!empty($stmt)) {
                            try { $pdo->exec($stmt); } catch (PDOException $ex) { /* skip */ }
                        }
                    }
                }
                break;
            }
        }

        // 5b. Auto-migrate B2B + brand + profile columns (idempotent)
        $migrations = [
            "UPDATE `hero_slides` SET `badge_text` = TRIM(REPLACE(`badge_text`, 'DEMO', '')) WHERE `badge_text` LIKE '%DEMO%'",
            // Products
            "ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `brand` varchar(100) DEFAULT 'Jenny Luxe'",
            // User B2B fields
            "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `account_type` enum('retail','b2b') DEFAULT 'retail'",
            "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `company_name` varchar(150) DEFAULT NULL",
            "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `business_email` varchar(150) DEFAULT NULL",
            "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `tax_id` varchar(100) DEFAULT NULL",
            // User profile fields
            "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `full_name` varchar(150) DEFAULT NULL",
            "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `phone` varchar(20) DEFAULT NULL",
            "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `birthdate` date DEFAULT NULL",
            "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `address` text DEFAULT NULL",
            "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `city` varchar(100) DEFAULT NULL",
            "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `profile_image` varchar(255) DEFAULT NULL",
            "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `bio` text DEFAULT NULL",
            "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `status` enum('active','banned','inactive') DEFAULT 'active'",
            // Contact messages table
            "CREATE TABLE IF NOT EXISTS `contact_messages` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(150) NOT NULL,
                `email` varchar(150) NOT NULL,
                `phone` varchar(30) DEFAULT NULL,
                `subject` varchar(255) NOT NULL,
                `message` text NOT NULL,
                `is_read` tinyint(1) DEFAULT 0,
                `admin_reply` text DEFAULT NULL,
                `replied_at` timestamp NULL DEFAULT NULL,
                `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "ALTER TABLE `contact_messages` ADD COLUMN IF NOT EXISTS `admin_reply` text DEFAULT NULL",
            "ALTER TABLE `contact_messages` ADD COLUMN IF NOT EXISTS `replied_at` timestamp NULL DEFAULT NULL",
            "ALTER TABLE `contact_messages` ADD COLUMN IF NOT EXISTS `is_read` tinyint(1) DEFAULT 0",
            // Coupons table
            "CREATE TABLE IF NOT EXISTS `coupons` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(50) NOT NULL UNIQUE,
                `description` text DEFAULT NULL,
                `discount_type` enum('percentage','fixed') DEFAULT 'percentage',
                `discount_value` decimal(10,2) NOT NULL DEFAULT 0,
                `min_order` decimal(10,2) DEFAULT 0,
                `max_uses` int(11) DEFAULT NULL,
                `used_count` int(11) DEFAULT 0,
                `is_active` tinyint(1) DEFAULT 1,
                `expires_at` date DEFAULT NULL,
                `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "ALTER TABLE `coupons` ADD COLUMN IF NOT EXISTS `description` text DEFAULT NULL",
            "ALTER TABLE `coupons` ADD COLUMN IF NOT EXISTS `discount_type` enum('percentage','fixed') DEFAULT 'percentage'",
            "ALTER TABLE `coupons` ADD COLUMN IF NOT EXISTS `discount_value` decimal(10,2) DEFAULT 0",
            // Order items
            "CREATE TABLE IF NOT EXISTS `order_items` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `order_id` int(11) NOT NULL,
                `product_id` int(11) DEFAULT NULL,
                `product_name` varchar(200) NOT NULL,
                `product_image` varchar(255) DEFAULT NULL,
                `price` decimal(10,2) NOT NULL,
                `quantity` int(11) DEFAULT 1,
                `subtotal` decimal(10,2) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            // Payments
            "CREATE TABLE IF NOT EXISTS `payments` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `order_id` int(11) NOT NULL,
                `method` varchar(50) DEFAULT 'COD',
                `amount` decimal(10,2) NOT NULL,
                `status` enum('pending','paid','failed','refunded') DEFAULT 'pending',
                `transaction_id` varchar(100) DEFAULT NULL,
                `paid_at` timestamp NULL DEFAULT NULL,
                `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            // Notifications
            "CREATE TABLE IF NOT EXISTS `notifications` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `type` varchar(50) DEFAULT 'info',
                `title` varchar(255) NOT NULL,
                `message` text DEFAULT NULL,
                `is_read` tinyint(1) DEFAULT 0,
                `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            // Orders extra columns
            "ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `order_number` varchar(50) DEFAULT NULL",
            "ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `payment_status` enum('pending','paid','failed','refunded') DEFAULT 'pending'",
            "ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `coupon_code` varchar(50) DEFAULT NULL",
            "ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `notes` text DEFAULT NULL",
            "ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `total_amount` decimal(10,2) DEFAULT NULL",
        ];
        foreach ($migrations as $m) {
            try { $pdo->exec($m); } catch (PDOException $ex) { /* already exists */ }
        }
    }

    // 6. Ensure admin user has correct password hash for 1683217
    try {
        $adminCheck = $pdo->prepare("SELECT id, password FROM users WHERE email = 'admin@jenny.com' AND is_admin = 1 LIMIT 1");
        $adminCheck->execute();
        $adminRow = $adminCheck->fetch();
        if ($adminRow && !password_verify('1683217', $adminRow['password'])) {
            $newHash = password_hash('1683217', PASSWORD_BCRYPT);
            $pdo->prepare("UPDATE users SET password = ? WHERE email = 'admin@jenny.com'")->execute([$newHash]);
        } elseif (!$adminRow) {
            $newHash = password_hash('1683217', PASSWORD_BCRYPT);
            $pdo->prepare("INSERT IGNORE INTO users (username, email, password, role, is_admin, full_name, status) VALUES ('admin','admin@jenny.com',?,'admin',1,'Jenny Admin','active')")->execute([$newHash]);
        }
    } catch (PDOException $ex) { /* users table might not exist yet */ }

    // 6.5 Auto-seed full product catalog if products count < 50
    try {
        $prodCount = (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
        if ($prodCount < 50) {
            $seedSql = "INSERT IGNORE INTO `products` (`name`, `category`, `sub_category`, `price`, `old_price`, `description`, `image`, `stock`, `rating`, `review_count`, `is_featured`, `is_new`, `is_bestseller`, `discount_percent`) VALUES
            ('Matte Nude Foundation', 'Cosmetics', 'Foundation', 650, 900, 'Oil-free matte finish foundation for smooth even skin tone.', 'foundation2.jpg', 95, 4.8, 128, 1, 1, 1, 28),
            ('Soft Radiance Foundation', 'Cosmetics', 'Foundation', 600, 850, 'Hydrating light coverage foundation with natural luminous glow.', 'foundation3.jpg', 110, 4.7, 84, 0, 1, 0, 29),
            ('Dewy Glow Foundation', 'Cosmetics', 'Foundation', 550, 800, 'Nourishing dewy foundation for dry & normal skin types.', 'foundation4.jpg', 105, 4.6, 71, 0, 0, 1, 31),
            ('Full Contour Base Stick', 'Cosmetics', 'Base Stick', 899, 1200, 'Dual-ended contour and highlight stick for sculpted features.', 'Base Stick2.jpg', 70, 4.8, 95, 1, 1, 0, 25),
            ('Mineral Touch Base Stick', 'Cosmetics', 'Base Stick', 799, 1100, 'Lightweight mineral base stick for quick seamless blending.', 'Base Stick3.jpg', 80, 4.6, 58, 0, 0, 1, 27),
            ('Velvet Rose Blush', 'Cosmetics', 'blush', 550, 750, 'Powder blush with subtle golden shimmers for radiant cheeks.', 'Blush2.jpg', 115, 4.8, 112, 1, 0, 1, 27),
            ('Coral Sunset Blush', 'Cosmetics', 'blush', 500, 700, 'Warm coral cheek color with ultra-fine pigments.', 'Blush3.jpg', 125, 4.7, 89, 0, 1, 0, 28),
            ('Oil Control Compact Powder', 'Cosmetics', 'compact-powder', 750, 1000, 'Mattifying compact powder that absorbs shine all day.', 'COMPACT POWDER2.jpg', 130, 4.8, 160, 1, 0, 1, 25),
            ('Translucent Compact Powder', 'Cosmetics', 'compact-powder', 850, 1200, 'Silky translucent powder for photo-ready smooth skin.', 'COMPACT POWDER3.jpg', 90, 4.9, 105, 0, 1, 0, 29),
            ('Brightening Liquid Concealer', 'Cosmetics', 'concealer', 600, 850, 'Under-eye brightening concealer with hydrating hyaluronic acid.', 'Concealer2.jpg', 140, 4.7, 134, 1, 0, 1, 29),
            ('Glitter Shimmer Palette', 'Cosmetics', 'Eye Shadow', 950, 1300, '18-shade party shimmer eyeshadow palette with mirror.', 'Eye Shadow2.jpg', 85, 4.9, 210, 1, 1, 1, 27),
            ('Nude Neutral Eyeshadow', 'Cosmetics', 'Eye Shadow', 800, 1100, 'Everyday matte & shimmer neutral eyeshadow palette.', 'Eye Shadow3.jpg', 95, 4.7, 125, 0, 1, 0, 27),
            ('Metallic Jewel Eyeshadow', 'Cosmetics', 'Eye Shadow', 850, 1150, 'High-impact foil metallic eyeshadow singles.', 'Eye Shadow4.jpg', 100, 4.6, 79, 0, 0, 1, 26),
            ('Waterproof Gel Eyeliner', 'Cosmetics', 'eye liner', 400, 550, 'Smudge-proof black gel eyeliner with pro brush.', 'Eye liner2.jpg', 170, 4.8, 142, 1, 0, 1, 27),
            ('Precision Wing Pen', 'Cosmetics', 'eye liner', 450, 600, 'Ergonomic liner pen for razor-sharp wings.', 'Eye liner3.jpg', 150, 4.7, 88, 0, 1, 0, 25),
            ('4D Extension Mascara', 'Cosmetics', 'Mascara', 600, 850, 'Lash extension mascara with microfiber technology.', 'Eyelashes Mascara2.jpg', 130, 4.9, 175, 1, 1, 1, 29),
            ('Dramatic Curl Mascara', 'Cosmetics', 'Mascara', 580, 800, 'Instant curl & lift mascara for wide-awake eyes.', 'Eyelashes Mascara3.jpg', 110, 4.7, 92, 0, 1, 0, 27),
            ('Pearl White Brightening Kajal', 'Cosmetics', 'White Kajal', 350, 500, 'Waterline brightening white kajal stick.', 'Kajal White.jpg', 190, 4.8, 150, 1, 1, 1, 30),
            ('Ultra Smooth White Kajal', 'Cosmetics', 'White Kajal', 380, 520, 'Creamy smudge-free white eyeliner & kajal.', 'Kajal White2.jpg', 160, 4.7, 98, 0, 0, 1, 27),
            ('Plumping Lip Gloss', 'Cosmetics', 'Lip Gloss', 380, 550, 'High-shine plumping lip gloss with vitamin E.', 'Lip Gloss2.jpg', 175, 4.8, 138, 1, 1, 0, 31),
            ('Berry Sparkle Lip Gloss', 'Cosmetics', 'Lip Gloss', 390, 560, 'Deep berry tinted gloss with multidimensional shine.', 'Lip Gloss3.jpg', 145, 4.7, 115, 0, 0, 1, 30),
            ('Nude Rose Lip Gloss', 'Cosmetics', 'Lip Gloss', 360, 500, 'My-lips-but-better neutral gloss.', 'Lip Gloss4.jpg', 155, 4.6, 82, 0, 1, 0, 28),
            ('Ruby Red Liquid Matte', 'Cosmetics', 'Lip Stick', 750, 1050, 'Transfer-proof bold red matte liquid lipstick.', 'Lip stick2.jpg', 165, 4.9, 220, 1, 1, 1, 28),
            ('Satin Nude Lipstick', 'Cosmetics', 'Lip Stick', 720, 980, 'Creamy satin bullet lipstick in nude rose.', 'Lip stick3.jpg', 140, 4.8, 164, 0, 1, 0, 26),
            ('Poreless Matte Primer', 'Cosmetics', 'primer', 950, 1350, 'Mattifying base primer for smooth porcelain skin.', 'Primer2.jpg', 90, 4.9, 140, 1, 0, 1, 30),
            ('Velvet Cherry Lip Tint', 'Cosmetics', 'lip tint', 450, 650, 'Long-lasting stain for lips & cheeks.', 'liptint2.jpg', 180, 4.7, 108, 0, 1, 0, 31),
            ('Glow Setting Spray', 'Cosmetics', 'make up fixer', 520, 750, 'All-day hold setting spray with subtle golden shimmer.', 'make up fixer2.jpg', 120, 4.8, 130, 1, 0, 1, 31),
            ('Rose Hydrating Mist', 'Cosmetics', 'make up fixer', 550, 780, 'Refreshing botanical setting mist.', 'make up fixer3.jpg', 135, 4.7, 95, 0, 1, 0, 29),
            ('Bridal Royal Gold Choker', 'Jewelry', 'necklace', 1650, 2300, 'Heavy royal gold choker set for grand occasions.', 'necklace4.jpg', 40, 4.9, 210, 1, 1, 1, 28),
            ('Pearl Pendant Gold Necklace', 'Jewelry', 'necklace', 1350, 1900, 'Delicate gold chain with freshwater pearl pendant.', 'necklace5.jpg', 55, 4.8, 145, 0, 1, 0, 29),
            ('Kundan Emerald Bridal Set', 'Jewelry', 'necklace', 1850, 2600, 'Emerald green kundan choker & dangling earrings.', 'necklace6.jpg', 30, 4.9, 190, 1, 1, 1, 29),
            ('Filigree Gold Statement Necklace', 'Jewelry', 'necklace', 1400, 1950, 'Lightweight filigree artwork gold necklace.', 'necklace7.jpg', 48, 4.7, 86, 0, 0, 1, 28),
            ('Antique Ruby Layered Necklace', 'Jewelry', 'necklace', 1750, 2450, 'Ruby gemstone embedded antique gold necklace.', 'necklace8.jpg', 38, 4.8, 160, 1, 0, 1, 28),
            ('Diamond Drop Jhumkas', 'Jewelry', 'earing', 850, 1200, 'Cubic zirconia diamond drop jhumka earrings.', 'earing3.jpg', 85, 4.9, 178, 1, 1, 1, 29),
            ('Royal Peacock Gold Earrings', 'Jewelry', 'earing', 920, 1300, 'Intricate peacock motif gold danglers.', 'earing4.jpg', 70, 4.8, 124, 0, 1, 0, 29),
            ('Traditional Chandbali Earrings', 'Jewelry', 'earing', 980, 1400, 'Crescent moon chandbali earrings with pearl tassels.', 'earing5.jpg', 65, 4.9, 195, 1, 0, 1, 30),
            ('Crystal Drop Earrings', 'Jewelry', 'earing', 790, 1100, 'Clear sparkling crystal drop earrings.', 'earing6.jpg', 90, 4.7, 91, 0, 1, 0, 28),
            ('Golden Filigree Hoops', 'Jewelry', 'earing', 690, 980, 'Textured gold filigree hoop earrings.', 'earing7.jpg', 110, 4.6, 75, 0, 0, 1, 29),
            ('Royal Sapphire Cocktail Ring', 'Jewelry', 'ring', 650, 950, 'Faux sapphire gemstone statement ring.', 'ring3.jpg', 130, 4.8, 152, 1, 1, 1, 31),
            ('Rose Gold Solitaire Ring', 'Jewelry', 'ring', 580, 850, 'Classic single solitaire ring in rose gold.', 'ring4.jpg', 140, 4.7, 110, 0, 1, 0, 31),
            ('Kundan Square Statement Ring', 'Jewelry', 'ring', 620, 900, 'Big square kundan cocktail ring.', 'ring5.jpg', 115, 4.8, 144, 1, 0, 1, 31),
            ('Emerald Cut Crystal Ring', 'Jewelry', 'ring', 540, 780, 'Emerald-cut green crystal ring with pavé band.', 'ring6.jpg', 125, 4.6, 88, 0, 1, 0, 30),
            ('Vintage Stackable Rings', 'Jewelry', 'ring', 480, 700, 'Set of 3 textured gold stacking rings.', 'ring7.jpg', 160, 4.5, 95, 0, 0, 1, 31),
            ('Butterfly Gold Adjustable Ring', 'Jewelry', 'ring', 450, 650, 'Delicate gold butterfly ring with open band.', 'ring8.jpg', 170, 4.7, 130, 0, 1, 0, 30),
            ('Pearl Accent Statement Ring', 'Jewelry', 'ring', 520, 750, 'Lustrous pearl ring surrounded by gold leaves.', 'ring9.jpg', 135, 4.8, 118, 1, 0, 1, 30),
            ('Crown Tiara Gold Ring', 'Jewelry', 'ring', 590, 850, 'Princess tiara shaped gold band.', 'ring10.jpg', 145, 4.9, 162, 0, 1, 0, 30),
            ('Solitaire Diamond-Cut Ring', 'Jewelry', 'ring', 610, 880, 'Brilliant cubic zirconia solitaire in gold setting.', 'ring11.jpg', 120, 4.8, 139, 1, 0, 1, 30),
            ('Delicate Gold Chain Bracelet', 'Jewelry', 'bracelet', 680, 980, 'Slim gold link bracelet with lobster clasp.', 'bracelet3.jpg', 100, 4.7, 94, 0, 1, 0, 30),
            ('Pearl Charm Gold Bracelet', 'Jewelry', 'bracelet', 720, 1050, 'Dangling pearl charms on gold chain bracelet.', 'bracelet4.jpg', 95, 4.8, 150, 1, 0, 1, 31),
            ('Antique Bangle Bracelet', 'Jewelry', 'bracelet', 850, 1250, 'Carved antique gold open cuff bangle.', 'bracelet5.jpg', 75, 4.9, 112, 0, 1, 0, 32),
            ('Crystal Embedded Gold Cuff', 'Jewelry', 'bracelet', 790, 1150, 'Wide gold cuff embedded with sparkling crystals.', 'bracelet6.jpg', 80, 4.8, 136, 1, 0, 1, 31),
            ('Minimalist Gold Bar Bracelet', 'Jewelry', 'bracelet', 450, 650, 'Sleek curved gold bar bracelet.', 'bracelet7.jpg', 180, 4.5, 85, 0, 0, 1, 30),
            ('Rose Gold Twist Bracelet', 'Jewelry', 'bracelet', 490, 720, 'Twisted wire bracelet in rose gold finish.', 'bracelet8.jpg', 160, 4.6, 99, 0, 1, 0, 31),
            ('Floral Engraved Gold Bangle', 'Jewelry', 'bracelet', 550, 800, 'Hand-engraved floral design gold bangle.', 'bracelet9.jpg', 130, 4.8, 142, 1, 0, 1, 31),
            ('Twisted Gold Thread Bracelet', 'Jewelry', 'bracelet', 520, 760, 'Flexible gold thread woven cuff.', 'bracelet10.jpg', 140, 4.7, 105, 0, 1, 0, 31),
            ('Beaded Kundan Gold Bracelet', 'Jewelry', 'bracelet', 580, 840, 'Multi-color kundan beads on gold chain.', 'bracelet11.jpg', 110, 4.8, 168, 1, 1, 1, 31);";
            $pdo->exec($seedSql);
        }
    } catch (Exception $ex) {}

    // 7. Copy AI hero images if generated in artifact directory
    $imgDir = __DIR__ . '/../img/';
    if (!is_dir($imgDir)) { @mkdir($imgDir, 0777, true); }
    $artifactDir = 'C:/Users/HP/.gemini/antigravity-ide/brain/79a284bd-7504-48f6-b4dd-e5a404305e98/';
    if (is_dir($artifactDir)) {
        $mappings = [
            'beauty'    => 'hero-ai-beauty.png',
            'jewelry'   => 'hero-ai-jewelry.png',
            'cosmetics' => 'hero-ai-cosmetics.png',
            'skincare'  => 'hero-ai-skincare.png',
            'royal'     => 'hero-ai-royal.png',
        ];
        foreach ($mappings as $key => $targetName) {
            $matches = glob($artifactDir . 'hero_ai_' . $key . '_*.png');
            if (!empty($matches)) {
                @copy($matches[0], $imgDir . $targetName);
            }
        }
    }

} catch (PDOException $e) {
    // Graceful fallback
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
        $pdo = null;
    }
}
?>