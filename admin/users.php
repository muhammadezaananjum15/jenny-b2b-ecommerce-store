<?php
// admin/users.php — Full CRUD Customer Management v2.0
require_once 'includes/auth_check.php';
require_once '../config/db.php';

$msg = ''; $msgType = '';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'edit') {
        $id       = (int)$_POST['user_id'];
        $fullName = trim($_POST['full_name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $phone    = trim($_POST['phone'] ?? '');
        $city     = trim($_POST['city'] ?? '');
        $address  = trim($_POST['address'] ?? '');
        $status   = trim($_POST['status'] ?? 'active');
        $role     = trim($_POST['role'] ?? 'customer');
        $is_admin = $role === 'admin' ? 1 : 0;

        // Profile image upload
        $profileImg = trim($_POST['existing_img'] ?? '');
        if (!empty($_FILES['profile_image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','webp','gif'])) {
                $uploadDir = '../img/profiles/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                $newName = 'user_' . $id . '_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadDir . $newName)) {
                    $profileImg = $newName;
                }
            }
        }

        try {
            $pdo->prepare("UPDATE users SET full_name=?, email=?, phone=?, city=?, address=?, status=?, role=?, is_admin=?, profile_image=? WHERE id=?")
                ->execute([$fullName, $email, $phone, $city, $address, $status, $role, $is_admin, $profileImg, $id]);
            $msg = 'User updated successfully.'; $msgType = 'success';
        } catch (PDOException $e) { $msg = 'Error: ' . $e->getMessage(); $msgType = 'error'; }

    } elseif ($action === 'ban') {
        $id = (int)$_POST['user_id'];
        $newStatus = $_POST['new_status'] ?? 'banned';
        $pdo->prepare("UPDATE users SET status=? WHERE id=? AND is_admin=0")->execute([$newStatus, $id]);
        $msg = 'User status updated.'; $msgType = 'success';

    } elseif ($action === 'delete') {
        $id = (int)$_POST['user_id'];
        $pdo->prepare("DELETE FROM users WHERE id=? AND is_admin=0")->execute([$id]);
        $msg = 'User deleted.'; $msgType = 'success';

    } elseif ($action === 'reset_password') {
        $id = (int)$_POST['user_id'];
        $newPass = trim($_POST['new_password'] ?? '');
        if (strlen($newPass) >= 6) {
            $hash = password_hash($newPass, PASSWORD_BCRYPT);
            $pdo->prepare("UPDATE users SET password=? WHERE id=?")->execute([$hash, $id]);
            $msg = 'Password reset successfully.'; $msgType = 'success';
        } else { $msg = 'Password must be at least 6 characters.'; $msgType = 'error'; }
    }
}

