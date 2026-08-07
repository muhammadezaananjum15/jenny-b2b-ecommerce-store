<?php
// admin/orders.php — Full Order Management v2.0
require_once 'includes/auth_check.php';
require_once '../config/db.php';

$msg = ''; $msgType = '';

// Handle Status Updates & Payment Status Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'update_status') {
        $order_id = (int)($_POST['order_id'] ?? 0);
        $status   = trim($_POST['status'] ?? 'pending');
        $pay_status = trim($_POST['payment_status'] ?? 'pending');
        
        try {
            $pdo->prepare("UPDATE orders SET status=?, payment_status=? WHERE id=?")->execute([$status, $pay_status, $order_id]);
            
            // If payment_status is paid and payment record doesn't exist, insert one
            if ($pay_status === 'paid') {
                $checkPay = $pdo->prepare("SELECT id FROM payments WHERE order_id=?");
                $checkPay->execute([$order_id]);
                if (!$checkPay->fetch()) {
                    $ord = $pdo->query("SELECT total, payment_method FROM orders WHERE id=$order_id")->fetch();
                    $pdo->prepare("INSERT INTO payments (order_id, method, amount, status, paid_at) VALUES (?,?,?,?,NOW())")
                        ->execute([$order_id, $ord['payment_method'] ?? 'COD', $ord['total'] ?? 0, 'paid']);
                }
            }

            $msg = "Order #$order_id updated successfully."; $msgType = 'success';
        } catch (PDOException $e) {
            $msg = 'Error updating order: ' . $e->getMessage(); $msgType = 'error';
        }
    } elseif ($action === 'delete_order') {
        $order_id = (int)($_POST['order_id'] ?? 0);
        try {
            $pdo->prepare("DELETE FROM order_items WHERE order_id=?")->execute([$order_id]);
            $pdo->prepare("DELETE FROM payments WHERE order_id=?")->execute([$order_id]);
            $pdo->prepare("DELETE FROM orders WHERE id=?")->execute([$order_id]);
            $msg = "Order #$order_id deleted."; $msgType = 'success';
        } catch (PDOException $e) {
            $msg = 'Error deleting order.'; $msgType = 'error';
        }
    }
}

