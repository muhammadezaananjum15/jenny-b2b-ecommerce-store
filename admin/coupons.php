<?php
// admin/coupons.php | Coupons & Discount Management v2.0
require_once 'includes/auth_check.php';
require_once '../config/db.php';

$msg = ''; $msgType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'save_coupon') {
        $id = (int)($_POST['coupon_id'] ?? 0);
        $code = strtoupper(trim($_POST['code'] ?? ''));
        $description = trim($_POST['description'] ?? '');
        $discount_type = trim($_POST['discount_type'] ?? 'percentage');
        $discount_value = (float)($_POST['discount_value'] ?? 0);
        $min_order = (float)($_POST['min_order'] ?? 0);
        $max_uses = !empty($_POST['max_uses']) ? (int)$_POST['max_uses'] : null;
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $expires_at = !empty($_POST['expires_at']) ? $_POST['expires_at'] : null;

        try {
            if ($id > 0) {
                $stmt = $pdo->prepare("UPDATE coupons SET code=?, description=?, discount_type=?, discount_value=?, min_order=?, max_uses=?, is_active=?, expires_at=? WHERE id=?");
                $stmt->execute([$code, $description, $discount_type, $discount_value, $min_order, $max_uses, $is_active, $expires_at, $id]);
                $msg = 'Coupon code updated!'; $msgType = 'success';
            } else {
                $stmt = $pdo->prepare("INSERT INTO coupons (code, description, discount_type, discount_value, min_order, max_uses, is_active, expires_at) VALUES (?,?,?,?,?,?,?,?)");
                $stmt->execute([$code, $description, $discount_type, $discount_value, $min_order, $max_uses, $is_active, $expires_at]);
                $msg = 'New coupon created!'; $msgType = 'success';
            }
        } catch (PDOException $e) {
            $msg = 'Error saving coupon: Code must be unique.'; $msgType = 'error';
        }
    } elseif ($action === 'delete_coupon') {
        $id = (int)($_POST['coupon_id'] ?? 0);
        $pdo->prepare("DELETE FROM coupons WHERE id=?")->execute([$id]);
        $msg = 'Coupon deleted.'; $msgType = 'success';
    }
}

