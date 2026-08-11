<?php
/**
 * admin-setup.php | One-time Admin Setup & Password Reset
 * DELETE THIS FILE after you've logged in successfully!
 * Access: http://localhost/jenny/admin-setup.php
 */

// Simple security token | change this if you want
define('SETUP_TOKEN', 'jenny_setup_2024');

require_once 'config/db.php';

$message = '';
$msgType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token    = $_POST['token'] ?? '';
    $email    = trim($_POST['email'] ?? 'admin@jenny.com');
    $username = trim($_POST['username'] ?? 'admin');
    $password = $_POST['password'] ?? 'admin123';

    if ($token !== SETUP_TOKEN) {
        $message = '<i class="fas fa-times-circle"></i> Invalid security token.';
        $msgType = 'error';
    } elseif (strlen($password) < 6) {
        $message = '<i class="fas fa-times-circle"></i> Password must be at least 6 characters.';
        $msgType = 'error';
    } else {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        try {
            // Check if admin exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $existing = $stmt->fetch();

            if ($existing) {
                // Update existing admin
                $pdo->prepare("UPDATE users SET password = ?, username = ?, role = 'admin', is_admin = 1 WHERE email = ?")
                    ->execute([$hash, $username, $email]);
                $message = "<i class="fas fa-check-circle"></i> Admin password updated! Email: <strong>$email</strong> | Password: <strong>$password</strong>";
            } else {
                // Create new admin
                $pdo->prepare("INSERT INTO users (username, email, password, role, is_admin) VALUES (?, ?, ?, 'admin', 1)")
                    ->execute([$username, $email, $hash]);
                $message = "<i class="fas fa-check-circle"></i> Admin created! Email: <strong>$email</strong> | Password: <strong>$password</strong>";
            }
            $msgType = 'success';
        } catch (PDOException $e) {
            $message = '<i class="fas fa-times-circle"></i> Database error: ' . $e->getMessage();
            $msgType = 'error';
        }
    }
}

// Also ensure all tables exist
try {
    $sqlPath = __DIR__ . '/config/setup.sql';
    if (file_exists($sqlPath)) {
        $sqlContent = file_get_contents($sqlPath);
        // Run each statement
        $statements = array_filter(array_map('trim', explode(';', $sqlContent)));
        foreach ($statements as $stmt) {
            if (!empty($stmt)) {
                try { $pdo->exec($stmt); } catch (PDOException $e) { /* ignore */ }
            }
        }
        $dbStatus = '<i class="fas fa-check-circle"></i> Database tables verified/created';
    } else {
        $dbStatus = '<i class="fas fa-exclamation-triangle"></i> setup.sql not found';
    }
} catch (Exception $e) {
    $dbStatus = '<i class="fas fa-times-circle"></i> DB Error: ' . $e->getMessage();
}

