<?php
/**
 * profile.php | User Profile & Account Management
 * Supports both Customers & Retailers / Business Owners
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/db.php';
require_once 'auth/session.php';

if (!isLoggedIn()) {
    header("Location: auth/login.php?redirect=profile.php");
    exit();
}

$userId = $_SESSION['user_id'] ?? 0;
$msg = '';
$msgType = '';

// Auto-migration helper for user columns
try {
    $cols = [
        "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `full_name` varchar(150) DEFAULT NULL",
        "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `phone` varchar(20) DEFAULT NULL",
        "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `birthdate` date DEFAULT NULL",
        "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `dob` date DEFAULT NULL",
        "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `address` text DEFAULT NULL",
        "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `city` varchar(100) DEFAULT NULL",
        "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `profile_image` varchar(255) DEFAULT NULL",
        "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `company_name` varchar(150) DEFAULT NULL",
        "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `business_email` varchar(150) DEFAULT NULL",
        "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `tax_id` varchar(100) DEFAULT NULL",
        "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `bio` text DEFAULT NULL",
        "ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `account_type` enum('retail','b2b') DEFAULT 'retail'",
    ];
    foreach ($cols as $sql) {
        try { $pdo->exec($sql); } catch (Exception $e) {}
    }
} catch (Exception $ex) {}

// Fetch current user data from database
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: auth/logout.php");
    exit();
}

// Handle Form POST Requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_profile') {
        $fullName     = trim($_POST['full_name'] ?? '');
        $phone        = trim($_POST['phone'] ?? '');
        $birthdate    = trim($_POST['birthdate'] ?? '');
        $address      = trim($_POST['address'] ?? '');
        $city         = trim($_POST['city'] ?? '');
        $bio          = trim($_POST['bio'] ?? '');
        $accountType  = in_array($_POST['account_type'] ?? '', ['retail','b2b']) ? $_POST['account_type'] : ($user['account_type'] ?? 'retail');
        $companyName  = trim($_POST['company_name'] ?? '');
        $busEmail     = trim($_POST['business_email'] ?? '');
        $taxId        = trim($_POST['tax_id'] ?? '');

        // Avatar Image Upload
        $profileImg = $user['profile_image'] ?? '';
        if (!empty($_FILES['avatar']['name'])) {
            $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            if (in_array($ext, $allowed)) {
                $dir = __DIR__ . '/img/profiles/';
                if (!is_dir($dir)) {
                    @mkdir($dir, 0755, true);
                }
                $fileName = 'user_' . $userId . '_' . time() . '.' . $ext;
                $targetFile = $dir . $fileName;
                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFile)) {
                    $profileImg = $fileName;
                }
            } else {
                $msg = 'Invalid image format. Allowed formats: JPG, PNG, WEBP, GIF.';
                $msgType = 'error';
            }
        }

        if (empty($msg)) {
            try {
                // Determine role tag
                $role = $user['role'];
                if ($role !== 'admin' && $accountType === 'b2b') {
                    $role = 'retailer'; // or customer with b2b
                }

                $updateSql = "UPDATE users SET 
                    full_name = ?, 
                    phone = ?, 
                    birthdate = ?, 
                    address = ?, 
                    city = ?, 
                    bio = ?, 
                    account_type = ?, 
                    company_name = ?, 
                    business_email = ?, 
                    tax_id = ?, 
                    profile_image = ?
                    WHERE id = ?";
                $updateStmt = $pdo->prepare($updateSql);
                $updateStmt->execute([
                    $fullName,
                    $phone,
                    !empty($birthdate) ? $birthdate : null,
                    $address,
                    $city,
                    $bio,
                    $accountType,
                    $companyName,
                    $busEmail,
                    $taxId,
                    $profileImg,
                    $userId
                ]);

                // Refetch user data
                $stmt->execute([$userId]);
                $user = $stmt->fetch();

                // Update session
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];

                $msg = 'Profile updated successfully!';
                $msgType = 'success';
            } catch (PDOException $e) {
                $msg = 'Failed to update profile: ' . $e->getMessage();
                $msgType = 'error';
            }
        }

    } elseif ($action === 'change_password') {
        $currentPass = $_POST['current_password'] ?? '';
        $newPass     = $_POST['new_password'] ?? '';
        $confirmPass = $_POST['confirm_password'] ?? '';

        if (empty($currentPass) || empty($newPass) || empty($confirmPass)) {
            $msg = 'Please fill in all password fields.';
            $msgType = 'error';
        } elseif (!password_verify($currentPass, $user['password'])) {
            $msg = 'Current password is incorrect.';
            $msgType = 'error';
        } elseif (strlen($newPass) < 6) {
            $msg = 'New password must be at least 6 characters long.';
            $msgType = 'error';
        } elseif ($newPass !== $confirmPass) {
            $msg = 'New passwords do not match.';
            $msgType = 'error';
        } else {
            try {
                $newHash = password_hash($newPass, PASSWORD_DEFAULT);
                $pdo->prepare("UPDATE users SET password = ? WHERE id = ?")->execute([$newHash, $userId]);
                $msg = 'Password changed successfully!';
                $msgType = 'success';
            } catch (PDOException $e) {
                $msg = 'Failed to change password.';
                $msgType = 'error';
            }
        }
    }
}

// Fetch user orders if any
$userOrders = [];
try {
    $orderStmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? OR customer_email = ? ORDER BY created_at DESC LIMIT 10");
    $orderStmt->execute([$userId, $user['email']]);
    $userOrders = $orderStmt->fetchAll();
} catch (Exception $ex) {}
?>
<?php require 'includes/header.php'; ?>
<link rel="stylesheet" href="css/style.css">
<?php require 'includes/navbar.php'; ?>

<style>
/* PROFILE PAGE STYLES */
.profile-wrapper {
    background: #f8f9fa;
    padding: 50px 5%;
    min-height: 80vh;
}
.profile-container {
    max-width: 1100px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 30px;
    min-width: 0;
}
@media (max-width: 900px) {
    .profile-container {
        grid-template-columns: 1fr;
    }
}

