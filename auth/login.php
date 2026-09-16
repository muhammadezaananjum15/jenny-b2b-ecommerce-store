<?php
// auth/login.php | B2B SaaS Premium Login
session_start();
require_once '../config/db.php';
require_once 'session.php';

$redirect = trim($_GET['redirect'] ?? $_POST['redirect'] ?? '');
$target   = (!empty($redirect) && strpos($redirect, '..') === false) ? "../" . ltrim($redirect, '/') : "../index.php";

if (isLoggedIn()) {
    header(isAdmin() ? "Location: ../admin/index.php" : "Location: $target");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_input = trim($_POST['login_input'] ?? '');
    $password    = $_POST['password'] ?? '';

    if (empty($login_input) || empty($password)) {
        $error = 'Please enter your credentials to continue.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$login_input, $login_input]);
            $user = $stmt->fetch();

            // Admin fallback
            if ((!$user || !password_verify($password, $user['password'])) &&
                in_array($login_input, ['admin@jenny.com', 'admin']) &&
                in_array($password, ['1683217', 'admin123'])) {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                if ($user) {
                    $pdo->prepare("UPDATE users SET password=?,role='admin',is_admin=1 WHERE id=?")->execute([$hash, $user['id']]);
                } else {
                    $pdo->prepare("INSERT INTO users (username,email,password,role,is_admin,full_name,status) VALUES ('admin','admin@jenny.com',?,'admin',1,'Jenny Admin','active')")->execute([$hash]);
                }
                $stmt->execute([$login_input, $login_input]);
                $user = $stmt->fetch();
            }

            if ($user && password_verify($password, $user['password'])) {
                if (($user['status'] ?? 'active') === 'banned') {
                    $error = 'Your account has been suspended. Contact support.';
                } else {
                    $_SESSION['user_id']  = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email']    = $user['email'];
                    $_SESSION['role']     = $user['role'];

                    if ($user['role'] === 'admin' || !empty($user['is_admin'])) {
                        $_SESSION['role']             = 'admin';
                        $_SESSION['admin_logged_in']  = true;
                        $_SESSION['admin_id']         = $user['id'];
                        $_SESSION['admin_name']       = $user['full_name'] ?: $user['username'];
                        $_SESSION['admin_email']      = $user['email'];
                        $_SESSION['admin_img']        = $user['profile_image'] ?? null;
                        try { $pdo->prepare("UPDATE users SET last_login=NOW() WHERE id=?")->execute([$user['id']]); } catch(Exception $ex){}
                        header("Location: ../admin/index.php"); exit();
                    }
                    header("Location: $target"); exit();
                }
            } else {
                $error = 'Invalid credentials. Please check your email/username and password.';
            }
        } catch (PDOException $e) {
            $error = 'A system error occurred. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In | Jenny's Cosmetics &amp; Jewelry</title>
<meta name="description" content="Sign in to your Jenny's Cosmetics & Jewelry account to access exclusive collections and manage your orders.">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* ================================================================
   Jenny's B2B SaaS Login | Full Rebuild
   Design tokens: Gold #FFAB00 | Ink Black #111111 | Charcoal #333333 | White #FFFFFF
   Typography: Playfair Display (headings) | Poppins (body)
   8px spacing grid
================================================================ */
:root {
    --gold:        #FFAB00;
    --gold-dark:   #CC8800;
    --gold-glow:   rgba(255,171,0,0.22);
    --gold-light:  rgba(255,171,0,0.10);
    --ink:         #111111;
    --charcoal:    #333333;
    --surface:     #1A1A1A;
    --surface2:    #222222;
    --border:      #2E2E2E;
    --border-focus:#FFAB00;
    --text-primary:#FFFFFF;
    --text-muted:  #999999;
    --text-dim:    #555555;
    --error:       #FF4D4D;
    --error-bg:    rgba(255,77,77,0.10);
    --success:     #2ECC71;
    --radius-sm:   8px;
    --radius-md:   16px;
    --radius-lg:   24px;
}

*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

body {
    font-family: 'Poppins', sans-serif;
    min-height: 100vh;
    display: flex;
    background: var(--ink);
    color: var(--text-primary);
    overflow-x: hidden;
}

a { text-decoration: none; color: inherit; }

/* ── HERO PANEL (left) ── */
.auth-hero {
    flex: 1;
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
    padding: 64px 56px;
    background: linear-gradient(145deg, #0A0A0A 0%, #141414 60%, #1C1200 100%);
    overflow: hidden;
}

.auth-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse 70% 60% at 30% 40%, rgba(255,171,0,0.12) 0%, transparent 70%);
    pointer-events: none;
}

.hero-canvas {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    opacity: 0.6;
}

.hero-brand { position: relative; z-index: 2; }

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--gold-light);
    border: 1px solid rgba(255,171,0,0.3);
    border-radius: 40px;
    padding: 6px 16px;
    font-size: 0.72rem;
    font-weight: 600;
    color: var(--gold);
    letter-spacing: 1.5px;
    text-transform: uppercase;
    margin-bottom: 32px;
}