$coupons = $pdo->query("SELECT * FROM coupons ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="icon" type="image/png" sizes="32x32" href="../img/favicon-32x32.png">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Coupons | Jenny's Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="css/admin.css">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>

<div class="admin-main">
    <div class="admin-topbar">
        <div class="topbar-title">Coupon Management</div>
        <div class="topbar-right">
            <button class="btn-gold" onclick="openAddCouponModal()"><i class="fas fa-plus"></i> Create Coupon</button>
        </div>
    </div>

    <div class="admin-content">
        <?php if ($msg): ?>
        <div class="toast <?= $msgType ?>" style="position:relative;margin-bottom:16px;animation:none;">
            <i class="fas fa-<?= $msgType==='success'?'check-circle':'exclamation-circle' ?>"></i> <?= htmlspecialchars($msg) ?>
        </div>
        <?php endif; ?>

        <div class="table-card">
            <div class="table-header">
                <div class="table-title">Active Discount Coupons</div>
            </div>
            <table class="admin-table">
                <thead><tr>
                    <th>Code</th>
                    <th>Description</th>
                    <th>Discount</th>
                    <th>Min. Spend</th>
                    <th>Usage</th>
                    <th>Expires</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr></thead>
                <tbody>
                    <?php foreach ($coupons as $c): ?>
                    <tr>
                        <td><span class="coupon-badge"><?= htmlspecialchars($c['code']) ?></span></td>
                        <td style="color:#ccc;font-size:0.84rem;"><?= htmlspecialchars($c['description'] ?: ' | ') ?></td>
                        <td style="font-weight:700;color:var(--gold);">
                            <?= $c['discount_type']==='percentage' ? $c['discount_value'].'%' : 'Rs.'.$c['discount_value'] ?>
                        </td>
                        <td>Rs.<?= number_format($c['min_order']) ?></td>
                        <td><?= $c['used_count'] ?> / <?= $c['max_uses'] ?: '∞' ?></td>
                        <td style="color:#888;font-size:0.8rem;"><?= $c['expires_at'] ? date('M d, Y', strtotime($c['expires_at'])) : 'Never' ?></td>
                        <td><span class="badge-status badge-<?= $c['is_active'] ? 'active' : 'inactive' ?>"><?= $c['is_active'] ? 'Active' : 'Disabled' ?></span></td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-action btn-edit" onclick="editCoupon(<?= htmlspecialchars(json_encode($c)) ?>)"><i class="fas fa-edit"></i></button>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this coupon?')">
                                    <input type="hidden" name="action" value="delete_coupon">
                                    <input type="hidden" name="coupon_id" value="<?= $c['id'] ?>">
                                    <button type="submit" class="btn-action btn-delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($coupons)): ?>
                    <tr><td colspan="8" class="empty-state"><i class="fas fa-ticket-alt"></i>No coupons available</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- COUPON MODAL -->
<div class="modal-overlay" id="couponModal">
    <div class="modal-box modal-sm">
        <div class="modal-header">
            <div class="modal-title"><i class="fas fa-ticket-alt" style="color:var(--gold);margin-right:8px;"></i><span id="couponModalTitle">Create Coupon</span></div>
            <button class="modal-close" onclick="closeModal('couponModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="save_coupon">
            <input type="hidden" name="coupon_id" id="cId" value="0">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Coupon Code *</label>
                    <input type="text" name="code" id="cCode" class="form-control" placeholder="e.g. SUMMER10" required style="text-transform:uppercase;">
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <input type="text" name="description" id="cDesc" class="form-control" placeholder="Short detail...">
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Discount Type</label>
                        <select name="discount_type" id="cType" class="form-control">
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed Amount (Rs.)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Value *</label>
                        <input type="number" name="discount_value" id="cVal" class="form-control" placeholder="10" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Min Spend (Rs.)</label>
                        <input type="number" name="min_order" id="cMin" class="form-control" value="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Max Uses Limit</label>
                        <input type="number" name="max_uses" id="cMax" class="form-control" placeholder="Leave empty for unlimited">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Expiry Date</label>
                    <input type="date" name="expires_at" id="cExp" class="form-control">
                </div>
                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;color:#aaa;font-size:0.875rem;">
                        <input type="checkbox" name="is_active" id="cActive" value="1" checked> Active & Redeemable
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline-gold" onclick="closeModal('couponModal')">Cancel</button>
                <button type="submit" class="btn-gold"><i class="fas fa-save"></i> Save Coupon</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddCouponModal() {
    document.getElementById('couponModalTitle').textContent = 'Create Coupon';
    document.getElementById('cId').value = 0;
    document.getElementById('cCode').value = '';
    document.getElementById('cDesc').value = '';
    document.getElementById('cVal').value = '';
    document.getElementById('cMin').value = '0';
    document.getElementById('cMax').value = '';
    document.getElementById('cExp').value = '';
    openModal('couponModal');
}
function editCoupon(c) {
    document.getElementById('couponModalTitle').textContent = 'Edit Coupon';
    document.getElementById('cId').value = c.id;
    document.getElementById('cCode').value = c.code;
    document.getElementById('cDesc').value = c.description || '';
    document.getElementById('cType').value = c.discount_type;
    document.getElementById('cVal').value = c.discount_value;
    document.getElementById('cMin').value = c.min_order;
    document.getElementById('cMax').value = c.max_uses || '';
    document.getElementById('cExp').value = c.expires_at || '';
    document.getElementById('cActive').checked = c.is_active == 1;
    openModal('couponModal');
}
</script>
</body>
</html>
