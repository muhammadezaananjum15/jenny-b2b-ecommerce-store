<?php
// admin/payments.php | Payment Tracking & Analytics v2.0
require_once 'includes/auth_check.php';
require_once '../config/db.php';

$msg = ''; $msgType = '';

// Handle manual payment status change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_id'])) {
    $pay_id = (int)$_POST['payment_id'];
    $status = trim($_POST['status']);
    
    try {
        $pdo->prepare("UPDATE payments SET status=?, updated_at=NOW() WHERE id=?")->execute([$status, $pay_id]);
        
        // Sync with order table payment_status
        $pay = $pdo->query("SELECT order_id FROM payments WHERE id=$pay_id")->fetch();
        if ($pay) {
            $pdo->prepare("UPDATE orders SET payment_status=? WHERE id=?")->execute([$status, $pay['order_id']]);
        }
        
        $msg = "Payment #$pay_id status updated."; $msgType = 'success';
    } catch (PDOException $e) {
        $msg = "Error updating payment."; $msgType = 'error';
    }
}

// Fetch payment stats
$totalPaid = (float)($pdo->query("SELECT COALESCE(SUM(amount),0) FROM payments WHERE status='paid'")->fetchColumn() ?? 0);
$totalPending = (float)($pdo->query("SELECT COALESCE(SUM(amount),0) FROM payments WHERE status='pending'")->fetchColumn() ?? 0);
$totalRefunded = (float)($pdo->query("SELECT COALESCE(SUM(amount),0) FROM payments WHERE status='refunded'")->fetchColumn() ?? 0);

// Filters & Pagination
$filter = trim($_GET['status'] ?? '');
$search = trim($_GET['search'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 15;
$offset = ($page - 1) * $perPage;

$whereClauses = ["1=1"];
$params = [];

if ($filter) {
    $whereClauses[] = "p.status = ?";
    $params[] = $filter;
}
if ($search) {
    $whereClauses[] = "(p.id LIKE ? OR p.order_id LIKE ? OR o.customer_name LIKE ? OR p.transaction_id LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$where = "WHERE " . implode(" AND ", $whereClauses);

$totalStmt = $pdo->prepare("SELECT COUNT(*) FROM payments p LEFT JOIN orders o ON p.order_id = o.id $where");
$totalStmt->execute($params);
$totalRecords = (int)$totalStmt->fetchColumn();

$stmt = $pdo->prepare("SELECT p.*, o.customer_name, o.customer_email FROM payments p LEFT JOIN orders o ON p.order_id = o.id $where ORDER BY p.created_at DESC LIMIT $perPage OFFSET $offset");
$stmt->execute($params);
$payments = $stmt->fetchAll();

$totalPages = ceil($totalRecords / $perPage);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payments | Jenny's Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="css/admin.css">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>

<div class="admin-main">
    <div class="admin-topbar">
        <div class="topbar-title">Payment Management</div>
        <div class="topbar-right">
            <span style="color:#888;font-size:0.85rem;">Collected: <strong style="color:var(--green);">Rs.<?= number_format($totalPaid) ?></strong></span>
        </div>
    </div>

    <div class="admin-content">
        <?php if ($msg): ?>
        <div class="toast <?= $msgType ?>" style="position:relative;margin-bottom:16px;animation:none;">
            <i class="fas fa-<?= $msgType==='success'?'check-circle':'exclamation-circle' ?>"></i> <?= htmlspecialchars($msg) ?>
        </div>
        <?php endif; ?>

        <!-- STAT CARDS -->
        <div class="stats-grid" style="grid-template-columns:repeat(3, 1fr);margin-bottom:20px;">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                <div class="stat-value">Rs.<?= number_format($totalPaid) ?></div>
                <div class="stat-label">Total Completed Payments</div>
                <span class="stat-change up"><i class="fas fa-arrow-up"></i> Successful</span>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
                <div class="stat-value">Rs.<?= number_format($totalPending) ?></div>
                <div class="stat-label">Pending Payments</div>
                <span class="stat-change neutral"><i class="fas fa-clock"></i> Awaiting Settlement</span>
            </div>
            <div class="stat-card">
                <div class="stat-icon red"><i class="fas fa-undo"></i></div>
                <div class="stat-value">Rs.<?= number_format($totalRefunded) ?></div>
                <div class="stat-label">Refunded Amount</div>
                <span class="stat-change down"><i class="fas fa-arrow-down"></i> Refunds</span>
            </div>
        </div>

        <div class="table-card">
            <div class="table-header">
                <div class="table-title">Payment Transactions</div>
                <div class="table-actions">
                    <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap;">
                        <div class="search-bar">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" placeholder="Search order ID, txn ID..." value="<?= htmlspecialchars($search) ?>">
                        </div>
                        <select name="status" class="form-control" style="width:130px;" onchange="this.form.submit()">
                            <option value="">All Statuses</option>
                            <?php foreach (['paid','pending','failed','refunded'] as $st): ?>
                            <option value="<?= $st ?>" <?= $filter===$st?'selected':'' ?>><?= ucfirst($st) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn-gold"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>

            <table class="admin-table">
                <thead><tr>
                    <th>#Txn ID</th>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Method</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Paid At</th>
                    <th>Action</th>
                </tr></thead>
                <tbody>
                    <?php foreach ($payments as $p): ?>
                    <tr>
                        <td style="font-family:monospace;color:var(--gold);"><?= htmlspecialchars($p['transaction_id'] ?: 'TXN-'.$p['id']) ?></td>
                        <td><a href="orders.php?view_id=<?= $p['order_id'] ?>" style="color:var(--blue);text-decoration:none;font-weight:600;">#<?= $p['order_id'] ?></a></td>
                        <td>
                            <div style="font-weight:600;color:#fff;"><?= htmlspecialchars($p['customer_name'] ?: 'Guest') ?></div>
                            <div style="font-size:0.75rem;color:#666;"><?= htmlspecialchars($p['customer_email'] ?? '') ?></div>
                        </td>
                        <td><span class="label-tag"><?= htmlspecialchars($p['method']) ?></span></td>
                        <td style="font-weight:700;color:var(--gold);">Rs.<?= number_format($p['amount']) ?></td>
                        <td><span class="badge-status badge-<?= $p['status'] ?>"><?= ucfirst($p['status']) ?></span></td>
                        <td style="color:#666;font-size:0.78rem;"><?= $p['paid_at'] ? date('M d, Y H:i', strtotime($p['paid_at'])) : ' | ' ?></td>
                        <td>
                            <form method="POST" style="display:flex;gap:6px;align-items:center;">
                                <input type="hidden" name="payment_id" value="<?= $p['id'] ?>">
                                <select name="status" class="form-control" style="padding:4px 6px;font-size:0.75rem;width:110px;">
                                    <?php foreach (['paid','pending','failed','refunded'] as $s): ?>
                                    <option value="<?= $s ?>" <?= $p['status']===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn-action btn-approve" title="Save Status"><i class="fas fa-check"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($payments)): ?>
                    <tr><td colspan="8" class="empty-state"><i class="fas fa-credit-card"></i>No payment transactions found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <div class="pagination-info">Page <?= $page ?> of <?= $totalPages ?></div>
                <div class="pagination-buttons">
                    <?php for($i=1;$i<=$totalPages;$i++): ?>
                    <a href="?page=<?= $i ?>&status=<?= urlencode($filter) ?>&search=<?= urlencode($search) ?>" class="page-btn <?= $i==$page?'active':'' ?>"><?= $i ?></a>
                    <?php endfor; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