/* SIDEBAR CARD */
.profile-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 35px 25px;
    text-align: center;
    box-shadow: 0 8px 30px rgba(0,0,0,0.06);
    border: 1px solid rgba(244,180,0,0.15);
    height: fit-content;
    overflow: hidden;
    min-width: 0;
}
.avatar-wrapper {
    position: relative;
    width: 120px;
    height: 120px;
    margin: 0 auto 20px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid var(--primary-gold);
    box-shadow: 0 6px 20px rgba(244,180,0,0.25);
    background: #1a1a1a;
}
.avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.avatar-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    font-weight: 700;
    color: var(--primary-gold);
    background: #111;
}
.avatar-upload-btn {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0,0,0,0.7);
    color: #fff;
    padding: 6px;
    font-size: 0.72rem;
    cursor: pointer;
    transition: 0.3s;
    text-align: center;
}
.avatar-wrapper:hover .avatar-upload-btn {
    background: var(--primary-gold);
    color: #111;
}

.profile-card h3 {
    font-family: var(--font-heading);
    font-size: 1.2rem;
    color: var(--dark-black);
    margin-bottom: 4px;
    word-break: break-all;
    overflow-wrap: break-word;
    white-space: normal;
    padding: 0 5px;
}
.profile-card p.profile-email {
    color: var(--text-grey);
    font-size: 0.8rem;
    margin-bottom: 15px;
    word-break: break-all;
    overflow-wrap: break-word;
    white-space: normal;
    padding: 0 5px;
}
.account-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 16px;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.account-badge.retail {
    background: rgba(244,180,0,0.15);
    color: #d19c00;
    border: 1px solid rgba(244,180,0,0.3);
}
.account-badge.b2b {
    background: linear-gradient(135deg, var(--dark-black), #333);
    color: var(--primary-gold);
    border: 1px solid var(--primary-gold);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.account-badge.admin {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: #fff;
}

.profile-nav {
    margin-top: 30px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    text-align: left;
}
.profile-nav-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 10px;
    color: var(--dark-black);
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.25s ease;
    text-decoration: none;
    border: none;
    background: transparent;
    width: 100%;
}
.profile-nav-item i {
    width: 20px;
    color: var(--primary-gold);
}
.profile-nav-item:hover, .profile-nav-item.active {
    background: rgba(244,180,0,0.12);
    color: var(--dark-black);
}
.profile-nav-item.active i {
    color: #d19c00;
}

/* CONTENT CARD */
.profile-main-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 40px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.06);
    border: 1px solid #eee;
}
.profile-tab-content {
    display: none;
}
.profile-tab-content.active {
    display: block;
    animation: fadeIn 0.4s ease;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.tab-header {
    margin-bottom: 28px;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 14px;
}
.tab-header h2 {
    font-family: var(--font-heading);
    font-size: 1.6rem;
    color: var(--dark-black);
}
.tab-header p {
    color: var(--text-grey);
    font-size: 0.88rem;
    margin-top: 4px;
}

.profile-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
@media (max-width: 600px) {
    .profile-form-grid { grid-template-columns: 1fr; }
}
.form-group-full {
    grid-column: 1 / -1;
}

.form-field {
    margin-bottom: 18px;
}
.form-field label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--dark-black);
    margin-bottom: 6px;
}
.form-field input, .form-field select, .form-field textarea {
    width: 100%;
    padding: 11px 16px;
    border: 1.5px solid #e0e0e0;
    border-radius: 10px;
    font-family: var(--font-body);
    font-size: 0.92rem;
    outline: none;
    transition: all 0.25s ease;
    background: #fff;
}
.form-field input:focus, .form-field select:focus, .form-field textarea:focus {
    border-color: var(--primary-gold);
    box-shadow: 0 0 0 3px rgba(244,180,0,0.15);
}