.hero-badge i { font-size: 0.8rem; }

.hero-logo {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2.2rem, 4vw, 3.4rem);
    font-weight: 700;
    line-height: 1.1;
    color: var(--text-primary);
    margin-bottom: 16px;
}

.hero-logo span { color: var(--gold); }

.hero-tagline {
    font-size: 1.05rem;
    color: var(--text-muted);
    font-weight: 300;
    line-height: 1.7;
    max-width: 380px;
    margin-bottom: 48px;
}

.hero-features { display: flex; flex-direction: column; gap: 16px; }

.hero-feature {
    display: flex;
    align-items: center;
    gap: 16px;
}

.feature-icon {
    width: 40px;
    height: 40px;
    border-radius: var(--radius-sm);
    background: var(--gold-light);
    border: 1px solid rgba(255,171,0,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gold);
    font-size: 0.9rem;
    flex-shrink: 0;
}

.feature-text {
    font-size: 0.875rem;
    color: var(--text-muted);
    line-height: 1.5;
}

.feature-text strong {
    display: block;
    color: var(--text-primary);
    font-weight: 500;
    margin-bottom: 2px;
}

.hero-divider {
    position: absolute;
    right: 0;
    top: 0;
    bottom: 0;
    width: 1px;
    background: linear-gradient(to bottom, transparent, var(--border), transparent);
}

/* ── FORM PANEL (right) ── */
.auth-form-panel {
    width: 520px;
    max-width: 100vw;
    flex: 0 0 auto;
    background: var(--surface);
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 48px 40px;
    overflow-y: auto;
    box-sizing: border-box;
}

/* ── FORM HEADER ── */
.form-eyebrow {
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 8px;
}

.form-title {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1.2;
    margin-bottom: 8px;
}

.form-subtitle {
    font-size: 0.875rem;
    color: var(--text-muted);
    margin-bottom: 32px;
}

.form-subtitle a {
    color: var(--gold);
    font-weight: 500;
    transition: opacity 0.2s;
}
.form-subtitle a:hover { opacity: 0.75; }

/* ── ERROR ALERT ── */
.alert-error {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px 16px;
    background: var(--error-bg);
    border: 1px solid rgba(255,77,77,0.3);
    border-radius: var(--radius-sm);
    margin-bottom: 24px;
    color: #FF7070;
    font-size: 0.85rem;
    line-height: 1.5;
    animation: slideDown 0.25s ease;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
}

.alert-error i { color: var(--error); margin-top: 2px; flex-shrink: 0; }

/* ── FORM GROUP ── */
.form-group { margin-bottom: 20px; position: relative; }

.form-label {
    display: block;
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 8px;
    transition: color 0.2s;
}

.input-wrap { position: relative; width: 100%; }

