<?php
// auth/register.php — B2B SaaS Premium Registration
session_start();
require_once '../config/db.php';
require_once 'session.php';

if (isLoggedIn()) {
    header(isAdmin() ? "Location: ../admin/index.php" : "Location: ../index.php");
    exit();
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account_type    = in_array($_POST['account_type'] ?? '', ['retail','b2b']) ? $_POST['account_type'] : 'retail';
    $username        = trim($_POST['username']        ?? '');
    $email           = trim($_POST['email']           ?? '');
    $password        = $_POST['password']             ?? '';
    $confirm_pw      = $_POST['confirm_password']     ?? '';
    $company_name    = trim($_POST['company_name']    ?? '');
    $business_email  = trim($_POST['business_email']  ?? '');
    $phone           = trim($_POST['phone']           ?? '');
    $tax_id          = trim($_POST['tax_id']          ?? '');

    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_pw)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm_pw) {
        $error = 'Passwords do not match.';
    } elseif ($account_type === 'b2b' && empty($company_name)) {
        $error = 'Company name is required for B2B registration.';
    } else {
        try {
            // Unique username
            $base = $username; $counter = 1;
            while (true) {
                $chk = $pdo->prepare("SELECT id FROM users WHERE username = ?");
                $chk->execute([$username]);
                if (!$chk->fetch()) break;
                $username = $base . $counter++;
            }

            // Email uniqueness
            $chk2 = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $chk2->execute([$email]);
            if ($chk2->fetch()) {
                $error = 'This email address is already registered.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users
                    (username, email, password, role, account_type, full_name, phone, company_name, business_email, tax_id, status)
                    VALUES (?, ?, ?, 'customer', ?, ?, ?, ?, ?, ?, 'active')");
                $stmt->execute([
                    $username, $email, $hash,
                    $account_type,
                    $username,   // full_name fallback
                    $phone,
                    $company_name,
                    $business_email ?: $email,
                    $tax_id
                ]);

                $_SESSION['user_id']  = $pdo->lastInsertId();
                $_SESSION['username'] = $username;
                $_SESSION['email']    = $email;
                $_SESSION['role']     = 'customer';

                header("Location: ../index.php");
                exit();
            }
        } catch (PDOException $e) {
            // Fallback: columns may not exist yet — insert without B2B fields
            try {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'customer')");
                $stmt->execute([$username, $email, $hash]);
                $_SESSION['user_id']  = $pdo->lastInsertId();
                $_SESSION['username'] = $username;
                $_SESSION['email']    = $email;
                $_SESSION['role']     = 'customer';
                header("Location: ../index.php"); exit();
            } catch (PDOException $ex) {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}

// Prefill account type from query string
$prefillType = in_array($_GET['type'] ?? '', ['b2b','retail']) ? $_GET['type'] : 'retail';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Account — Jenny's Cosmetics &amp; Jewelry</title>
<meta name="description" content="Register for a Jenny's Cosmetics & Jewelry account. Retail customers and B2B wholesalers welcome.">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* ================================================================
   Jenny's B2B SaaS Register — Full Rebuild
   Design tokens: Gold #FFAB00 | Ink Black #111111 | Charcoal #333333 | White
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
    --text-primary:#FFFFFF;
    --text-muted:  #999999;
    --text-dim:    #555555;
    --error:       #FF4D4D;
    --error-bg:    rgba(255,77,77,0.10);
    --success:     #2ECC71;
    --radius-sm:   8px;
    --radius-md:   16px;
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

/* ── HERO PANEL ── */
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
    position: absolute; inset: 0;
    width: 100%; height: 100%;
    pointer-events: none; opacity: 0.6;
}

.hero-brand { position: relative; z-index: 2; }

.hero-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--gold-light);
    border: 1px solid rgba(255,171,0,0.3);
    border-radius: 40px; padding: 6px 16px;
    font-size: 0.72rem; font-weight: 600;
    color: var(--gold); letter-spacing: 1.5px;
    text-transform: uppercase; margin-bottom: 32px;
}

.hero-logo {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2.2rem, 4vw, 3.2rem);
    font-weight: 700; line-height: 1.15;
    color: var(--text-primary); margin-bottom: 16px;
}
.hero-logo span { color: var(--gold); }

