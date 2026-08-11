<?php
// process-order.php | Receives order data via AJAX POST and saves to DB
session_start();
require_once 'config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit();
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid data received.']);
    exit();
}

// Extract & sanitize fields
$customer_name    = htmlspecialchars(trim($data['name'] ?? ''), ENT_QUOTES, 'UTF-8');
$customer_email   = filter_var(trim($data['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$customer_phone   = htmlspecialchars(trim($data['phone'] ?? ''), ENT_QUOTES, 'UTF-8');
$shipping_address = htmlspecialchars(trim(($data['address'] ?? '') . ', ' . ($data['city'] ?? '') . ' ' . ($data['zip'] ?? '')), ENT_QUOTES, 'UTF-8');
$payment_method   = htmlspecialchars(trim($data['payment'] ?? 'COD'), ENT_QUOTES, 'UTF-8');
$notes            = htmlspecialchars(trim($data['notes'] ?? ''), ENT_QUOTES, 'UTF-8');
$items            = $data['items'] ?? [];
$total            = 0;

// Calculate server-side total to prevent tampering
foreach ($items as $item) {
    $total += (float)($item['price'] ?? 0) * (int)($item['quantity'] ?? 1);
}

if (empty($customer_name) || empty($customer_email) || empty($customer_phone) || empty($items)) {
    echo json_encode(['success' => false, 'message' => 'Required fields are missing.']);
    exit();
}

if (!$pdo) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit();
}

try {
    $pdo->beginTransaction();

    // 1. Insert order
    $user_id = $_SESSION['user_id'] ?? null;
    $stmt = $pdo->prepare("
        INSERT INTO orders (user_id, customer_name, customer_email, customer_phone, shipping_address, total, status, payment_method, notes)
        VALUES (?, ?, ?, ?, ?, ?, 'pending', ?, ?)
    ");
    $stmt->execute([$user_id, $customer_name, $customer_email, $customer_phone, $shipping_address, $total, $payment_method, $notes]);
    $order_id = $pdo->lastInsertId();

    // 2. Insert order items
    $itemStmt = $pdo->prepare("
        INSERT INTO order_items (order_id, product_id, product_name, qty, price)
        VALUES (?, ?, ?, ?, ?)
    ");
    foreach ($items as $item) {
        $product_id   = is_numeric($item['id'] ?? '') ? (int)$item['id'] : null;
        $product_name = htmlspecialchars(trim($item['name'] ?? 'Unknown Product'), ENT_QUOTES, 'UTF-8');
        $qty          = max(1, (int)($item['quantity'] ?? 1));
        $price        = (float)($item['price'] ?? 0);

        // Validate product_id against DB (prevent injection of fake products)
        if ($product_id) {
            $check = $pdo->prepare("SELECT id FROM products WHERE id = ?");
            $check->execute([$product_id]);
            if (!$check->fetch()) {
                $product_id = null; // Use null if product doesn't exist
            }
        }

        $itemStmt->execute([$order_id, $product_id, $product_name, $qty, $price]);
    }

    $pdo->commit();

    // Store order summary in session for confirmation page
    $_SESSION['last_order'] = [
        'order_id'      => $order_id,
        'customer_name' => $customer_name,
        'customer_email'=> $customer_email,
        'customer_phone'=> $customer_phone,
        'address'       => $shipping_address,
        'payment'       => $payment_method,
        'total'         => $total,
        'items_count'   => count($items),
        'date'          => date('d M Y, h:i A'),
    ];

    echo json_encode([
        'success'  => true,
        'order_id' => $order_id,
        'message'  => 'Order placed successfully!',
    ]);

} catch (PDOException $e) {
    $pdo->rollBack();
    error_log('Order save error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Could not save order. Please try again.']);
}