// Filters & Pagination
$filter = trim($_GET['status'] ?? '');
$payFilter = trim($_GET['payment_status'] ?? '');
$search = trim($_GET['search'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 15;
$offset = ($page - 1) * $perPage;

$whereClauses = ["1=1"];
$params = [];

if ($filter) {
    $whereClauses[] = "status = ?";
    $params[] = $filter;
}
if ($payFilter) {
    $whereClauses[] = "payment_status = ?";
    $params[] = $payFilter;
}
if ($search) {
    $whereClauses[] = "(id LIKE ? OR customer_name LIKE ? OR customer_email LIKE ? OR customer_phone LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$where = "WHERE " . implode(" AND ", $whereClauses);

$total = $pdo->prepare("SELECT COUNT(*) FROM orders $where");
$total->execute($params);
$totalOrders = (int)$total->fetchColumn();

$stmt = $pdo->prepare("SELECT * FROM orders $where ORDER BY created_at DESC LIMIT $perPage OFFSET $offset");
$stmt->execute($params);
$orders = $stmt->fetchAll();

$totalPages = ceil($totalOrders / $perPage);

// Fetch order details for modal if requested
$viewOrder = null;
$viewItems = [];
if (isset($_GET['view_id'])) {
    $vId = (int)$_GET['view_id'];
    $vo = $pdo->prepare("SELECT * FROM orders WHERE id=?");
    $vo->execute([$vId]);
    $viewOrder = $vo->fetch();
    
    if ($viewOrder) {
        $vi = $pdo->prepare("SELECT oi.*, p.image FROM order_items oi LEFT JOIN products p ON oi.product_id=p.id WHERE oi.order_id=?");
        $vi->execute([$vId]);
        $viewItems = $vi->fetchAll();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Orders — Jenny's Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="css/admin.css">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>

<div class="admin-main">
    <div class="admin-topbar">
        <div class="topbar-title">Orders Management</div>
        <div class="topbar-right">
            <span style="color:#888;font-size:0.85rem;">Total Orders: <strong style="color:var(--gold);"><?= $totalOrders ?></strong></span>
        </div>
    </div>

    <div class="admin-content">
        <?php if ($msg): ?>
        <div class="toast <?= $msgType ?>" style="position:relative;margin-bottom:16px;animation:none;">
            <i class="fas fa-<?= $msgType==='success'?'check-circle':'exclamation-circle' ?>"></i>
            <?= htmlspecialchars($msg) ?>
        </div>
        <?php endif; ?>

        <!-- Filter & Search Bar -->
        <div class="table-card" style="margin-bottom:20px;padding:16px 22px;">
            <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;justify-content:space-between;">
                <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
                    <div class="search-bar">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" placeholder="Search order ID, name, email..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <select name="status" class="form-control" style="width:140px;" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <?php foreach (['pending','processing','shipped','delivered','cancelled','refunded'] as $s): ?>
                        <option value="<?= $s ?>" <?= $filter===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="payment_status" class="form-control" style="width:140px;" onchange="this.form.submit()">
                        <option value="">All Payment</option>
                        <?php foreach (['pending','paid','failed','refunded'] as $ps): ?>
                        <option value="<?= $ps ?>" <?= $payFilter===$ps?'selected':'' ?>><?= ucfirst($ps) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn-gold" style="padding:8px 14px;"><i class="fas fa-filter"></i> Filter</button>
                    <?php if ($search || $filter || $payFilter): ?>
                    <a href="orders.php" class="btn-outline-gold" style="padding:8px 14px;">Reset</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="table-card">
            <div class="table-header">
                <div class="table-title">Order Directory</div>
            </div>
            <table class="admin-table">
                <thead><tr>
                    <th>#ID</th>
                    <th>Customer</th>
                    <th>Contact Info</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Pay Status</th>
                    <th>Order Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr></thead>
                <tbody>
                    <?php foreach ($orders as $o): ?>
                    <tr>
                        <td style="color:var(--gold);font-weight:700;">#<?= $o['id'] ?></td>
                        <td>
                            <div style="font-weight:600;color:#fff;"><?= htmlspecialchars($o['customer_name']) ?></div>
                            <div style="font-size:0.75rem;color:#666;"><?= htmlspecialchars($o['city'] ?? '—') ?></div>
                        </td>
                        <td style="font-size:0.8rem;color:#aaa;">
                            <div><?= htmlspecialchars($o['customer_email']) ?></div>
                            <div style="font-size:0.75rem;color:#666;"><?= htmlspecialchars($o['customer_phone'] ?? '—') ?></div>
                        </td>
                        <td style="font-weight:700;color:var(--gold);">Rs.<?= number_format($o['total']) ?></td>
                        <td style="font-size:0.8rem;"><?= htmlspecialchars($o['payment_method']) ?></td>
                        <td><span class="badge-status badge-<?= $o['payment_status'] ?? 'pending' ?>"><?= ucfirst($o['payment_status'] ?? 'pending') ?></span></td>
                        <td><span class="badge-status badge-<?= $o['status'] ?>"><?= ucfirst($o['status']) ?></span></td>
                        <td style="color:#666;font-size:0.78rem;"><?= date('M d, Y', strtotime($o['created_at'])) ?></td>
                        <td>
                            <div class="action-btns">
                                <a href="?view_id=<?= $o['id'] ?>" class="btn-action btn-view" title="View Details"><i class="fas fa-eye"></i></a>
                                <button class="btn-action btn-edit" onclick="openUpdateModal(<?= htmlspecialchars(json_encode($o)) ?>)" title="Update Status"><i class="fas fa-edit"></i></button>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this order permanently?')">
                                    <input type="hidden" name="action" value="delete_order">
                                    <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                                    <button type="submit" class="btn-action btn-delete" title="Delete Order"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($orders)): ?>
                    <tr><td colspan="9" class="empty-state"><i class="fas fa-shopping-bag"></i>No orders found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <div class="pagination-info">Page <?= $page ?> of <?= $totalPages ?></div>
                <div class="pagination-buttons">
                    <?php for($i=1;$i<=$totalPages;$i++): ?>
                    <a href="?page=<?= $i ?>&status=<?= urlencode($filter) ?>&payment_status=<?= urlencode($payFilter) ?>&search=<?= urlencode($search) ?>" class="page-btn <?= $i==$page?'active':'' ?>"><?= $i ?></a>
                    <?php endfor; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- UPDATE STATUS MODAL -->
<div class="modal-overlay" id="updateModal">
    <div class="modal-box modal-sm">
        <div class="modal-header">
            <div class="modal-title"><i class="fas fa-edit" style="color:var(--gold);margin-right:8px;"></i>Update Order #<span id="modOrderId"></span></div>
            <button class="modal-close" onclick="closeModal('updateModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="update_status">
            <input type="hidden" name="order_id" id="formOrderId">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Order Fulfillment Status</label>
                    <select name="status" id="formStatus" class="form-control">
                        <?php foreach (['pending','processing','shipped','delivered','cancelled','refunded'] as $s): ?>
                        <option value="<?= $s ?>"><?= ucfirst($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Payment Status</label>
                    <select name="payment_status" id="formPayStatus" class="form-control">
                        <?php foreach (['pending','paid','failed','refunded'] as $ps): ?>
                        <option value="<?= $ps ?>"><?= ucfirst($ps) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline-gold" onclick="closeModal('updateModal')">Cancel</button>
                <button type="submit" class="btn-gold"><i class="fas fa-save"></i> Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- VIEW ORDER DETAILS MODAL -->
<?php if ($viewOrder): ?>
<div class="modal-overlay active" id="viewModal">
    <div class="modal-box modal-lg">
        <div class="modal-header">
            <div class="modal-title"><i class="fas fa-receipt" style="color:var(--gold);margin-right:8px;"></i>Order Details #<?= $viewOrder['id'] ?></div>
            <a href="orders.php" class="modal-close"><i class="fas fa-times"></i></a>
        </div>
        <div class="modal-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;padding:16px;background:var(--bg-card2);border-radius:10px;">
                <div>
                    <div style="font-size:0.75rem;color:#888;text-transform:uppercase;margin-bottom:4px;">Customer Information</div>
                    <div style="font-weight:700;color:#fff;"><?= htmlspecialchars($viewOrder['customer_name']) ?></div>
                    <div style="font-size:0.85rem;color:#ccc;"><?= htmlspecialchars($viewOrder['customer_email']) ?></div>
                    <div style="font-size:0.85rem;color:#ccc;"><?= htmlspecialchars($viewOrder['customer_phone'] ?? 'N/A') ?></div>
                </div>
                <div>
                    <div style="font-size:0.75rem;color:#888;text-transform:uppercase;margin-bottom:4px;">Shipping Address</div>
                    <div style="font-size:0.85rem;color:#ccc;"><?= nl2br(htmlspecialchars($viewOrder['shipping_address'] ?? 'No address provided')) ?></div>
                    <div style="font-size:0.85rem;color:var(--gold);margin-top:4px;"><?= htmlspecialchars($viewOrder['city'] ?? '') ?></div>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:14px;margin-bottom:20px;">
                <div class="stat-mini">
                    <div style="font-size:0.75rem;color:#888;">Fulfillment Status</div>
                    <span class="badge-status badge-<?= $viewOrder['status'] ?>" style="margin-top:4px;"><?= ucfirst($viewOrder['status']) ?></span>
                </div>
                <div class="stat-mini">
                    <div style="font-size:0.75rem;color:#888;">Payment Method</div>
                    <div style="font-weight:600;color:#fff;margin-top:4px;"><?= htmlspecialchars($viewOrder['payment_method']) ?></div>
                </div>
                <div class="stat-mini">
                    <div style="font-size:0.75rem;color:#888;">Payment Status</div>
                    <span class="badge-status badge-<?= $viewOrder['payment_status'] ?? 'pending' ?>" style="margin-top:4px;"><?= ucfirst($viewOrder['payment_status'] ?? 'pending') ?></span>
                </div>
            </div>

            <div style="font-weight:700;color:#fff;margin-bottom:12px;">Order Items</div>
            <table class="admin-table" style="margin-bottom:20px;">
                <thead><tr>
                    <th>Product</th>
                    <th>Unit Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr></thead>
                <tbody>
                    <?php if (!empty($viewItems)): ?>
                        <?php foreach ($viewItems as $item): ?>
                        <tr>
                            <td>
                                <div class="product-img-cell">
                                    <img src="../img/<?= htmlspecialchars($item['image'] ?? 'foundation.jpg') ?>" onerror="this.src='../img/foundation.jpg'" class="product-thumb">
                                    <div style="font-weight:600;color:#fff;"><?= htmlspecialchars($item['product_name']) ?></div>
                                </div>
                            </td>
                            <td>Rs.<?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['qty'] ?></td>
                            <td style="font-weight:700;color:var(--gold);">Rs.<?= number_format($item['price'] * $item['qty'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="text-align:center;color:#888;">Items data stored in main order record. Subtotal: Rs.<?= number_format($viewOrder['total']) ?></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div style="display:flex;justify-content:flex-end;border-top:1px solid var(--border);padding-top:14px;">
                <div style="width:250px;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px;font-size:0.85rem;color:#888;">
                        <span>Subtotal:</span>
                        <span>Rs.<?= number_format($viewOrder['subtotal'] > 0 ? $viewOrder['subtotal'] : $viewOrder['total'], 2) ?></span>
                    </div>
                    <?php if ($viewOrder['discount_amount'] > 0): ?>
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px;font-size:0.85rem;color:var(--green);">
                        <span>Discount:</span>
                        <span>-Rs.<?= number_format($viewOrder['discount_amount'], 2) ?></span>
                    </div>
                    <?php endif; ?>
                    <div style="display:flex;justify-content:space-between;font-size:1.1rem;font-weight:700;color:var(--gold);border-top:1px solid var(--border);padding-top:6px;margin-top:6px;">
                        <span>Total:</span>
                        <span>Rs.<?= number_format($viewOrder['total'], 2) ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="orders.php" class="btn-outline-gold">Close</a>
            <button onclick="window.print()" class="btn-gold"><i class="fas fa-print"></i> Print Invoice</button>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
function openUpdateModal(o) {
    document.getElementById('modOrderId').textContent = o.id;
    document.getElementById('formOrderId').value = o.id;
    document.getElementById('formStatus').value = o.status;
    document.getElementById('formPayStatus').value = o.payment_status || 'pending';
    openModal('updateModal');
}
</script>
</body>
</html>