.hero-tagline {
    font-size: 1rem; color: var(--text-muted);
    font-weight: 300; line-height: 1.7;
    max-width: 360px; margin-bottom: 40px;
}

.hero-features { display: flex; flex-direction: column; gap: 16px; }

.hero-feature { display: flex; align-items: center; gap: 16px; }

.feature-icon {
    width: 40px; height: 40px; border-radius: var(--radius-sm);
    background: var(--gold-light);
    border: 1px solid rgba(255,171,0,0.2);
    display: flex; align-items: center; justify-content: center;
    color: var(--gold); font-size: 0.9rem; flex-shrink: 0;
}

.feature-text { font-size: 0.875rem; color: var(--text-muted); line-height: 1.5; }
.feature-text strong {
    display: block; color: var(--text-primary);
    font-weight: 500; margin-bottom: 2px;
}

.hero-divider {
    position: absolute; right: 0; top: 0; bottom: 0;
    width: 1px;
    background: linear-gradient(to bottom, transparent, var(--border), transparent);
}

/* ── FORM PANEL ── */
.auth-form-panel {
    width: 520px; min-width: 520px;
    background: var(--surface);
    display: flex; flex-direction: column;
    justify-content: center;
    padding: 48px 48px;
    overflow-y: auto;
}

/* ── ACCOUNT TYPE TOGGLE ── */
.account-type-tabs {
    display: flex;
    background: var(--surface2);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 4px;
    margin-bottom: 28px;
    gap: 4px;
}

.type-tab {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    border: none;
    background: transparent;
    color: var(--text-muted);
    font-family: 'Poppins', sans-serif;
    transition: background 0.2s, color 0.2s;
}

.type-tab.active {
    background: var(--gold);
    color: var(--ink);
}

.type-tab:not(.active):hover {
    color: var(--text-primary);
    background: rgba(255,255,255,0.05);
}

/* ── FORM ELEMENTS ── */
.form-eyebrow {
    font-size: 0.72rem; font-weight: 600;
    letter-spacing: 2px; text-transform: uppercase;
    color: var(--gold); margin-bottom: 8px;
}

.form-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.8rem; font-weight: 700;
    color: var(--text-primary); line-height: 1.2;
    margin-bottom: 8px;
}

.form-subtitle {
    font-size: 0.875rem; color: var(--text-muted);
    margin-bottom: 24px;
}
.form-subtitle a { color: var(--gold); font-weight: 500; }
.form-subtitle a:hover { opacity: 0.75; }

.alert-error {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 14px 16px;
    background: var(--error-bg);
    border: 1px solid rgba(255,77,77,0.3);
    border-radius: var(--radius-sm);
    margin-bottom: 20px;
    color: #FF7070; font-size: 0.85rem; line-height: 1.5;
    animation: slideDown 0.25s ease;
}
@keyframes slideDown {
    from { opacity:0; transform:translateY(-8px); }
    to   { opacity:1; transform:translateY(0); }
}
.alert-error i { color: var(--error); margin-top: 2px; flex-shrink: 0; }

/* B2B section */
.b2b-section {
    border-top: 1px solid var(--border);
    padding-top: 20px;
    margin-top: 4px;
    margin-bottom: 4px;
    display: none;
}
.b2b-section.visible { display: block; }
.b2b-section-label {
    font-size: 0.72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 1.5px;
    color: var(--gold); margin-bottom: 16px;
    display: flex; align-items: center; gap: 8px;
}

/* Form grid */
.form-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0 16px;
}

.form-group { margin-bottom: 18px; }

.form-label {
    display: block; font-size: 0.78rem; font-weight: 600;
    color: var(--text-muted); text-transform: uppercase;
    letter-spacing: 0.8px; margin-bottom: 8px;
    transition: color 0.2s;
}
.form-label .req { color: var(--gold); }

.input-wrap { position: relative; }

.input-icon {
    position: absolute; left: 16px; top: 50%;
    transform: translateY(-50%);
    color: var(--text-dim); font-size: 0.85rem;
    pointer-events: none; transition: color 0.2s;
}

