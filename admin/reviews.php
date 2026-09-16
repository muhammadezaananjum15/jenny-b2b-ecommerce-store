<?php
// admin/reviews.php | Product Reviews Approval & Reply v2.0
require_once 'includes/auth_check.php';
require_once '../config/db.php';

$msg = ''; $msgType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'toggle_approval') {
        $id = (int)$_POST['review_id'];
        $app = (int)$_POST['is_approved'];
        $pdo->prepare("UPDATE reviews SET is_approved=? WHERE id=?")->execute([$app, $id]);
        $msg = 'Approval status updated.'; $msgType = 'success';
    } elseif ($action === 'toggle_flag') {
        $id = (int)$_POST['review_id'];
        $flag = (int)$_POST['is_flagged'];
        $pdo->prepare("UPDATE reviews SET is_flagged=? WHERE id=?")->execute([$flag, $id]);
        $msg = 'Flag status updated.'; $msgType = 'success';
    } elseif ($action === 'reply_review') {
        $id = (int)$_POST['review_id'];
        $reply = trim($_POST['admin_reply'] ?? '');
        $pdo->prepare("UPDATE reviews SET admin_reply=? WHERE id=?")->execute([$reply, $id]);
        $msg = 'Reply saved!'; $msgType = 'success';
    } elseif ($action === 'delete_review') {
        $id = (int)$_POST['review_id'];
        $pdo->prepare("DELETE FROM reviews WHERE id=?")->execute([$id]);
        $msg = 'Review deleted.'; $msgType = 'success';
    }
}

$reviews = $pdo->query("
    SELECT r.*, p.name as product_name, p.image as product_image 
    FROM reviews r 
    LEFT JOIN products p ON r.product_id = p.id 
    ORDER BY r.created_at DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="icon" type="image/png" sizes="32x32" href="../img/favicon-32x32.png">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Product Reviews | Jenny's Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="css/admin.css">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>

<div class="admin-main">
    <div class="admin-topbar">
        <div class="topbar-title">Product Reviews Moderation</div>
    </div>

    <div class="admin-content">
        <?php if ($msg): ?>
        <div class="toast <?= $msgType ?>" style="position:relative;margin-bottom:16px;animation:none;">
            <i class="fas fa-<?= $msgType==='success'?'check-circle':'exclamation-circle' ?>"></i> <?= htmlspecialchars($msg) ?>
        </div>
        <?php endif; ?>

        <div class="table-card">
            <div class="table-header">
                <div class="table-title">Customer Reviews & Ratings</div>
            </div>
            <table class="admin-table">
                <thead><tr>
                    <th>Product</th>
                    <th>Reviewer</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr></thead>
                <tbody>
                    <?php foreach ($reviews as $r): ?>
                    <tr>
                        <td>
                            <div class="product-img-cell">
                                <img src="../img/<?= htmlspecialchars($r['product_image'] ?: 'foundation.jpg') ?>" onerror="this.src='../img/foundation.jpg'" class="product-thumb">
                                <div style="font-weight:600;color:#fff;"><?= htmlspecialchars(substr($r['product_name'] ?: 'Product #'.$r['product_id'],0,20)) ?>...</div>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight:600;color:#fff;"><?= htmlspecialchars($r['reviewer_name']) ?></div>
                            <div style="font-size:0.75rem;color:#666;"><?= htmlspecialchars($r['reviewer_email'] ?? '') ?></div>
                        </td>
                        <td><span style="color:var(--gold);">★ <?= $r['rating'] ?></span></td>
                        <td style="max-width:280px;">
                            <div style="font-size:0.82rem;color:#ccc;"><?= htmlspecialchars($r['comment']) ?></div>
                            <?php if ($r['admin_reply']): ?>
                            <div style="margin-top:6px;padding:6px 10px;background:rgba(244,180,0,0.06);border-left:2px solid var(--gold);border-radius:4px;font-size:0.75rem;color:var(--gold);">
                                <strong>Admin Reply:</strong> <?= htmlspecialchars($r['admin_reply']) ?>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="toggle_approval">
                                <input type="hidden" name="review_id" value="<?= $r['id'] ?>">
                                <input type="hidden" name="is_approved" value="<?= $r['is_approved'] ? 0 : 1 ?>">
                                <button type="submit" class="badge-status badge-<?= $r['is_approved'] ? 'active' : 'inactive' ?>" style="border:none;cursor:pointer;">
                                    <?= $r['is_approved'] ? 'Approved' : 'Pending' ?>
                                </button>
                            </form>
                        </td>
                        <td style="color:#666;font-size:0.78rem;"><?= date('M d, Y', strtotime($r['created_at'])) ?></td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-action btn-reply" onclick="openReplyModal(<?= $r['id'] ?>, '<?= htmlspecialchars(addslashes($r['admin_reply'] ?? '')) ?>')" title="Reply"><i class="fas fa-reply"></i></button>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Delete review?')">
                                    <input type="hidden" name="action" value="delete_review">
                                    <input type="hidden" name="review_id" value="<?= $r['id'] ?>">
                                    <button type="submit" class="btn-action btn-delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($reviews)): ?>
                    <tr><td colspan="7" class="empty-state"><i class="fas fa-comment-dots"></i>No product reviews yet</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- REPLY MODAL -->
<div class="modal-overlay" id="replyModal">
    <div class="modal-box modal-sm">
        <div class="modal-header">
            <div class="modal-title"><i class="fas fa-reply" style="color:var(--gold);margin-right:8px;"></i>Reply to Review</div>
            <button class="modal-close" onclick="closeModal('replyModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="reply_review">
            <input type="hidden" name="review_id" id="replyReviewId">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Admin Official Reply</label>
                    <textarea name="admin_reply" id="replyText" class="form-control" rows="4" placeholder="Type your response to the customer..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline-gold" onclick="closeModal('replyModal')">Cancel</button>
                <button type="submit" class="btn-gold"><i class="fas fa-paper-plane"></i> Save Reply</button>
            </div>
        </form>
    </div>
</div>

<script>
function openReplyModal(id, currentReply) {
    document.getElementById('replyReviewId').value = id;
    document.getElementById('replyText').value = currentReply;
    openModal('replyModal');
}
</script>
</body>
</html>