.btn-save-profile {
    padding: 13px 36px;
    background: var(--primary-gold);
    color: var(--dark-black);
    border: none;
    border-radius: 30px;
    font-weight: 700;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(244,180,0,0.3);
}
.btn-save-profile:hover {
    background: var(--gold-hover);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(244,180,0,0.4);
}

.b2b-highlight-box {
    background: linear-gradient(135deg, rgba(244,180,0,0.08), rgba(244,180,0,0.02));
    border: 1.5px dashed var(--primary-gold);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
}
.b2b-highlight-box h4 {
    color: var(--dark-black);
    font-size: 1rem;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* ORDERS TABLE */
.orders-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}
.orders-table th, .orders-table td {
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid #eee;
    font-size: 0.88rem;
}
.orders-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: var(--dark-black);
}
.status-pill {
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
}
.status-pending { background: #fff3cd; color: #856404; }
.status-completed { background: #d4edda; color: #155724; }
.status-cancelled { background: #f8d7da; color: #721c24; }
</style>

<div class="profile-wrapper">
    <div class="profile-container">
        
        <!-- SIDEBAR -->
        <div class="profile-card">
            <div class="avatar-wrapper">
                <?php 
                $avatarFile = $user['profile_image'] ?? '';
                if ($avatarFile && file_exists(__DIR__ . '/img/profiles/' . $avatarFile)): 
                ?>
                    <img src="img/profiles/<?= htmlspecialchars($avatarFile) ?>" alt="Profile Picture" class="avatar-img">
                <?php else: ?>
                    <div class="avatar-placeholder">
                        <?= strtoupper(substr($user['username'] ?? 'U', 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <label for="avatarUploadInput" class="avatar-upload-btn">
                    <i class="fas fa-camera"></i> Change
                </label>
            </div>

            <h3><?= htmlspecialchars($user['full_name'] ?: $user['username']) ?></h3>
            <p class="profile-email"><?= htmlspecialchars($user['email']) ?></p>

            <?php if (($user['role'] ?? '') === 'admin' || !empty($user['is_admin'])): ?>
                <span class="account-badge admin"><i class="fas fa-crown"></i> Admin Account</span>
            <?php elseif (($user['account_type'] ?? '') === 'b2b' || ($user['role'] ?? '') === 'retailer'): ?>
                <span class="account-badge b2b"><i class="fas fa-store"></i> Retailer / Business</span>
            <?php else: ?>
                <span class="account-badge retail"><i class="fas fa-user"></i> Retail Customer</span>
            <?php endif; ?>

            <div class="profile-nav">
                <button class="profile-nav-item active" onclick="switchProfileTab('personalTab', this)">
                    <i class="fas fa-user-edit"></i> Personal Information
                </button>
                <button class="profile-nav-item" onclick="switchProfileTab('businessTab', this)">
                    <i class="fas fa-building"></i> Business &amp; Retailer Info
                </button>
                <button class="profile-nav-item" onclick="switchProfileTab('ordersTab', this)">
                    <i class="fas fa-shopping-bag"></i> Order History
                </button>
                <button class="profile-nav-item" onclick="switchProfileTab('securityTab', this)">
                    <i class="fas fa-lock"></i> Security &amp; Password
                </button>
                <a href="auth/logout.php" class="profile-nav-item" style="color:#e74c3c;">
                    <i class="fas fa-sign-out-alt" style="color:#e74c3c;"></i> Logout
                </a>
            </div>
        </div>

        <!-- MAIN CONTENT AREA -->
        <div class="profile-main-card">
            
            <?php if ($msg): ?>
            <div style="padding:14px 18px; border-radius:10px; margin-bottom:24px; font-size:0.9rem; display:flex; align-items:center; gap:10px; <?= $msgType==='success'?'background:rgba(46,204,113,0.15);border:1px solid #2ecc71;color:#2ecc71;':'background:rgba(231,76,60,0.15);border:1px solid #e74c3c;color:#e74c3c;' ?>">
                <i class="fas <?= $msgType==='success'?'fa-check-circle':'fa-exclamation-circle' ?>"></i>
                <?= htmlspecialchars($msg) ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="profile.php" enctype="multipart/form-data" id="profileMainForm">
                <input type="hidden" name="action" value="update_profile">
                <input type="file" name="avatar" id="avatarUploadInput" style="display:none;" onchange="submitAvatarForm()">

                <!-- TAB 1: PERSONAL INFO -->
                <div class="profile-tab-content active" id="personalTab">
                    <div class="tab-header">
                        <h2>Personal Information</h2>
                        <p>Manage your account details and contact information.</p>
                    </div>

                    <div class="profile-form-grid">
                        <div class="form-field">
                            <label>Full Name</label>
                            <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" placeholder="Enter your full name">
                        </div>
                        <div class="form-field">
                            <label>Username (Read-only)</label>
                            <input type="text" value="<?= htmlspecialchars($user['username']) ?>" disabled style="background:#f5f5f5;">
                        </div>
                        <div class="form-field">
                            <label>Email Address</label>
                            <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled style="background:#f5f5f5;" title="Contact support to change email">
                        </div>
                        <div class="form-field">
                            <label>Phone Number</label>
                            <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="+92 300 1234567">
                        </div>
                        <div class="form-field">
                            <label>Date of Birth</label>
                            <input type="date" name="birthdate" value="<?= htmlspecialchars($user['birthdate'] ?? ($user['dob'] ?? '')) ?>">
                        </div>
                        <div class="form-field">
                            <label>City</label>
                            <input type="text" name="city" value="<?= htmlspecialchars($user['city'] ?? '') ?>" placeholder="Karachi, Lahore, Islamabad...">
                        </div>
                        <div class="form-field form-group-full">
                            <label>Shipping Address</label>
                            <textarea name="address" rows="3" placeholder="Enter complete delivery address"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                        </div>
                        <div class="form-field form-group-full">
                            <label>Bio / Notes</label>
                            <textarea name="bio" rows="2" placeholder="Tell us a little about yourself"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div style="margin-top:20px;">
                        <button type="submit" class="btn-save-profile">
                            <i class="fas fa-save" style="margin-right:8px;"></i> Save Changes
                        </button>
                    </div>
                </div>

                <!-- TAB 2: BUSINESS & RETAILER INFO -->
                <div class="profile-tab-content" id="businessTab">
                    <div class="tab-header">
                        <h2>Business &amp; Retailer Information</h2>
                        <p>Configure wholesale and business account settings.</p>
                    </div>

                    <div class="b2b-highlight-box">
                        <h4><i class="fas fa-briefcase" style="color:var(--primary-gold);"></i> Account Type</h4>
                        <p style="font-size:0.88rem; color:var(--text-grey); margin-bottom:12px;">Choose whether you are buying as an individual customer or registered retail business owner.</p>
                        
                        <div class="form-field" style="margin-bottom:0;">
                            <select name="account_type" onchange="toggleB2bFields(this.value)">
                                <option value="retail" <?= ($user['account_type']??'retail')==='retail'?'selected':'' ?>><i class="fas fa-shopping-cart"></i> Retail Customer (Standard Shopping)</option>
                                <option value="b2b" <?= ($user['account_type']??'retail')==='b2b'?'selected':'' ?>><i class="fas fa-building"></i> Retailer / Business Owner (Wholesale &amp; Bulk Orders)</option>
                            </select>
                        </div>
                    </div>

                    <div class="profile-form-grid" id="b2bFieldsGroup">
                        <div class="form-field">
                            <label>Company / Store Name</label>
                            <input type="text" name="company_name" value="<?= htmlspecialchars($user['company_name'] ?? '') ?>" placeholder="e.g. Jenny Beauty Salon / Luxe Retail">
                        </div>
                        <div class="form-field">
                            <label>Business Email</label>
                            <input type="email" name="business_email" value="<?= htmlspecialchars($user['business_email'] ?? '') ?>" placeholder="business@company.com">
                        </div>
                        <div class="form-field form-group-full">
                            <label>Tax ID / NTN / GST Registration Number</label>
                            <input type="text" name="tax_id" value="<?= htmlspecialchars($user['tax_id'] ?? '') ?>" placeholder="Optional tax registration ID">
                        </div>
                    </div>

                    <div style="margin-top:20px;">
                        <button type="submit" class="btn-save-profile">
                            <i class="fas fa-save" style="margin-right:8px;"></i> Save Business Profile
                        </button>
                    </div>
                </div>

            </form>

            <!-- TAB 3: ORDERS HISTORY -->
            <div class="profile-tab-content" id="ordersTab">
                <div class="tab-header">
                    <h2>Order History</h2>
                    <p>Track your previous purchases and order statuses.</p>
                </div>

                <?php if (!empty($userOrders)): ?>
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($userOrders as $ord): ?>
                                <tr>
                                    <td><strong>#<?= htmlspecialchars($ord['order_number'] ?? $ord['id']) ?></strong></td>
                                    <td><?= date('M d, Y', strtotime($ord['created_at'])) ?></td>
                                    <td>Rs. <?= number_format($ord['total_amount'] ?? $ord['total'] ?? 0) ?></td>
                                    <td><?= htmlspecialchars($ord['payment_method'] ?? 'COD') ?></td>
                                    <td>
                                        <?php $st = strtolower($ord['status'] ?? 'pending'); ?>
                                        <span class="status-pill status-<?= $st ?>"><?= ucfirst($st) ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div style="text-align:center; padding:50px 20px; color:var(--text-grey);">
                        <i class="fas fa-box-open" style="font-size:3rem; color:#ccc; margin-bottom:12px; display:block;"></i>
                        <h4 style="color:var(--dark-black); font-size:1.1rem; margin-bottom:6px;">No orders found</h4>
                        <p style="font-size:0.88rem; margin-bottom:20px;">You haven't placed any orders yet.</p>
                        <a href="products.php" class="btn-save-profile" style="text-decoration:none; display:inline-block;">Start Shopping</a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- TAB 4: SECURITY & PASSWORD -->
            <div class="profile-tab-content" id="securityTab">
                <div class="tab-header">
                    <h2>Security &amp; Password</h2>
                    <p>Update your password to keep your account secure.</p>
                </div>

                <form method="POST" action="profile.php">
                    <input type="hidden" name="action" value="change_password">

                    <div class="profile-form-grid" style="max-width:500px;">
                        <div class="form-field form-group-full">
                            <label>Current Password</label>
                            <input type="password" name="current_password" required placeholder="Enter current password">
                        </div>
                        <div class="form-field form-group-full">
                            <label>New Password</label>
                            <input type="password" name="new_password" required placeholder="At least 6 characters">
                        </div>
                        <div class="form-field form-group-full">
                            <label>Confirm New Password</label>
                            <input type="password" name="confirm_password" required placeholder="Re-enter new password">
                        </div>
                    </div>

                    <div style="margin-top:20px;">
                        <button type="submit" class="btn-save-profile">
                            <i class="fas fa-key" style="margin-right:8px;"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>
</div>

<script>
function switchProfileTab(tabId, btnEl) {
    document.querySelectorAll('.profile-tab-content').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.profile-nav-item').forEach(btn => btn.classList.remove('active'));

    document.getElementById(tabId).classList.add('active');
    btnEl.classList.add('active');
}

function submitAvatarForm() {
    document.getElementById('profileMainForm').submit();
}

function toggleB2bFields(val) {
    const grp = document.getElementById('b2bFieldsGroup');
    if (grp) {
        grp.style.opacity = val === 'b2b' ? '1' : '0.6';
    }
}
</script>

<?php require 'includes/footer.php'; ?>