.form-input {
    width: 100%;
    padding: 13px 16px 13px 44px;
    background: var(--surface2);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    color: var(--text-primary);
    font-size: 0.875rem;
    font-family: 'Poppins', sans-serif;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
}
.form-input::placeholder { color: var(--text-dim); }
.form-input:focus {
    border-color: var(--gold);
    background: #262626;
    box-shadow: 0 0 0 3px var(--gold-glow);
}
.input-wrap:focus-within .input-icon { color: var(--gold); }
.form-input.input-error { border-color: var(--error); }
.form-input.input-error:focus { box-shadow: 0 0 0 3px rgba(255,77,77,0.18); }
.field-error-msg { font-size: 0.76rem; color: var(--error); margin-top: 6px; display: none; }
.form-input.input-error ~ .field-error-msg { display: block; }

.pass-toggle {
    position: absolute; right: 14px; top: 50%;
    transform: translateY(-50%);
    background: none; border: none;
    color: var(--text-dim); cursor: pointer;
    padding: 4px; border-radius: 4px; line-height: 1;
    transition: color 0.2s;
}
.pass-toggle:hover { color: var(--gold); }

/* Password Strength */
.strength-bar-wrap {
    margin-top: 8px;
    height: 4px; border-radius: 2px;
    background: var(--border); overflow: hidden;
}
.strength-bar {
    height: 100%; width: 0;
    border-radius: 2px;
    transition: width 0.3s, background-color 0.3s;
}
.strength-label {
    font-size: 0.72rem; margin-top: 4px;
    color: var(--text-dim);
}