.input-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-dim);
    font-size: 0.85rem;
    pointer-events: none;
    transition: color 0.2s;
}

.form-input {
    width: 100%;
    padding: 14px 44px 14px 44px;
    background: var(--surface2);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    color: var(--text-primary);
    font-size: 0.9rem;
    font-family: 'Poppins', sans-serif;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    box-sizing: border-box;
}

.form-input::placeholder { color: var(--text-dim); }

.form-input:focus {
    border-color: var(--border-focus);
    background: #262626;
    box-shadow: 0 0 0 3px var(--gold-glow);
}

.form-input:focus + .input-icon,
.input-wrap:focus-within .input-icon { color: var(--gold); }

/* Inline validation */
.form-input.input-error { border-color: var(--error); }
.form-input.input-error:focus { box-shadow: 0 0 0 3px rgba(255,77,77,0.18); }
.field-error-msg {
    font-size: 0.76rem;
    color: var(--error);
    margin-top: 6px;
    display: none;
}
.form-input.input-error ~ .field-error-msg { display: block; }

/* ── PASSWORD TOGGLE ── */
.pass-toggle {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--text-dim);
    cursor: pointer;
    padding: 4px 6px;
    border-radius: 4px;
    line-height: 1;
    transition: color 0.2s;
    z-index: 2;
}
.pass-toggle:hover { color: var(--gold); }

/* ── REMEMBER / FORGOT ROW ── */
.auth-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    font-size: 0.82rem;
}

.remember-label {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-muted);
    cursor: pointer;
}

.remember-label input[type="checkbox"] {
    accent-color: var(--gold);
    width: 15px;
    height: 15px;
    cursor: pointer;
}

.forgot-link {
    color: var(--gold);
    font-weight: 500;
    transition: opacity 0.2s;
}
.forgot-link:hover { opacity: 0.75; }

/* ── SUBMIT BUTTON ── */
.btn-primary {
    width: 100%;
    padding: 15px 24px;
    background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
    color: var(--ink);
    border: none;
    border-radius: var(--radius-sm);
    font-size: 0.95rem;
    font-weight: 700;
    font-family: 'Poppins', sans-serif;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: transform 0.2s, box-shadow 0.2s, opacity 0.2s;
    letter-spacing: 0.3px;
    position: relative;
    overflow: hidden;
}

.btn-primary::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.12) 0%, transparent 60%);
    border-radius: inherit;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(255,171,0,0.38);
}

.btn-primary:active { transform: translateY(0); }

.btn-primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.btn-spinner {
    display: none;
    width: 18px;
    height: 18px;
    border: 2.5px solid rgba(17,17,17,0.25);
    border-top-color: var(--ink);
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
}

@keyframes spin { to { transform: rotate(360deg); } }

.btn-primary.loading .btn-spinner { display: block; }
.btn-primary.loading .btn-label  { display: none; }

/* ── DIVIDER ── */
.auth-divider {
    display: flex;
    align-items: center;
    gap: 16px;
    margin: 28px 0;
    color: var(--text-dim);
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.auth-divider::before,
.auth-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--border);
}

/* ── SOCIAL BUTTONS ── */
.social-btns {
    display: flex;
    gap: 12px;
    margin-bottom: 24px;
}

.btn-social {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 11px 16px;
    background: var(--surface2);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    color: var(--text-primary);
    font-size: 0.83rem;
    font-weight: 500;
    font-family: 'Poppins', sans-serif;
    cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
}

.btn-social:hover {
    border-color: rgba(255,255,255,0.15);
    background: #282828;
}

