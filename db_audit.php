<?php
require_once 'config/db.php';

header('Content-Type: text/plain; charset=utf-8');

echo "=== JENNY B2B / ADMIN DATABASE AUDIT ===\n\n";

$tables = ['users', 'products', 'orders', 'order_items', 'payments', 'contact_messages', 
           'testimonials', 'reviews', 'coupons', 'admin_settings', 'hero_slides', 'notifications'];

echo "1. TABLE STATUS & ROW COUNTS:\n";
echo "----------------------------------------\n";
foreach ($tables as $t) {
    try {
        $count = $pdo->query("SELECT COUNT(*) FROM `$t`")->fetchColumn();
        echo sprintf("%-20s | OK | Rows: %d\n", $t, $count);
    } catch (PDOException $e) {
        echo sprintf("%-20s | ERROR: %s\n", $t, $e->getMessage());
    }
}

echo "\n2. USER TABLE STRUCTURE & B2B FIELDS:\n";
echo "----------------------------------------\n";
try {
    $cols = $pdo->query("SHOW COLUMNS FROM users")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cols as $c) {
        echo sprintf("%-20s | %-15s | Null: %-3s | Default: %s\n", $c['Field'], $c['Type'], $c['Null'], $c['Default'] ?? 'NULL');
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n3. CONTACT MESSAGES AUDIT:\n";
echo "----------------------------------------\n";
try {
    $msgCount = $pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();
    echo "Total contact messages stored in DB: " . $msgCount . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n4. PRODUCTS & B2B/RETAILER FIELD AUDIT:\n";
echo "----------------------------------------\n";
try {
    $prodCount = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    echo "Total products in DB: " . $prodCount . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
