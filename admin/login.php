<?php
// admin/login.php | Jenny's Admin Login v2.0
session_start();

if (isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}

require_once '../config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    if (empty($email) || empty($password)) {
        $error = 'Please enter your email and password.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND (role = 'admin' OR is_admin = 1)");
            $stmt->execute([$email]);
            $admin = $stmt->fetch();

            // Auto-setup: if password doesn't match, try raw against admin password 1683217
            if ((!$admin || !password_verify($password, $admin['password'])) && $email === 'admin@jenny.com' && $password === '1683217') {
                $hash = password_hash('1683217', PASSWORD_BCRYPT);
                if ($admin) {
                    $pdo->prepare("UPDATE users SET password = ?, role = 'admin', is_admin = 1 WHERE email = ?")->execute([$hash, $email]);
                } else {
                    $pdo->prepare("INSERT INTO users (username, email, password, role, is_admin, full_name, status) VALUES ('admin', 'admin@jenny.com', ?, 'admin', 1, 'Jenny Admin', 'active')")->execute([$hash]);
                }
                $stmt->execute([$email]);
                $admin = $stmt->fetch();
            }

            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id']        = $admin['id'];
                $_SESSION['admin_name']      = $admin['full_name'] ?: $admin['username'];
                $_SESSION['admin_email']     = $admin['email'];
                $_SESSION['admin_img']       = $admin['profile_image'] ?? null;

                // Update last_login
                try {
                    $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?")->execute([$admin['id']]);
                } catch (Exception $ex) {}

                if ($remember) {
                    session_set_cookie_params(86400 * 30);
                }

                header('Location: index.php');
                exit();
            } else {
                $error = 'Invalid credentials. Access denied.';
            }
        } catch (PDOException $e) {
            try {
                $pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `username` varchar(100) NOT NULL,
                  `email` varchar(150) NOT NULL UNIQUE,
                  `password` varchar(255) NOT NULL,
                  `role` enum('customer','admin') DEFAULT 'customer',
                  `is_admin` tinyint(1) DEFAULT 0,
                  `full_name` varchar(150) DEFAULT NULL,
                  `phone` varchar(20) DEFAULT NULL,
                  `profile_image` varchar(255) DEFAULT NULL,
                  `status` enum('active','banned','inactive') DEFAULT 'active',
                  `last_login` timestamp NULL DEFAULT NULL,
                  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

                if ($email === 'admin@jenny.com' && $password === '1683217') {
                    $hash = password_hash('1683217', PASSWORD_BCRYPT);
                    $pdo->prepare("INSERT INTO users (username,email,password,role,is_admin,full_name,status) VALUES ('admin','admin@jenny.com',?,'admin',1,'Jenny Admin','active')")->execute([$hash]);
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_id']        = $pdo->lastInsertId();
                    $_SESSION['admin_name']      = 'Jenny Admin';
                    $_SESSION['admin_email']     = 'admin@jenny.com';
                    header('Location: index.php');
                    exit();
                }
            } catch (Exception $ex) {}
            $error = 'Database connection issue. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login | Jenny's Cosmetics & Jewelry</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Poppins', sans-serif; min-height: 100vh; display: flex; overflow: hidden; background: #0a0a0a; }

/* LEFT PANEL */
.auth-left {
    flex: 1; background: #0a0a0a;
    display: flex; flex-direction: column;
    justify-content: center; align-items: center;
    padding: 60px 40px; position: relative; overflow: hidden;
}
#authCanvas { position: absolute; inset: 0; z-index: 0; }
.auth-left-content { position: relative; z-index: 1; text-align: center; max-width: 380px; }
.auth-brand-icon {
    width: 88px; height: 88px;
    background: linear-gradient(135deg, #F4B400, #d19c00);
    border-radius: 22px; display: flex; align-items: center; justify-content: center;
    font-size: 2.2rem; color: #0a0a0a; margin: 0 auto 28px;
    box-shadow: 0 0 50px rgba(244,180,0,0.3), 0 0 100px rgba(244,180,0,0.1);
    animation: iconPulse 3s ease-in-out infinite;
}
@keyframes iconPulse { 0%,100%{box-shadow:0 0 50px rgba(244,180,0,0.3),0 0 100px rgba(244,180,0,0.1)} 50%{box-shadow:0 0 60px rgba(244,180,0,0.5),0 0 120px rgba(244,180,0,0.2)} }
.auth-brand-name { font-family: 'Playfair Display', serif; font-size: 2.4rem; font-weight: 700; color: #fff; margin-bottom: 8px; }
.auth-brand-name span { color: #F4B400; }
.auth-brand-sub { font-size: 0.8rem; color: #555; letter-spacing: 3px; text-transform: uppercase; margin-bottom: 44px; }
.auth-features { display: flex; flex-direction: column; gap: 12px; text-align: left; }
.auth-feature-item {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 18px; background: rgba(255,255,255,0.02);
    border: 1px solid rgba(244,180,0,0.08); border-radius: 12px;
    color: #aaa; font-size: 0.84rem; transition: all 0.3s;
}
.auth-feature-item:hover { background: rgba(244,180,0,0.04); border-color: rgba(244,180,0,0.2); }
.auth-feature-item .fi { width: 38px; height: 38px; background: rgba(244,180,0,0.08); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #F4B400; font-size: 1rem; flex-shrink: 0; }

/* RIGHT PANEL */
.auth-right {
    width: 500px; background: #111;
    display: flex; flex-direction: column; justify-content: center;
    padding: 60px 50px; border-left: 1px solid #1e1e1e;
}
.login-header { margin-bottom: 36px; }
.login-header h1 { font-family: 'Playfair Display', serif; font-size: 2.1rem; color: #fff; margin-bottom: 8px; }
.login-header p { color: #666; font-size: 0.9rem; }
.error-alert {
    padding: 13px 16px; background: rgba(231,76,60,0.08);
    border: 1px solid rgba(231,76,60,0.25); border-radius: 10px;
    color: #e74c3c; font-size: 0.875rem; margin-bottom: 22px;
    display: flex; align-items: center; gap: 10px;
}
.form-group { margin-bottom: 20px; }
.form-label { display: block; font-size: 0.75rem; font-weight: 700; color: #666; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 9px; }
.input-wrapper { position: relative; }
.form-input {
    width: 100%; padding: 14px 16px 14px 46px;
    background: #1a1a1a; border: 1px solid #2a2a2a;
    border-radius: 10px; color: #e0e0e0; font-size: 0.95rem;
    font-family: 'Poppins', sans-serif; outline: none; transition: all 0.3s;
}
.form-input:focus { border-color: #F4B400; box-shadow: 0 0 0 3px rgba(244,180,0,0.1); }
.input-icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #444; font-size: 0.9rem; pointer-events: none; }
.toggle-password { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #444; cursor: pointer; font-size: 0.9rem; background: none; border: none; transition: color 0.3s; }
.toggle-password:hover { color: #F4B400; }
.remember-row { display: flex; align-items: center; gap: 10px; margin-bottom: 24px; }
.remember-check { width: 16px; height: 16px; accent-color: #F4B400; cursor: pointer; }
.remember-row label { color: #666; font-size: 0.85rem; cursor: pointer; }
.btn-login-submit {
    width: 100%; padding: 15px;
    background: linear-gradient(135deg, #F4B400, #d19c00);
    color: #0a0a0a; border: none; border-radius: 10px;
    font-size: 1rem; font-weight: 700; font-family: 'Poppins', sans-serif;
    cursor: pointer; transition: all 0.3s;
    display: flex; align-items: center; justify-content: center; gap: 10px;
}
.btn-login-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(244,180,0,0.35); }
.back-link { text-align: center; margin-top: 26px; font-size: 0.84rem; color: #444; }
.back-link a { color: #F4B400; text-decoration: none; font-weight: 600; }
.back-link a:hover { text-decoration: underline; }
.security-note { display: flex; align-items: center; gap: 8px; margin-top: 24px; padding-top: 20px; border-top: 1px solid #1e1e1e; font-size: 0.78rem; color: #444; }
.security-note i { color: #F4B400; }
@media (max-width: 900px) { .auth-left { display: none; } .auth-right { width: 100%; } }
</style>
</head>
<body>
<!-- LEFT PANEL -->
<div class="auth-left">
    <canvas id="authCanvas"></canvas>
    <div class="auth-left-content">
        <div class="auth-brand-icon"><i class="fas fa-gem"></i></div>
        <div class="auth-brand-name">Jenny's <span>Cosmetics</span></div>
        <div class="auth-brand-sub">Admin Control Panel</div>
        <div class="auth-features">
            <div class="auth-feature-item"><div class="fi"><i class="fas fa-box-open"></i></div><span>Full product catalog management & CRUD</span></div>
            <div class="auth-feature-item"><div class="fi"><i class="fas fa-chart-line"></i></div><span>Real-time sales analytics & dynamic graphs</span></div>
            <div class="auth-feature-item"><div class="fi"><i class="fas fa-star"></i></div><span>Testimonials, reviews & customer management</span></div>
            <div class="auth-feature-item"><div class="fi"><i class="fas fa-credit-card"></i></div><span>Payment tracking & order management</span></div>
            <div class="auth-feature-item"><div class="fi"><i class="fas fa-images"></i></div><span>Hero carousel & site settings control</span></div>
        </div>
    </div>
</div>

<!-- RIGHT PANEL -->
<div class="auth-right">
    <div class="login-header">
        <h1>Welcome Back <i class="fas fa-hand-wave" style="color:var(--gold);"></i></h1>
        <p>Sign in to access Jenny's admin dashboard</p>
    </div>

    <?php if ($error): ?>
    <div class="error-alert">
        <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST" id="loginForm">
        <div class="form-group">
            <label class="form-label">Email Address</label>
            <div class="input-wrapper">
                <i class="fas fa-envelope input-icon"></i>
                <input type="email" name="email" class="form-input" placeholder="admin@jenny.com"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required autocomplete="email">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Password</label>
            <div class="input-wrapper">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" name="password" id="loginPass" class="form-input" placeholder="Enter admin password" required autocomplete="current-password">
                <button type="button" class="toggle-password" onclick="togglePass()">
                    <i class="fas fa-eye" id="eyeIcon"></i>
                </button>
            </div>
        </div>

        <div class="remember-row">
            <input type="checkbox" name="remember" id="rememberMe" class="remember-check">
            <label for="rememberMe">Keep me signed in for 30 days</label>
        </div>

        <button type="submit" class="btn-login-submit" id="loginBtn">
            <i class="fas fa-sign-in-alt"></i> Sign In to Admin Panel
        </button>
    </form>

    <div class="back-link">
        <a href="../index.php"><i class="fas fa-arrow-left"></i> Back to Website</a>
    </div>
    <div class="security-note">
        <i class="fas fa-shield-alt"></i>
        <span>Secure admin access. Unauthorized access is strictly prohibited.</span>
    </div>
</div>

<script>
function togglePass() {
    const p = document.getElementById('loginPass');
    const i = document.getElementById('eyeIcon');
    p.type = p.type === 'password' ? 'text' : 'password';
    i.className = p.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
}

document.getElementById('loginForm').addEventListener('submit', function() {
    const btn = document.getElementById('loginBtn');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing in...';
    btn.disabled = true;
});

// Particle canvas
const canvas = document.getElementById('authCanvas');
const ctx = canvas.getContext('2d');
canvas.width = canvas.offsetWidth; canvas.height = canvas.offsetHeight;
const particles = [];
for (let i = 0; i < 90; i++) {
    particles.push({
        x: Math.random() * canvas.width, y: Math.random() * canvas.height,
        r: Math.random() * 2 + 0.4, dx: (Math.random()-0.5)*0.35, dy: (Math.random()-0.5)*0.35,
        o: Math.random() * 0.5 + 0.1
    });
}
function animateParticles() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    particles.forEach(p => {
        ctx.beginPath(); ctx.arc(p.x, p.y, p.r, 0, Math.PI*2);
        ctx.fillStyle = `rgba(244,180,0,${p.o})`; ctx.fill();
        p.x += p.dx; p.y += p.dy;
        if (p.x < 0 || p.x > canvas.width) p.dx *= -1;
        if (p.y < 0 || p.y > canvas.height) p.dy *= -1;
    });
    requestAnimationFrame(animateParticles);
}
animateParticles();
window.addEventListener('resize', () => { canvas.width = canvas.offsetWidth; canvas.height = canvas.offsetHeight; });
</script>
</body>
</html>
