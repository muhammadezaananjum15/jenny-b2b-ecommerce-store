<?php
// admin/messages.php | Customer Inquiries & Messages v2.0
require_once 'includes/auth_check.php';
require_once '../config/db.php';

$msg = ''; $msgType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'toggle_read') {
        $id = (int)$_POST['message_id'];
        $read = (int)$_POST['is_read'];
        $pdo->prepare("UPDATE contact_messages SET is_read=? WHERE id=?")->execute([$read, $id]);
        $msg = 'Message status updated.'; $msgType = 'success';
    } elseif ($action === 'reply_message') {
        $id = (int)$_POST['message_id'];
        $reply = trim($_POST['admin_reply'] ?? '');
        $pdo->prepare("UPDATE contact_messages SET admin_reply=?, replied_at=NOW(), is_read=1 WHERE id=?")->execute([$reply, $id]);
        $msg = 'Reply recorded!'; $msgType = 'success';
    } elseif ($action === 'delete_message') {
        $id = (int)$_POST['message_id'];
        $pdo->prepare("DELETE FROM contact_messages WHERE id=?")->execute([$id]);
        $msg = 'Message deleted.'; $msgType = 'success';
    }
}

$messages = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="icon" type="image/png" sizes="32x32" href="../img/favicon-32x32.png">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Messages | Jenny's Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="css/admin.css">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>

<div class="admin-main">
    <div class="admin-topbar">
        <div class="topbar-title">Customer Inquiries</div>
    </div>

    <div class="admin-content">
        <?php if ($msg): ?>
        <div class="toast <?= $msgType ?>" style="position:relative;margin-bottom:16px;animation:none;">
            <i class="fas fa-<?= $msgType==='success'?'check-circle':'exclamation-circle' ?>"></i> <?= htmlspecialchars($msg) ?>
        </div>
        <?php endif; ?>

        <div class="table-card">
            <div class="table-header">
                <div class="table-title">Contact Form Submissions</div>
            </div>
            <table class="admin-table">
                <thead><tr>
                    <th>Sender</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr></thead>
                <tbody>
                    <?php foreach ($messages as $m): ?>
                    <tr style="<?= !$m['is_read'] ? 'background:rgba(244,180,0,0.03);' : '' ?>">
                        <td>
                            <div style="font-weight:600;color:#fff;"><?= htmlspecialchars($m['name']) ?></div>
                            <div style="font-size:0.75rem;color:#888;"><?= htmlspecialchars($m['email']) ?></div>
                            <div style="font-size:0.75rem;color:#666;"><?= htmlspecialchars($m['phone'] ?? '') ?></div>
                        </td>
                        <td style="font-weight:600;color:var(--gold);"><?= htmlspecialchars($m['subject']) ?></td>
                        <td style="max-width:320px;">
                            <div style="font-size:0.82rem;color:#ccc;"><?= htmlspecialchars($m['message']) ?></div>
                            <?php if ($m['admin_reply']): ?>
                            <div style="margin-top:6px;padding:6px 10px;background:rgba(46,204,113,0.06);border-left:2px solid var(--green);border-radius:4px;font-size:0.75rem;color:var(--green);">
                                <strong>Reply sent:</strong> <?= htmlspecialchars($m['admin_reply']) ?>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="toggle_read">
                                <input type="hidden" name="message_id" value="<?= $m['id'] ?>">
                                <input type="hidden" name="is_read" value="<?= $m['is_read'] ? 0 : 1 ?>">
                                <button type="submit" class="badge-status badge-<?= $m['is_read'] ? 'delivered' : 'pending' ?>" style="border:none;cursor:pointer;">
                                    <?= $m['is_read'] ? 'Read' : 'Unread' ?>
                                </button>
                            </form>
                        </td>
                        <td style="color:#666;font-size:0.78rem;"><?= date('M d, Y H:i', strtotime($m['created_at'])) ?></td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-action btn-reply" onclick="openReplyMsgModal(<?= $m['id'] ?>, '<?= htmlspecialchars(addslashes($m['admin_reply'] ?? '')) ?>')" title="Reply"><i class="fas fa-reply"></i></button>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Delete message?')">
                                    <input type="hidden" name="action" value="delete_message">
                                    <input type="hidden" name="message_id" value="<?= $m['id'] ?>">
                                    <button type="submit" class="btn-action btn-delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($messages)): ?>
                    <tr><td colspan="6" class="empty-state"><i class="fas fa-envelope"></i>No messages received</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- REPLY MODAL -->
<div class="modal-overlay" id="replyMsgModal">
    <div class="modal-box modal-sm">
        <div class="modal-header">
            <div class="modal-title"><i class="fas fa-reply" style="color:var(--gold);margin-right:8px;"></i>Reply to Customer</div>
            <button class="modal-close" onclick="closeModal('replyMsgModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="reply_message">
            <input type="hidden" name="message_id" id="replyMsgId">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Response Message</label>
                    <textarea name="admin_reply" id="replyMsgText" class="form-control" rows="4" placeholder="Write your response..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline-gold" onclick="closeModal('replyMsgModal')">Cancel</button>
                <button type="submit" class="btn-gold"><i class="fas fa-paper-plane"></i> Send Reply</button>
            </div>
        </form>
    </div>
</div>

<script>
function openReplyMsgModal(id, currentReply) {
    document.getElementById('replyMsgId').value = id;
    document.getElementById('replyMsgText').value = currentReply;
    openModal('replyMsgModal');
}
</script>
</body>
</html>