// Fetch users
$search = trim($_GET['search'] ?? '');
$roleFilter = trim($_GET['role'] ?? '');
$statusFilter = trim($_GET['status'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 15; $offset = ($page-1)*$perPage;

$where = "WHERE 1=1";
$params = [];
if ($search) { $where .= " AND (username LIKE ? OR email LIKE ? OR full_name LIKE ?)"; $params = array_merge($params, ["%$search%","%$search%","%$search%"]); }
if ($roleFilter) { $where .= " AND role=?"; $params[] = $roleFilter; }
if ($statusFilter) { $where .= " AND status=?"; $params[] = $statusFilter; }

$totalStmt = $pdo->prepare("SELECT COUNT(*) FROM users $where");
$totalStmt->execute($params);
$totalCount = (int)$totalStmt->fetchColumn();

$stmt = $pdo->prepare("SELECT u.*, (SELECT COUNT(*) FROM orders WHERE user_id=u.id) as order_count FROM users u $where ORDER BY u.created_at DESC LIMIT $perPage OFFSET $offset");
$stmt->execute($params);
$users = $stmt->fetchAll();
$totalPages = ceil($totalCount / $perPage);

// For edit modal
$editUser = null;
if (isset($_GET['edit_id'])) {
    $eu = $pdo->prepare("SELECT * FROM users WHERE id=?");
    $eu->execute([(int)$_GET['edit_id']]);
    $editUser = $eu->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Customers — Jenny's Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="css/admin.css">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>

<div class="admin-main">
    <div class="admin-topbar">
        <div class="topbar-title">Customer Management</div>
        <div class="topbar-right">
            <span style="color:#888;font-size:0.85rem;">Total: <strong style="color:var(--gold);"><?= $totalCount ?></strong> users</span>
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
                <div class="table-title">All Users <span style="color:#888;font-size:0.85rem;">(<?= $totalCount ?>)</span></div>
                <div class="table-actions">
                    <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
                        <div class="search-bar">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" placeholder="Search users..." value="<?= htmlspecialchars($search) ?>">
                        </div>
                        <select name="role" class="form-control" style="width:120px;" onchange="this.form.submit()">
                            <option value="">All Roles</option>
                            <option value="customer" <?= $roleFilter==='customer'?'selected':'' ?>>Customer</option>
                            <option value="admin" <?= $roleFilter==='admin'?'selected':'' ?>>Admin</option>
                        </select>
                        <select name="status" class="form-control" style="width:120px;" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="active" <?= $statusFilter==='active'?'selected':'' ?>>Active</option>
                            <option value="banned" <?= $statusFilter==='banned'?'selected':'' ?>>Banned</option>
                            <option value="inactive" <?= $statusFilter==='inactive'?'selected':'' ?>>Inactive</option>
                        </select>
                        <button type="submit" class="btn-gold" style="padding:8px 14px;"><i class="fas fa-search"></i></button>
                        <?php if ($search || $roleFilter || $statusFilter): ?>
                        <a href="users.php" class="btn-outline-gold" style="padding:8px 14px;">Clear</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <table class="admin-table">
                <thead><tr>
                    <th>User</th>
                    <th>Contact</th>
                    <th>City</th>
                    <th>Role</th>
                    <th>Orders</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr></thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <?php if ($u['profile_image'] && file_exists('../img/profiles/'.$u['profile_image'])): ?>
                                <img src="../img/profiles/<?= htmlspecialchars($u['profile_image']) ?>" class="user-avatar-md" alt="">
                                <?php else: ?>
                                <div class="user-initials"><?= strtoupper(substr($u['username'],0,1)) ?></div>
                                <?php endif; ?>
                                <div>
                                    <div style="font-weight:600;color:#fff;"><?= htmlspecialchars($u['full_name'] ?: $u['username']) ?></div>
                                    <div style="font-size:0.72rem;color:#666;">@<?= htmlspecialchars($u['username']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-size:0.82rem;"><?= htmlspecialchars($u['email']) ?></div>
                            <div style="font-size:0.75rem;color:#666;"><?= htmlspecialchars($u['phone'] ?? '—') ?></div>
                        </td>
                        <td style="color:#888;font-size:0.82rem;"><?= htmlspecialchars($u['city'] ?? '—') ?></td>
                        <td><span class="badge-status <?= $u['role']==='admin'?'badge-jewelry':'badge-processing' ?>"><?= ucfirst($u['role']) ?></span></td>
                        <td style="font-weight:700;color:var(--gold);"><?= $u['order_count'] ?></td>
                        <td><span class="badge-status badge-<?= $u['status'] ?? 'active' ?>"><?= ucfirst($u['status'] ?? 'active') ?></span></td>
                        <td style="color:#666;font-size:0.78rem;"><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-action btn-edit" onclick="openEditModal(<?= htmlspecialchars(json_encode($u)) ?>)" title="Edit"><i class="fas fa-edit"></i></button>
                                <?php if ($u['is_admin'] != 1): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="ban">
                                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                    <input type="hidden" name="new_status" value="<?= ($u['status']??'active')==='banned'?'active':'banned' ?>">
                                    <button type="submit" class="btn-action <?= ($u['status']??'active')==='banned'?'btn-approve':'btn-flag' ?>" title="<?= ($u['status']??'active')==='banned'?'Unban':'Ban' ?>" onclick="return confirm('<?= ($u['status']??'active')==='banned'?'Unban':'Ban' ?> this user?')">
                                        <i class="fas fa-<?= ($u['status']??'active')==='banned'?'user-check':'user-slash' ?>"></i>
                                    </button>
                                </form>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                    <button type="submit" class="btn-action btn-delete" title="Delete" onclick="return confirm('Permanently delete this user?')"><i class="fas fa-trash"></i></button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($users)): ?>
                    <tr><td colspan="8" class="empty-state"><i class="fas fa-users"></i>No users found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <div class="pagination-info">Showing <?= $offset+1 ?>–<?= min($offset+$perPage,$totalCount) ?> of <?= $totalCount ?></div>
                <div class="pagination-buttons">
                    <?php for ($i=1;$i<=$totalPages;$i++): ?>
                    <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&role=<?= urlencode($roleFilter) ?>&status=<?= urlencode($statusFilter) ?>" class="page-btn <?= $i==$page?'active':'' ?>"><?= $i ?></a>
                    <?php endfor; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- EDIT USER MODAL -->
<div class="modal-overlay" id="editUserModal">
    <div class="modal-box modal-lg">
        <div class="modal-header">
            <div class="modal-title"><i class="fas fa-user-edit" style="color:var(--gold);margin-right:8px;"></i>Edit User</div>
            <button class="modal-close" onclick="closeModal('editUserModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="user_id" id="editUserId">
            <input type="hidden" name="existing_img" id="editUserExistingImg">
            <div class="modal-body">
                <div style="display:flex;align-items:center;gap:20px;margin-bottom:24px;padding:16px;background:var(--bg-card2);border-radius:10px;">
                    <div id="editAvatarPreview" style="width:72px;height:72px;border-radius:50%;background:var(--gold);display:flex;align-items:center;justify-content:center;font-size:1.8rem;font-weight:700;color:#000;flex-shrink:0;overflow:hidden;">
                        <span id="editAvatarInitial">A</span>
                    </div>
                    <div>
                        <div id="editUserDisplayName" style="font-weight:700;font-size:1rem;color:#fff;margin-bottom:4px;">User Name</div>
                        <div id="editUserDisplayEmail" style="font-size:0.8rem;color:#888;margin-bottom:10px;"></div>
                        <label class="btn-outline-gold" style="cursor:pointer;font-size:0.78rem;padding:5px 12px;">
                            <i class="fas fa-camera"></i> Change Photo
                            <input type="file" name="profile_image" id="profileImgInput" accept="image/*" style="display:none;" onchange="previewProfileImg(this)">
                        </label>
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" id="editFullName" class="form-control" placeholder="Full name">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" id="editUserEmail" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" id="editUserPhone" class="form-control" placeholder="+92 300 ...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">City</label>
                        <input type="text" name="city" id="editUserCity" class="form-control" placeholder="e.g. Karachi">
                    </div>
                    <div class="form-group form-full">
                        <label class="form-label">Address</label>
                        <textarea name="address" id="editUserAddress" class="form-control" rows="2" placeholder="Full shipping address"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <select name="role" id="editUserRole" class="form-control">
                            <option value="customer">Customer</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" id="editUserStatus" class="form-control">
                            <option value="active">Active</option>
                            <option value="banned">Banned</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <hr class="section-divider">
                <div style="font-size:0.8rem;color:#888;margin-bottom:12px;"><i class="fas fa-key" style="color:var(--gold);margin-right:6px;"></i>Reset Password (leave blank to keep current)</div>
                <div class="form-grid">
                    <div class="form-group form-full" style="position:relative;">
                        <input type="text" id="resetPassInput" class="form-control" placeholder="New password (min 6 chars)" style="display:none;">
                        <input type="hidden" name="action" id="resetActionField" value="edit">
                        <button type="button" class="btn-outline-gold" style="font-size:0.78rem;" onclick="showResetPass()"><i class="fas fa-key"></i> Set New Password</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline-gold" onclick="closeModal('editUserModal')">Cancel</button>
                <button type="submit" class="btn-gold"><i class="fas fa-save"></i> Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(u) {
    document.getElementById('editUserId').value = u.id;
    document.getElementById('editFullName').value = u.full_name || '';
    document.getElementById('editUserEmail').value = u.email || '';
    document.getElementById('editUserPhone').value = u.phone || '';
    document.getElementById('editUserCity').value = u.city || '';
    document.getElementById('editUserAddress').value = u.address || '';
    document.getElementById('editUserRole').value = u.role || 'customer';
    document.getElementById('editUserStatus').value = u.status || 'active';
    document.getElementById('editUserExistingImg').value = u.profile_image || '';
    document.getElementById('editUserDisplayName').textContent = u.full_name || u.username;
    document.getElementById('editUserDisplayEmail').textContent = u.email;
    document.getElementById('editAvatarInitial').textContent = (u.username||'A').charAt(0).toUpperCase();
    openModal('editUserModal');
}
function previewProfileImg(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('editAvatarPreview');
            preview.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function showResetPass() {
    const inp = document.getElementById('resetPassInput');
    inp.style.display = 'block';
    inp.name = 'new_password';
    document.getElementById('resetActionField').value = 'reset_password';
    document.querySelector('#editUserModal form').onsubmit = function() {
        document.querySelector('[name="action"]').value = 'reset_password';
    };
}
<?php if ($editUser): ?>openEditModal(<?= json_encode($editUser) ?>);<?php endif; ?>
</script>
</body>
</html>