// Check current admin users
$admins = [];
try {
    $admins = $pdo->query("SELECT id, username, email, role, is_admin FROM users WHERE role='admin' OR is_admin=1")->fetchAll();
} catch (Exception $e) {}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Setup | Jenny's Cosmetics</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #0a0a0a 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.setup-card {
    background: #111;
    border: 1px solid #333;
    border-radius: 20px;
    padding: 40px;
    width: 100%;
    max-width: 560px;
    box-shadow: 0 25px 60px rgba(0,0,0,0.5);
}
.logo { text-align: center; margin-bottom: 30px; }
.logo h1 { font-size: 1.8rem; color: #F4B400; font-weight: 700; }
.logo p { color: #888; font-size: 0.9rem; margin-top: 5px; }
.status-box {
    background: #1a1a1a;
    border-radius: 12px;
    padding: 15px 20px;
    margin-bottom: 25px;
    border-left: 4px solid #F4B400;
    font-size: 0.88rem;
    color: #ccc;
}
.alert {
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    font-size: 0.92rem;
    font-weight: 500;
}
.alert.success { background: rgba(46,204,113,0.15); border: 1px solid rgba(46,204,113,0.3); color: #2ecc71; }
.alert.error { background: rgba(231,76,60,0.15); border: 1px solid rgba(231,76,60,0.3); color: #e74c3c; }
.form-group { margin-bottom: 18px; }
label { display: block; font-size: 0.85rem; color: #aaa; font-weight: 500; margin-bottom: 6px; }
input {
    width: 100%;
    background: #1a1a1a;
    border: 1px solid #333;
    border-radius: 10px;
    padding: 12px 16px;
    color: #fff;
    font-family: 'Poppins', sans-serif;
    font-size: 0.95rem;
    outline: none;
    transition: border-color 0.3s;
}
input:focus { border-color: #F4B400; }
.btn-setup {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #F4B400, #e6a800);
    color: #000;
    border: none;
    border-radius: 12px;
    font-family: 'Poppins', sans-serif;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    margin-top: 8px;
    transition: transform 0.2s, box-shadow 0.2s;
}
.btn-setup:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(244,180,0,0.3); }
.admins-table { width: 100%; margin-top: 25px; }
.admins-table h3 { color: #F4B400; font-size: 0.95rem; margin-bottom: 12px; }
table { width: 100%; border-collapse: collapse; font-size: 0.83rem; }
th { background: #1a1a1a; color: #888; font-weight: 600; padding: 8px 12px; text-align: left; }
td { color: #ccc; padding: 8px 12px; border-bottom: 1px solid #222; }
.login-link { text-align: center; margin-top: 25px; }
.login-link a {
    color: #F4B400;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 600;
    padding: 10px 24px;
    border: 1px solid #F4B400;
    border-radius: 30px;
    display: inline-block;
    transition: all 0.3s;
}
.login-link a:hover { background: #F4B400; color: #000; }
.warning {
    background: rgba(231,76,60,0.1);
    border: 1px solid rgba(231,76,60,0.3);
    border-radius: 10px;
    padding: 12px 16px;
    font-size: 0.82rem;
    color: #e74c3c;
    margin-top: 20px;
    text-align: center;
}
</style>
</head>
<body>
<div class="setup-card">
    <div class="logo">
        <h1><i class="fas fa-cogs"></i> Admin Setup</h1>
        <p>Jenny's Cosmetics | One-time setup utility</p>
    </div>

    <div class="status-box">
        <?php echo $dbStatus ?? 'Checking database...'; ?>
    </div>

    <?php if ($message): ?>
    <div class="alert <?= $msgType ?>">
        <?= $message ?>
        <?php if ($msgType === 'success'): ?>
        <br><br><i class="fas fa-arrow-right"></i> <a href="admin/login.php" style="color:inherit;font-weight:700;">Go to Admin Login</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Security Token</label>
            <input type="text" name="token" value="jenny_setup_2024" required placeholder="Enter setup token">
        </div>
        <div class="form-group">
            <label>Admin Email</label>
            <input type="email" name="email" value="admin@jenny.com" required>
        </div>
        <div class="form-group">
            <label>Admin Username</label>
            <input type="text" name="username" value="admin" required>
        </div>
        <div class="form-group">
            <label>Admin Password (min 6 chars)</label>
            <input type="text" name="password" value="admin123" required>
        </div>
        <button type="submit" class="btn-setup"><i class="fas fa-shield-alt"></i> Create / Reset Admin</button>
    </form>

    <?php if (!empty($admins)): ?>
    <div class="admins-table">
        <h3>Current Admin Users</h3>
        <table>
            <tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th></tr>
            <?php foreach ($admins as $a): ?>
            <tr>
                <td><?= $a['id'] ?></td>
                <td><?= htmlspecialchars($a['username']) ?></td>
                <td><?= htmlspecialchars($a['email']) ?></td>
                <td><?= htmlspecialchars($a['role']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php endif; ?>

    <div class="login-link">
        <a href="admin/login.php">→ Go to Admin Login</a>
    </div>

    <div class="warning">
        <i class="fas fa-exclamation-triangle"></i> Delete this file after setup! <code>admin-setup.php</code>
    </div>
</div>
</body>
</html>