/* Submit */
.btn-primary {
    width: 100%;
    padding: 14px 24px;
    background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
    color: var(--ink); border: none;
    border-radius: var(--radius-sm);
    font-size: 0.95rem; font-weight: 700;
    font-family: 'Poppins', sans-serif; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 10px;
    transition: transform 0.2s, box-shadow 0.2s;
    position: relative; overflow: hidden; letter-spacing: 0.3px;
}
.btn-primary::before {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.12) 0%, transparent 60%);
}
.btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(255,171,0,0.38); }
.btn-primary:active { transform: translateY(0); }
.btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
.btn-spinner {
    display: none; width: 18px; height: 18px;
    border: 2.5px solid rgba(17,17,17,0.25);
    border-top-color: var(--ink);
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
.btn-primary.loading .btn-spinner { display: block; }
.btn-primary.loading .btn-label  { display: none; }

/* Terms */
.terms-line {
    font-size: 0.76rem; color: var(--text-dim);
    text-align: center; margin-top: 14px; line-height: 1.5;
}
.terms-line a { color: var(--gold); }
.terms-line a:hover { opacity: 0.75; }

/* Divider */
.auth-divider {
    display: flex; align-items: center;
    gap: 16px; margin: 20px 0;
    color: var(--text-dim); font-size: 0.78rem;
    text-transform: uppercase; letter-spacing: 1px;
}
.auth-divider::before, .auth-divider::after {
    content: ''; flex: 1; height: 1px; background: var(--border);
}

.social-btns { display: flex; gap: 12px; margin-bottom: 16px; }
.btn-social {
    flex: 1; display: flex; align-items: center; justify-content: center; gap: 8px;
    padding: 11px 16px;
    background: var(--surface2);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    color: var(--text-primary);
    font-size: 0.83rem; font-weight: 500;
    font-family: 'Poppins', sans-serif; cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
}
.btn-social:hover { border-color: rgba(255,255,255,0.15); background: #282828; }
.btn-social .g-icon { color: #ea4335; }
.btn-social .a-icon { color: #e0e0e0; }

.form-footer-links {
    text-align: center; font-size: 0.82rem; color: var(--text-muted); margin-top: 16px;
}
.form-footer-links a { color: var(--gold); font-weight: 500; }
.form-footer-links a:hover { opacity: 0.75; }

.back-home {
    display: inline-flex; align-items: center; gap: 6px;
    margin-top: 12px; font-size: 0.8rem; color: var(--text-dim);
    transition: color 0.2s;
}
.back-home:hover { color: var(--gold); }

/* ── TOAST ── */
.toast-container {
    position: fixed; bottom: 24px; right: 24px;
    z-index: 9999; display: flex; flex-direction: column; gap: 8px;
    pointer-events: none;
}
.toast {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 20px; border-radius: var(--radius-sm);
    background: var(--surface2); border: 1px solid var(--border);
    color: var(--text-primary); font-size: 0.875rem; font-weight: 500;
    box-shadow: 0 8px 24px rgba(0,0,0,0.4); animation: toastIn 0.3s ease;
    min-width: 280px; pointer-events: all;
}
.toast.toast-success { border-color: rgba(46,204,113,0.4); }
.toast.toast-success i { color: var(--success); }
.toast.toast-error   { border-color: rgba(255,77,77,0.4); }
.toast.toast-error i { color: var(--error); }
@keyframes toastIn {
    from { opacity:0; transform:translateX(24px); }
    to   { opacity:1; transform:translateX(0); }
}

/* ── RESPONSIVE ── */
@media (max-width: 1100px) {
    .auth-form-panel { width: 480px; min-width: 480px; }
}
@media (max-width: 768px) {
    body { flex-direction: column; }
    .auth-hero { display: none; }
    .auth-form-panel { width: 100%; min-width: 100%; padding: 48px 24px; justify-content: flex-start; padding-top: 56px; }
    .form-grid-2 { grid-template-columns: 1fr; gap: 0; }
}
@media (max-width: 360px) {
    .auth-form-panel { padding: 40px 16px; }
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
        <div class="hero-badge"><i class="fas fa-store"></i> Open Your Account Today</div>
        <h1 class="hero-logo">Join<br>Jenny's<br><span>B2B Network</span></h1>
        <p class="hero-tagline">Access wholesale pricing, early-access collections, and a dedicated business portal — designed for Pakistan's beauty retailers.</p>
        <div class="hero-features">
            <div class="hero-feature">
                <div class="feature-icon"><i class="fas fa-percent"></i></div>
                <div class="feature-text">
                    <strong>Wholesale Pricing</strong>
                    Bulk discounts and tiered B2B rates
                </div>
            </div>
            <div class="hero-feature">
                <div class="feature-icon"><i class="fas fa-headset"></i></div>
                <div class="feature-text">
                    <strong>Dedicated Support</strong>
                    Priority account manager for B2B clients
                </div>
            </div>
            <div class="hero-feature">
                <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                <div class="feature-text">
                    <strong>Analytics Dashboard</strong>
                    Track order history and spending patterns
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ── FORM PANEL ── -->
<div class="auth-form-panel">
    <p class="form-eyebrow">Get started</p>
    <h2 class="form-title">Create Your Account</h2>
    <p class="form-subtitle">Already have an account? <a href="login.php">Sign in here &rarr;</a></p>

    <!-- ACCOUNT TYPE TABS -->
    <div class="account-type-tabs" role="tablist" aria-label="Account type">
        <button
            type="button"
            class="type-tab <?= $prefillType !== 'b2b' ? 'active' : '' ?>"
            id="tab-retail"
            role="tab"
            onclick="switchType('retail')"
            aria-selected="<?= $prefillType !== 'b2b' ? 'true' : 'false' ?>">
            <i class="fas fa-user"></i> Retail Customer
        </button>
        <button
            type="button"
            class="type-tab <?= $prefillType === 'b2b' ? 'active' : '' ?>"
            id="tab-b2b"
            role="tab"
            onclick="switchType('b2b')"
            aria-selected="<?= $prefillType === 'b2b' ? 'true' : 'false' ?>">
            <i class="fas fa-building"></i> B2B Wholesaler
        </button>
    </div>

    <?php if ($error): ?>
    <div class="alert-error" role="alert">
        <i class="fas fa-circle-exclamation"></i>
        <span><?= htmlspecialchars($error) ?></span>
    </div>
    <?php endif; ?>

    <form id="registerForm" method="POST" action="register.php" novalidate>
        <input type="hidden" name="account_type" id="accountTypeInput" value="<?= $prefillType === 'b2b' ? 'b2b' : 'retail' ?>">

        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label" for="username">Username <span class="req">*</span></label>
                <div class="input-wrap">
                    <i class="fas fa-at input-icon"></i>
                    <input type="text" id="username" name="username" class="form-input"
                        placeholder="yourname"
                        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                        autocomplete="username" required>
                    <span class="field-error-msg">Username is required.</span>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label" for="email">Email Address <span class="req">*</span></label>
                <div class="input-wrap">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" id="email" name="email" class="form-input"
                        placeholder="you@example.com"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        autocomplete="email" required>
                    <span class="field-error-msg">Valid email is required.</span>
                </div>
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label" for="password">Password <span class="req">*</span></label>
                <div class="input-wrap">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" class="form-input"
                        placeholder="Min. 6 characters"
                        oninput="checkStrength(this.value)"
                        autocomplete="new-password" required>
                    <button type="button" class="pass-toggle" id="passToggle1" onclick="togglePass('password','passEye1')" aria-label="Toggle">
                        <i class="fas fa-eye" id="passEye1"></i>
                    </button>
                    <span class="field-error-msg">Password (min 6 chars) required.</span>
                </div>
                <div class="strength-bar-wrap"><div class="strength-bar" id="strengthBar"></div></div>
                <div class="strength-label" id="strengthLabel"></div>
            </div>
            <div class="form-group">
                <label class="form-label" for="confirm_password">Confirm Password <span class="req">*</span></label>
                <div class="input-wrap">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-input"
                        placeholder="Repeat password"
                        autocomplete="new-password" required>
                    <button type="button" class="pass-toggle" onclick="togglePass('confirm_password','passEye2')" aria-label="Toggle">
                        <i class="fas fa-eye" id="passEye2"></i>
                    </button>
                    <span class="field-error-msg">Passwords must match.</span>
                </div>
            </div>
        </div>

        <!-- ── B2B SECTION ── -->
        <div class="b2b-section <?= $prefillType === 'b2b' ? 'visible' : '' ?>" id="b2bSection">
            <div class="b2b-section-label"><i class="fas fa-building"></i> Business / Wholesale Information</div>

            <div class="form-group">
                <label class="form-label" for="company_name">Company / Business Name <span class="req">*</span></label>
                <div class="input-wrap">
                    <i class="fas fa-briefcase input-icon"></i>
                    <input type="text" id="company_name" name="company_name" class="form-input"
                        placeholder="Your Business Name"
                        value="<?= htmlspecialchars($_POST['company_name'] ?? '') ?>">
                    <span class="field-error-msg">Company name is required for B2B.</span>
                </div>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label" for="business_email">Business Email</label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope-open-text input-icon"></i>
                        <input type="email" id="business_email" name="business_email" class="form-input"
                            placeholder="orders@business.com"
                            value="<?= htmlspecialchars($_POST['business_email'] ?? '') ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="phone">Business Phone</label>
                    <div class="input-wrap">
                        <i class="fas fa-phone input-icon"></i>
                        <input type="tel" id="phone" name="phone" class="form-input"
                            placeholder="+92 300 0000000"
                            value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="tax_id">Tax / NTN / STRN Registration ID</label>
                <div class="input-wrap">
                    <i class="fas fa-id-card input-icon"></i>
                    <input type="text" id="tax_id" name="tax_id" class="form-input"
                        placeholder="Your Tax or Registration ID (optional)"
                        value="<?= htmlspecialchars($_POST['tax_id'] ?? '') ?>">
                </div>
            </div>
        </div>

        <button type="submit" class="btn-primary" id="registerBtn" style="margin-top: 8px;">
            <div class="btn-spinner"></div>
            <span class="btn-label" id="registerBtnLabel"><i class="fas fa-user-plus"></i>&nbsp; Create Account</span>
        </button>

        <p class="terms-line">
            By creating an account you agree to our <a href="../terms-conditions.php" target="_blank">Terms &amp; Conditions</a>
            and <a href="../privacy-policy.php" target="_blank">Privacy Policy</a>.
        </p>
    </form>

    <div class="auth-divider">or sign up with</div>
    <div class="social-btns">
        <button type="button" class="btn-social" onclick="showToast('Google sign-up is not configured yet.','error')">
            <i class="fab fa-google g-icon"></i> Google
        </button>
        <button type="button" class="btn-social" onclick="showToast('Apple sign-up is not configured yet.','error')">
            <i class="fab fa-apple a-icon"></i> Apple
        </button>
    </div>

    <div class="form-footer-links">Already registered? <a href="login.php">Sign in to your account</a></div>
    <div style="text-align:center;">
        <a href="../index.php" class="back-home"><i class="fas fa-arrow-left"></i> Back to Store</a>
    </div>
</div>

<!-- ── TOAST ── -->
<div class="toast-container" id="toastContainer"></div>

<script>
// ── PARTICLE CANVAS ──
(function() {
    const c = document.getElementById('heroCanvas');
    if (!c) return;
    const ctx = c.getContext('2d');
    const resize = () => { c.width = c.offsetWidth; c.height = c.offsetHeight; };
    resize(); window.addEventListener('resize', resize);
    const pts = Array.from({length: 55}, () => ({
        x: Math.random() * c.width, y: Math.random() * c.height,
        r: Math.random() * 1.8 + 0.4,
        dx: (Math.random() - 0.5) * 0.35, dy: (Math.random() - 0.5) * 0.35,
        o: Math.random() * 0.45 + 0.08
    }));
    function draw() {
        ctx.clearRect(0, 0, c.width, c.height);
        pts.forEach(p => {
            ctx.beginPath(); ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(255,171,0,${p.o})`; ctx.fill();
            p.x += p.dx; p.y += p.dy;
            if (p.x < 0 || p.x > c.width)  p.dx *= -1;
            if (p.y < 0 || p.y > c.height) p.dy *= -1;
        });
        requestAnimationFrame(draw);
    }
    draw();
})();

// ── ACCOUNT TYPE SWITCH ──
function switchType(type) {
    document.getElementById('accountTypeInput').value = type;
    document.getElementById('tab-retail').classList.toggle('active', type === 'retail');
    document.getElementById('tab-b2b').classList.toggle('active', type === 'b2b');
    document.getElementById('tab-retail').setAttribute('aria-selected', type === 'retail');
    document.getElementById('tab-b2b').setAttribute('aria-selected', type === 'b2b');
    const b2bSec = document.getElementById('b2bSection');
    b2bSec.classList.toggle('visible', type === 'b2b');
    const btnLabel = document.getElementById('registerBtnLabel');
    btnLabel.innerHTML = type === 'b2b'
        ? '<i class="fas fa-building"></i>&nbsp; Register as Wholesaler'
        : '<i class="fas fa-user-plus"></i>&nbsp; Create Account';
}

// ── PASS TOGGLE ──
function togglePass(inputId, iconId) {
    const inp = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    const show = inp.type === 'password';
    inp.type = show ? 'text' : 'password';
    icon.className = show ? 'fas fa-eye-slash' : 'fas fa-eye';
}

// ── PASSWORD STRENGTH ──
function checkStrength(val) {
    const bar   = document.getElementById('strengthBar');
    const label = document.getElementById('strengthLabel');
    if (!bar || !label) return;
    let score = 0;
    if (val.length >= 6)  score += 25;
    if (val.length >= 10) score += 25;
    if (/[A-Z]/.test(val)) score += 25;
    if (/[0-9]/.test(val) || /[^A-Za-z0-9]/.test(val)) score += 25;

    bar.style.width = score + '%';
    if (score < 50)  { bar.style.background = '#FF4D4D'; label.textContent = 'Weak'; label.style.color = '#FF4D4D'; }
    else if (score < 100) { bar.style.background = '#FFAB00'; label.textContent = 'Moderate'; label.style.color = '#FFAB00'; }
    else { bar.style.background = '#2ECC71'; label.textContent = 'Strong'; label.style.color = '#2ECC71'; }
}

// ── FORM VALIDATION ──
document.getElementById('registerForm').addEventListener('submit', function(e) {
    let valid = true;

    const requiredIds = ['username', 'email', 'password', 'confirm_password'];
    requiredIds.forEach(id => {
        const el = document.getElementById(id);
        if (!el.value.trim()) {
            el.classList.add('input-error');
            valid = false;
        } else {
            el.classList.remove('input-error');
        }
    });

    const pw  = document.getElementById('password').value;
    const cpw = document.getElementById('confirm_password').value;
    if (pw && cpw && pw !== cpw) {
        document.getElementById('confirm_password').classList.add('input-error');
        valid = false;
    }

    const isB2B = document.getElementById('accountTypeInput').value === 'b2b';
    if (isB2B) {
        const cn = document.getElementById('company_name');
        if (!cn.value.trim()) { cn.classList.add('input-error'); valid = false; }
    }

    if (!valid) { e.preventDefault(); return; }

    const btn = document.getElementById('registerBtn');
    btn.classList.add('loading');
    btn.disabled = true;
});

// Clear errors on input
document.querySelectorAll('.form-input').forEach(el => {
    el.addEventListener('input', function() { this.classList.remove('input-error'); });
});

// ── TOAST ──
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
showToast(<?= json_encode($error) ?>, 'error');
<?php endif; ?>
</script>
</body>
</html>