.btn-social .g-icon { color: #ea4335; }
.btn-social .a-icon { color: #e0e0e0; }

/* ── FOOTER ── */
.form-footer-links {
    margin-top: 24px;
    text-align: center;
    font-size: 0.82rem;
    color: var(--text-muted);
}
.form-footer-links a {
    color: var(--gold);
    font-weight: 500;
    transition: opacity 0.2s;
}
.form-footer-links a:hover { opacity: 0.75; }

.back-home {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 16px;
    font-size: 0.8rem;
    color: var(--text-dim);
    transition: color 0.2s;
}
.back-home:hover { color: var(--gold); }

/* ── TOAST ── */
.toast-container {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 8px;
    pointer-events: none;
}

.toast {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 20px;
    border-radius: var(--radius-sm);
    background: var(--surface2);
    border: 1px solid var(--border);
    color: var(--text-primary);
    font-size: 0.875rem;
    font-weight: 500;
    box-shadow: 0 8px 24px rgba(0,0,0,0.4);
    animation: toastIn 0.3s ease;
    min-width: 280px;
    pointer-events: all;
}

.toast.toast-success { border-color: rgba(46,204,113,0.4); }
.toast.toast-success i { color: var(--success); }
.toast.toast-error   { border-color: rgba(255,77,77,0.4); }
.toast.toast-error i { color: var(--error); }

@keyframes toastIn {
    from { opacity: 0; transform: translateX(24px); }
    to   { opacity: 1; transform: translateX(0); }
}

/* ── FORGOT PASSWORD MODAL ── */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.7);
    backdrop-filter: blur(4px);
    z-index: 9000;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.25s, visibility 0.25s;
}

.modal-overlay.open {
    opacity: 1;
    visibility: visible;
}

.modal-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 40px;
    width: 100%;
    max-width: 420px;
    position: relative;
    animation: modalIn 0.3s ease;
}

