<?php
// admin/settings.php — Admin Profile & Site Settings v2.0
require_once 'includes/auth_check.php';
require_once '../config/db.php';

$msg = ''; $msgType = '';

// Handle Profile Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'update_profile') {
        $name = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $admin_id = $_SESSION['admin_id'];

        $profileImg = $_SESSION['admin_img'] ?? null;

        if (!empty($_FILES['profile_image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','webp'])) {
                $uploadDir = '../img/profiles/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                $newName = 'admin_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadDir . $newName)) {
                    $profileImg = $newName;
                }
            }
        }

        try {
            $stmt = $pdo->prepare("UPDATE users SET full_name=?, email=?, phone=?, profile_image=? WHERE id=?");
            $stmt->execute([$name, $email, $phone, $profileImg, $admin_id]);
            $_SESSION['admin_name'] = $name;
            $_SESSION['admin_email'] = $email;
            $_SESSION['admin_img'] = $profileImg;
            $msg = 'Profile updated successfully!'; $msgType = 'success';
        } catch (PDOException $e) {
            $msg = 'Error updating profile.'; $msgType = 'error';
        }

    } elseif ($action === 'change_password') {
        $curr = $_POST['current_password'] ?? '';
        $new  = $_POST['new_password'] ?? '';
        $conf = $_POST['confirm_password'] ?? '';
        $admin_id = $_SESSION['admin_id'];

        $u = $pdo->prepare("SELECT password FROM users WHERE id=?");
        $u->execute([$admin_id]);
        $user = $u->fetch();

        if ($user && password_verify($curr, $user['password'])) {
            if ($new === $conf && strlen($new) >= 6) {
                $hash = password_hash($new, PASSWORD_BCRYPT);
                $pdo->prepare("UPDATE users SET password=? WHERE id=?")->execute([$hash, $admin_id]);
                $msg = 'Password changed successfully!'; $msgType = 'success';
            } else {
                $msg = 'New passwords do not match or are under 6 chars.'; $msgType = 'error';
            }
        } else {
            $msg = 'Incorrect current password.'; $msgType = 'error';
        }

    } elseif ($action === 'update_site_settings') {
        foreach ($_POST['setting'] ?? [] as $k => $v) {
            $stmt = $pdo->prepare("INSERT INTO admin_settings (setting_key, setting_value) VALUES (?,?) ON DUPLICATE KEY UPDATE setting_value=?");
            $stmt->execute([$k, trim($v), trim($v)]);
        }
        $msg = 'Site settings updated!'; $msgType = 'success';
    }
}

// Fetch current site settings
$settingsRows = $pdo->query("SELECT setting_key, setting_value FROM admin_settings")->fetchAll();
$settings = [];
foreach ($settingsRows as $r) {
    $settings[$r['setting_key']] = $r['setting_value'];
}

// Fetch admin profile
$admin_id = $_SESSION['admin_id'];
$adminUser = $pdo->query("SELECT * FROM users WHERE id=$admin_id")->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Settings — Jenny's Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="css/admin.css">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>

<div class="admin-main">
    <div class="admin-topbar">
        <div class="topbar-title">System Settings</div>
    </div>

    <div class="admin-content">
        <?php if ($msg): ?>
        <div class="toast <?= $msgType ?>" style="position:relative;margin-bottom:16px;animation:none;">
            <i class="fas fa-<?= $msgType==='success'?'check-circle':'exclamation-circle' ?>"></i> <?= htmlspecialchars($msg) ?>
        </div>
        <?php endif; ?>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <!-- ADMIN PROFILE SECTION -->
            <div>
                <div class="settings-section">
                    <div class="settings-section-title"><i class="fas fa-user-shield"></i> Admin Account Profile</div>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="update_profile">
                        <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;">
                            <?php if (!empty($adminUser['profile_image']) && file_exists('../img/profiles/'.$adminUser['profile_image'])): ?>
                            <img src="../img/profiles/<?= htmlspecialchars($adminUser['profile_image']) ?>" class="user-avatar-lg">
                            <?php else: ?>
                            <div class="user-avatar-lg" style="display:flex;align-items:center;justify-content:center;background:var(--gold);color:#000;font-size:2rem;font-weight:700;">
                                <?= strtoupper(substr($adminUser['username'] ?? 'A', 0, 1)) ?>
                            </div>
                            <?php endif; ?>
                            <div>
                                <label class="btn-outline-gold" style="cursor:pointer;font-size:0.8rem;padding:6px 12px;">
                                    <i class="fas fa-camera"></i> Change Photo
                                    <input type="file" name="profile_image" accept="image/*" style="display:none;" onchange="this.form.submit()">
                                </label>
                                <div style="font-size:0.75rem;color:#888;margin-top:6px;">JPG, PNG or WebP</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($adminUser['full_name'] ?? 'Jenny Admin') ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($adminUser['email'] ?? 'admin@jenny.com') ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($adminUser['phone'] ?? '') ?>" placeholder="+92 300 0000000">
                        </div>

                        <button type="submit" class="btn-gold"><i class="fas fa-save"></i> Save Profile</button>
                    </form>
                </div>

                <!-- PASSWORD CHANGE -->
                <div class="settings-section">
                    <div class="settings-section-title"><i class="fas fa-lock"></i> Security & Password</div>
                    <form method="POST">
                        <input type="hidden" name="action" value="change_password">
                        <div class="form-group">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control" required placeholder="Min 6 characters">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn-outline-gold"><i class="fas fa-key"></i> Update Password</button>
                    </form>
                </div>
            </div>

            <!-- SITE CONFIGURATION SECTION -->
            <div>
                <div class="settings-section">
                    <div class="settings-section-title"><i class="fas fa-globe"></i> Website Information</div>
                    <form method="POST">
                        <input type="hidden" name="action" value="update_site_settings">
                        <div class="form-group">
                            <label class="form-label">Store Brand Name</label>
                            <input type="text" name="setting[site_name]" class="form-control" value="<?= htmlspecialchars($settings['site_name'] ?? "Jenny's Cosmetics & Jewelry") ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tagline</label>
                            <input type="text" name="setting[site_tagline]" class="form-control" value="<?= htmlspecialchars($settings['site_tagline'] ?? 'Premium Beauty & Elegance') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Contact Email</label>
                            <input type="email" name="setting[site_email]" class="form-control" value="<?= htmlspecialchars($settings['site_email'] ?? 'admin@jenny.com') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Support Phone</label>
                            <input type="text" name="setting[site_phone]" class="form-control" value="<?= htmlspecialchars($settings['site_phone'] ?? '+92 300 0000000') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">WhatsApp Number</label>
                            <input type="text" name="setting[whatsapp_number]" class="form-control" value="<?= htmlspecialchars($settings['whatsapp_number'] ?? '+923000000000') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Store Currency Symbol</label>
                            <input type="text" name="setting[currency_symbol]" class="form-control" value="<?= htmlspecialchars($settings['currency_symbol'] ?? 'Rs.') ?>">
                        </div>

                        <button type="submit" class="btn-gold"><i class="fas fa-save"></i> Save Site Configuration</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