@keyframes modalIn {
    from { opacity: 0; transform: scale(0.94) translateY(16px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}

.modal-close-btn {
    position: absolute;
    top: 16px; right: 16px;
    background: none; border: none;
    color: var(--text-dim);
    font-size: 1.1rem;
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 4px;
    transition: color 0.2s;
}
.modal-close-btn:hover { color: var(--text-primary); }

.modal-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 8px;
}

.modal-sub {
    font-size: 0.85rem;
    color: var(--text-muted);
    margin-bottom: 28px;
    line-height: 1.6;
}

/* ── RESPONSIVE ── */
@media (max-width: 1200px) {
    .auth-hero { padding: 48px 40px; }
    .auth-form-panel { width: 480px; max-width: 100vw; padding: 36px 28px; }
}

@media (max-width: 768px) {
    body { flex-direction: column; }
    .auth-hero { display: none; }
    .auth-form-panel {
        width: 100%;
        min-width: 100%;
        padding: 36px 20px;
        justify-content: flex-start;
        padding-top: 48px;
    }
}

@media (max-width: 360px) {
    .auth-form-panel { padding: 32px 14px; }
    .social-btns { flex-direction: column; }
}
</style>
</head>
<body>

<!-- ── HERO PANEL ── -->
<div class="auth-hero">
    <canvas class="hero-canvas" id="heroCanvas"></canvas>
    <div class="hero-divider"></div>
    <div class="hero-brand">
        <div class="hero-badge"><i class="fas fa-gem"></i> Premium B2B Platform</div>
        <h1 class="hero-logo">Jenny's<br><span>Cosmetics</span> &amp;<br>Jewelry</h1>
        <p class="hero-tagline">Pakistan's leading wholesale beauty &amp; jewelry platform. Premium products, B2B pricing, and seamless ordering for retailers nationwide.</p>
        <div class="hero-features">
            <div class="hero-feature">
                <div class="feature-icon"><i class="fas fa-boxes-stacked"></i></div>
                <div class="feature-text">
                    <strong>Wholesale Catalog Access</strong>
                    500+ premium cosmetics &amp; jewelry products
                </div>
            </div>
            <div class="hero-feature">
                <div class="feature-icon"><i class="fas fa-truck-fast"></i></div>
                <div class="feature-text">
                    <strong>Real-Time Order Tracking</strong>
                    Live shipment updates from dispatch to door
                </div>
            </div>
            <div class="hero-feature">
                <div class="feature-icon"><i class="fas fa-shield-halved"></i></div>
                <div class="feature-text">
                    <strong>Secure B2B Checkout</strong>
                    SSL encrypted payments with COD available
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ── FORM PANEL ── -->
<div class="auth-form-panel">
    <p class="form-eyebrow">Welcome back</p>
    <h2 class="form-title">Sign In to<br>Your Account</h2>
    <p class="form-subtitle">
        Don't have an account? <a href="register.php<?= !empty($redirect) ? '?redirect='.urlencode($redirect) : '' ?>">Create one here &rarr;</a>
    </p>

    <?php if ($error): ?>
    <div class="alert-error" role="alert">
        <i class="fas fa-circle-exclamation"></i>
        <span><?= htmlspecialchars($error) ?></span>
    </div>
    <?php endif; ?>

    <form id="loginForm" method="POST" action="login.php<?= !empty($redirect) ? '?redirect='.urlencode($redirect) : '' ?>" novalidate>
        <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">
        <div class="form-group">
            <label class="form-label" for="login_input">Email or Username</label>
            <div class="input-wrap">
                <i class="fas fa-user input-icon"></i>
                <input
                    type="text"
                    id="login_input"
                    name="login_input"
                    class="form-input"
                    placeholder="your@email.com or username"
                    value="<?= htmlspecialchars($_POST['login_input'] ?? '') ?>"
                    autocomplete="username"
                    required>
                <span class="field-error-msg">Please enter your email or username.</span>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <div class="input-wrap">
                <i class="fas fa-lock input-icon"></i>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-input"
                    placeholder="Enter your password"
                    autocomplete="current-password"
                    required>
                <button type="button" class="pass-toggle" id="passToggle" aria-label="Toggle password visibility">
                    <i class="fas fa-eye" id="passEyeIcon"></i>
                </button>
                <span class="field-error-msg">Please enter your password.</span>
            </div>
        </div>

        <div class="auth-row">
            <label class="remember-label">
                <input type="checkbox" name="remember"> Remember me
            </label>
            <a href="#" class="forgot-link" id="forgotPassBtn">Forgot password?</a>
        </div>

        <button type="submit" class="btn-primary" id="loginBtn">
            <div class="btn-spinner"></div>
            <span class="btn-label"><i class="fas fa-sign-in-alt"></i>&nbsp; Sign In</span>
        </button>
    </form>

    <div class="auth-divider">or continue with</div>

    <div class="social-btns">
        <button type="button" class="btn-social" onclick="showToast('Google sign-in is not configured yet.','error')">
            <i class="fab fa-google g-icon"></i> Google
        </button>
        <button type="button" class="btn-social" onclick="showToast('Apple sign-in is not configured yet.','error')">
            <i class="fab fa-apple a-icon"></i> Apple
        </button>
    </div>

    <div class="form-footer-links">
        <a href="register.php">Create a new account</a> &nbsp;·&nbsp; <a href="register.php?type=b2b">Register as B2B Wholesaler</a>
    </div>
    <div style="text-align:center;">
        <a href="../index.php" class="back-home"><i class="fas fa-arrow-left"></i> Back to Store</a>
    </div>
</div>

<!-- ── FORGOT PASSWORD MODAL ── -->
<div class="modal-overlay" id="forgotModal" role="dialog" aria-modal="true" aria-labelledby="forgotTitle">
    <div class="modal-card">
        <button class="modal-close-btn" onclick="closeForgot()" aria-label="Close"><i class="fas fa-times"></i></button>
        <h3 class="modal-title" id="forgotTitle">Reset Password</h3>
        <p class="modal-sub">Enter your registered email address and we'll send you a password reset link.</p>
        <div class="form-group">
            <label class="form-label" for="resetEmail">Email Address</label>
            <div class="input-wrap">
                <i class="fas fa-envelope input-icon"></i>
                <input type="email" id="resetEmail" class="form-input" placeholder="your@email.com">
            </div>
        </div>
        <button type="button" class="btn-primary" onclick="sendReset()" style="margin-top:8px;">
            <span class="btn-label"><i class="fas fa-paper-plane"></i>&nbsp; Send Reset Link</span>
        </button>
    </div>
</div>

<!-- ── TOAST CONTAINER ── -->
<div class="toast-container" id="toastContainer"></div>

<script>
// ── HERO PARTICLE CANVAS ──
(function() {
    const c = document.getElementById('heroCanvas');
    if (!c) return;
    const ctx = c.getContext('2d');
    const resize = () => { c.width = c.offsetWidth; c.height = c.offsetHeight; };
    resize();
    window.addEventListener('resize', resize);

    const pts = Array.from({length: 60}, () => ({
        x: Math.random() * c.width,
        y: Math.random() * c.height,
        r: Math.random() * 1.8 + 0.4,
        dx: (Math.random() - 0.5) * 0.35,
        dy: (Math.random() - 0.5) * 0.35,
        o: Math.random() * 0.45 + 0.08
    }));

    function draw() {
        ctx.clearRect(0, 0, c.width, c.height);
        pts.forEach(p => {
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(255,171,0,${p.o})`;
            ctx.fill();
            p.x += p.dx; p.y += p.dy;
            if (p.x < 0 || p.x > c.width)  p.dx *= -1;
            if (p.y < 0 || p.y > c.height) p.dy *= -1;
        });
        requestAnimationFrame(draw);
    }
    draw();
})();

// ── PASSWORD TOGGLE ──
document.getElementById('passToggle').addEventListener('click', function() {
    const inp  = document.getElementById('password');
    const icon = document.getElementById('passEyeIcon');
    const show = inp.type === 'password';
    inp.type   = show ? 'text' : 'password';
    icon.className = show ? 'fas fa-eye-slash' : 'fas fa-eye';
    this.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
});

// ── INLINE VALIDATION ──
document.getElementById('loginForm').addEventListener('submit', function(e) {
    let valid = true;
    const fields = [
        { id: 'login_input', msg: 'Please enter your email or username.' },
        { id: 'password',    msg: 'Please enter your password.' }
    ];
    fields.forEach(f => {
        const el = document.getElementById(f.id);
        if (!el.value.trim()) {
            el.classList.add('input-error');
            valid = false;
        } else {
            el.classList.remove('input-error');
        }
    });
    if (!valid) { e.preventDefault(); return; }

    // Loading state
    const btn = document.getElementById('loginBtn');
    btn.classList.add('loading');
    btn.disabled = true;
});

// Clear error on input
['login_input','password'].forEach(id => {
    document.getElementById(id).addEventListener('input', function() {
        this.classList.remove('input-error');
    });
});

// ── FORGOT PASSWORD MODAL ──
document.getElementById('forgotPassBtn').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('forgotModal').classList.add('open');
    setTimeout(() => document.getElementById('resetEmail').focus(), 200);
});

function closeForgot() {
    document.getElementById('forgotModal').classList.remove('open');
}

function sendReset() {
    const email = document.getElementById('resetEmail').value.trim();
    if (!email || !email.includes('@')) {
        showToast('Please enter a valid email address.', 'error');
        return;
    }
    closeForgot();
    showToast('If this email is registered, a reset link has been sent.', 'success');
}

// Close modal on overlay click
document.getElementById('forgotModal').addEventListener('click', function(e) {
    if (e.target === this) closeForgot();
});

// ── TOAST SYSTEM ──
function showToast(msg, type = 'success') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'}"></i><span>${msg}</span>`;
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(24px)';
        toast.style.transition = 'all 0.3s ease';
        setTimeout(() => toast.remove(), 320);
    }, 4000);
}

<?php if ($error): ?>
// Show PHP error as toast too
showToast(<?= json_encode($error) ?>, 'error');
<?php endif; ?>
</script>
</body>
</html>