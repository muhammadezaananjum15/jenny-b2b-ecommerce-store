<?php
// Ensure session and DB are always available in header.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($pdo)) {
    $dbPath = __DIR__ . '/../config/db.php';
    if (file_exists($dbPath)) { require_once $dbPath; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Jenny's Cosmetics &amp; Imitation Jewelry | Premium online store for authentic makeup, lipstick, foundation, necklaces, earrings, and imitation jewelry in Pakistan. Nationwide shipping.">
<title>Jenny's Cosmetics &amp; Imitation Jewelry</title>
<!-- Google Fonts & Font Awesome -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
<!-- AOS CSS -->
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
<!-- Custom CSS -->
<link rel="stylesheet" href="css/style.css">

<style>
    /* --- CSS RESET & VARIABLES --- */
    :root {
        --primary-gold: #F4B400;
        --gold-hover: #d19c00;
        --dark-black: #1A1A1A;
        --light-bg: #f9f9f9;
        --white: #ffffff;
        --text-grey: #666;
        --font-heading: 'Playfair Display', serif;
        --font-body: 'Poppins', sans-serif;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    /* --- UNIFIED STICKY HEADER WRAPPER --- */
    /* All header layers sit INSIDE this fixed wrapper | no inner fixed positioning needed */
    .sticky-header-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
        background: var(--white);
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }

    /* --- TOP BAR (inside wrapper, NOT fixed itself) --- */
    .top-bar {
        background-color: var(--dark-black);
        color: var(--white);
        padding: 5px 5%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.75rem;
        border-bottom: 1px solid #2a2a2a;
        line-height: 1;
    }
    .top-bar-left, .top-bar-right { display: flex; align-items: center; gap: 16px; }
    .top-bar a { color: rgba(255,255,255,0.75); transition: color 0.2s; }
    .top-bar a:hover { color: var(--primary-gold); }

    /* --- MAIN HEADER (inside wrapper, NOT fixed itself) --- */
    .main-header {
        background: var(--white);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 5%;
        border-bottom: 1px solid #f0f0f0;
        gap: 16px;
    }
    .logo-container { display: flex; align-items: center; gap: 10px; min-width: 170px; flex-shrink: 0; }
    .logo-img { height: 46px; width: auto; object-fit: contain; }
    .logo-text { font-family: var(--font-heading); font-size: 1.4rem; font-weight: 700; color: var(--dark-black); line-height: 1.1; }
    .logo-text span { color: var(--primary-gold); }

    .header-search-bar {
        flex: 1;
        max-width: 480px;
        margin: 0 24px;
        display: flex;
        border: 1.5px solid #e8e8e8;
        border-radius: 30px;
        overflow: hidden;
        transition: border-color 0.2s;
    }
    .header-search-bar:focus-within { border-color: var(--primary-gold); box-shadow: 0 0 0 3px rgba(244,180,0,0.1); }
    .header-search-bar input { width: 100%; padding: 9px 16px; border: none; outline: none; font-family: var(--font-body); font-size: 0.875rem; background: transparent; }
    .header-search-bar button { padding: 0 18px; background: var(--primary-gold); color: var(--dark-black); border: none; cursor: pointer; font-size: 0.875rem; flex-shrink: 0; transition: background 0.2s; }
    .header-search-bar button:hover { background: var(--gold-hover); }

    /* --- NAV ICONS --- */
    .nav-icons {
        display: flex;
        gap: 18px;
        font-size: 1.15rem;
        align-items: center;
        flex-shrink: 0;
    }
    .nav-icons a, .nav-icons i { cursor: pointer; transition: color 0.2s; color: var(--dark-black); }
    .nav-icons a:hover i, .nav-icons i:hover { color: var(--primary-gold); }

    /* Cart badge | attached to the cart icon's parent div */
    .cart-icon-wrap { position: relative; display: flex; align-items: center; justify-content: center; }
    #cart-badge { position: absolute; top: -7px; right: -8px; background: var(--primary-gold); color: var(--dark-black); font-size: 0.62rem; font-weight: 700; padding: 2px 5px; border-radius: 10px; min-width: 17px; text-align: center; line-height: 1.3; display: none; }

    /* --- USER ACTIONS & AVATAR DROPDOWN --- */
    .user-actions { 
        display: flex !important; 
        align-items: center; 
        gap: 10px; 
        position: relative; 
    }

    .btn-auth { 
        padding: 6px 15px; 
        border-radius: 20px; 
        font-size: 0.8rem; 
        font-weight: 600; 
        cursor: pointer; 
        transition: 0.3s; 
        font-family: var(--font-body); 
        border: 1px solid transparent; 
        text-decoration: none; 
        display: inline-block; 
    }

    .btn-login { 
        background: var(--primary-gold); 
        color: var(--dark-black); 
    }
    .btn-login:hover { 
        background: #d19c00; 
        transform: scale(1.05); 
    }

    .btn-register { 
        background: transparent; 
        border: 1px solid var(--dark-black); 
        color: var(--dark-black); 
    }
    .btn-register:hover { 
        background: var(--dark-black); 
        color: var(--white); 
    }

    #guestActions {
        display: flex !important;
        align-items: center;
        gap: 8px;
    }

    #userActions {
        display: flex !important;
        align-items: center;
        gap: 10px;
    }

    /* --- AVATAR CIRCLE (Logged In) --- */
    .avatar-container {
        position: relative;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }
    .avatar-circle {
        width: 38px;
        height: 38px;
        background: var(--primary-gold);
        color: var(--dark-black);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        font-family: var(--font-body);
        border: 2px solid var(--primary-gold);
        transition: 0.3s;
        user-select: none;
    }
    .avatar-circle:hover {
        transform: scale(1.05);
        box-shadow: 0 0 10px rgba(244, 180, 0, 0.4);
    }

    /* --- DROPDOWN MENU --- */
    .user-dropdown {
        position: absolute;
        top: 50px;
        right: 0;
        background: var(--white);
        min-width: 220px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        padding: 10px 0;
        display: none;
        z-index: 1000;
        border: 1px solid #eee;
    }
    .user-dropdown.show {
        display: block;
    }
    .user-dropdown .user-email {
        padding: 10px 20px;
        font-size: 0.85rem;
        color: var(--text-grey);
        border-bottom: 1px solid #eee;
        margin-bottom: 5px;
        word-break: break-all;
    }  
    .user-dropdown .dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 20px;
        color: var(--dark-black);
        text-decoration: none;
        transition: 0.2s;
        font-size: 0.9rem;
        cursor: pointer;
    }
    .user-dropdown .dropdown-item:hover {
        background: var(--light-bg);
        color: var(--primary-gold);
    }
    .user-dropdown .dropdown-item i {
        width: 20px;
        color: var(--primary-gold);
    }
    .user-dropdown .dropdown-item.logout-item {
        color: #e74c3c;
    }
    .user-dropdown .dropdown-item.logout-item:hover {
        background: #fde8e8;
        color: #c0392b;
    }
    .user-dropdown .dropdown-item.logout-item i {
        color: #e74c3c;
    }
    /* --- AUTH POPUPS --- */
    .auth-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 99999; display: none; opacity: 0; transition: 0.3s; backdrop-filter: blur(5px); }
    .auth-overlay.active { display: block; opacity: 1; }
    .auth-modal { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) scale(0.9); background: var(--white); width: 90%; max-width: 450px; border-radius: 15px; padding: 40px; z-index: 100000; display: none; box-shadow: 0 20px 50px rgba(0,0,0,0.4); transition: 0.3s ease-in-out; }
    .auth-modal.active { display: block; transform: translate(-50%, -50%) scale(1); }
    .auth-close { position: absolute; top: 15px; right: 15px; background: transparent; border: none; font-size: 1.5rem; color: var(--text-grey); cursor: pointer; transition: 0.3s; }
    .auth-close:hover { color: red; transform: rotate(90deg); }
    .auth-modal h2 { font-family: var(--font-heading); font-size: 1.8rem; color: var(--dark-black); margin-bottom: 10px; text-align: center; }
    .auth-modal p { text-align: center; color: var(--text-grey); margin-bottom: 20px; font-size: 0.9rem; }
    .auth-form .form-group { margin-bottom: 15px; }
    .auth-form .form-group label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px; color: var(--text-grey); }
    .auth-form .form-group input { width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-family: var(--font-body); font-size: 0.95rem; outline: none; transition: 0.3s; }
    .auth-form .form-group input:focus { border-color: var(--primary-gold); box-shadow: 0 0 5px rgba(244, 180, 0, 0.2); }
    .auth-submit-btn { width: 100%; padding: 12px; background: var(--primary-gold); color: var(--dark-black); border: none; border-radius: 25px; font-size: 1rem; font-weight: 700; cursor: pointer; transition: 0.3s; font-family: var(--font-body); margin-top: 10px; }
    .auth-submit-btn:hover { background: #d19c00; transform: scale(1.02); }
    .auth-switch { text-align: center; margin-top: 15px; font-size: 0.9rem; color: var(--text-grey); }
    .auth-switch a { color: var(--primary-gold); font-weight: 600; cursor: pointer; transition: 0.3s; }
    .auth-switch a:hover { text-decoration: underline; }
    .logout-content { text-align: center; padding: 10px 0; }
    .logout-content i { font-size: 4rem; color: var(--primary-gold); margin-bottom: 15px; display: block; }
    .logout-content h3 { font-size: 1.3rem; margin-bottom: 10px; color: var(--dark-black); }
    .logout-content p { color: var(--text-grey); margin-bottom: 25px; }
    .logout-buttons { display: flex; gap: 15px; }
    .btn-confirm-logout { flex: 1; padding: 12px; background: #e74c3c; color: white; border: none; border-radius: 25px; font-weight: 600; cursor: pointer; transition: 0.3s; }
    .btn-confirm-logout:hover { background: #c0392b; }
    .btn-cancel-logout { flex: 1; padding: 12px; background: #eee; color: var(--dark-black); border: none; border-radius: 25px; font-weight: 600; cursor: pointer; transition: 0.3s; }
    .btn-cancel-logout:hover { background: #ddd; }

    /* --- NAV BAR (inside wrapper) --- */
    .nav-bar {
        background: var(--white);
        padding: 0 5%;
        border-top: 1px solid #f0f0f0;
        display: flex;
        justify-content: center;
        align-items: stretch;
    }
    .nav-links {
        display: flex;
        gap: 0;
        font-weight: 500;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0;
        padding: 0;
    }
    .nav-links li { position: relative; list-style: none; }
    .nav-links li a {
        display: block;
        padding: 10px 16px;
        color: var(--dark-black);
        transition: color 0.2s;
        white-space: nowrap;
    }
    .nav-links li a:hover, .nav-links li a.active { color: var(--primary-gold); }
    .nav-links li::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: 0;
        left: 16px;
        background: var(--primary-gold);
        transition: width 0.25s ease;
    }
    .nav-links li:hover::after { width: calc(100% - 32px); }

    /* Mega-dropdown */
    .nav-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        background: var(--white);
        min-width: 180px;
        border-radius: 0 0 10px 10px;
        border: 1px solid #eee;
        border-top: 2px solid var(--primary-gold);
        box-shadow: 0 12px 28px rgba(0,0,0,0.1);
        padding: 8px 0;
        opacity: 0;
        visibility: hidden;
        transform: translateY(4px);
        transition: opacity 0.2s, transform 0.2s, visibility 0.2s;
        z-index: 999;
    }
    .nav-links li:hover .nav-dropdown { opacity: 1; visibility: visible; transform: translateY(0); }
    .nav-dropdown a { display: block; padding: 9px 18px; font-size: 0.82rem; color: var(--dark-black); text-transform: none; letter-spacing: 0; transition: 0.2s; }
    .nav-dropdown a:hover { background: var(--light-bg); color: var(--primary-gold); padding-left: 24px; }

    /* Mobile hamburger button | hidden on desktop, shown by canonical responsive rules in style.css */
    .mobile-menu-btn {
        display: none;
        background: rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.1);
        cursor: pointer;
        font-size: 1.15rem;
        color: var(--dark-black);
        width: 38px;
        height: 38px;
        border-radius: 8px;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }
    .mobile-menu-btn:hover, .mobile-menu-btn:active {
        background: var(--primary-gold);
        color: var(--dark-black);
        border-color: var(--primary-gold);
    }

    /* Mobile nav overlay */
    .mobile-nav-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.55);
        z-index: 10000;
        backdrop-filter: blur(4px);
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
        display: block; /* always in DOM, visibility controlled by opacity + pointer-events */
    }
    .mobile-nav-overlay.open {
        opacity: 1;
        pointer-events: auto;
    }

    /* Mobile nav drawer panel */
    .mobile-nav-panel {
        position: fixed;
        top: 0;
        right: 0;
        width: 300px;
        max-width: 85vw;
        height: 100vh;
        background: var(--white);
        z-index: 10001;
        padding: 24px 20px;
        transform: translateX(100%);
        transition: transform 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
        overflow-y: auto;
        box-shadow: -8px 0 30px rgba(0, 0, 0, 0.22);
        display: flex;
        flex-direction: column;
    }
    .mobile-nav-panel.open {
        transform: translateX(0);
    }
    .mobile-nav-close {
        position: absolute; top: 16px; right: 16px;
        background: rgba(0,0,0,0.05); border: none; font-size: 1.1rem;
        color: var(--dark-black); cursor: pointer; width: 34px; height: 34px;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        transition: 0.2s;
    }
    .mobile-nav-close:hover { background: #e74c3c; color: #fff; }
    .mobile-nav-logo { font-family: var(--font-heading); font-size: 1.25rem; font-weight: 700; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid #eee; }
    .mobile-nav-logo span { color: var(--primary-gold); }
    .mobile-nav-links { list-style: none; display: flex; flex-direction: column; gap: 3px; padding: 0; margin: 0; }
    .mobile-nav-links a { display: flex; align-items: center; padding: 10px 12px; font-size: 0.88rem; font-weight: 500; color: var(--dark-black); border-radius: 8px; transition: 0.2s; text-decoration: none; }
    .mobile-nav-links a:hover { background: var(--light-bg); color: var(--primary-gold); }
    .mobile-nav-links .mobile-section-title { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #aaa; padding: 14px 12px 4px; }
    .mobile-nav-auth { margin-top: 20px; padding-top: 16px; border-top: 1px solid #eee; display: flex; flex-direction: column; gap: 8px; }
    .mobile-nav-auth a { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 11px; border-radius: 8px; font-weight: 600; font-size: 0.875rem; text-decoration: none; }
    .mobile-nav-auth .mb-login { background: var(--primary-gold); color: var(--dark-black); }
    .mobile-nav-auth .mb-register { border: 1.5px solid var(--dark-black); color: var(--dark-black); }

    /* RESPONSIVE BREAKPOINTS */
    @media (max-width: 992px) {
        .top-bar { display: none !important; }
        .header-search-bar { display: none !important; }
        .nav-bar { display: none !important; }
        body { padding-top: 64px !important; }
        .sticky-header-wrapper { position: fixed; top: 0; left: 0; width: 100%; z-index: 1000; }
        .main-header { padding: 8px 16px !important; gap: 8px !important; width: 100% !important; max-width: 100vw !important; box-sizing: border-box !important; }
        .logo-container { min-width: 0 !important; flex-shrink: 1 !important; }
        .nav-icons { gap: 10px !important; }
    }
    @media (max-width: 576px) {
        body { padding-top: 56px !important; }
        .main-header { padding: 6px 12px !important; }
        .logo-text { display: none !important; }
        .logo-img { height: 34px !important; }
        .nav-icons { gap: 8px !important; font-size: 1rem !important; }
        .user-actions .btn-auth { font-size: 0.7rem !important; padding: 4px 8px !important; }
        .avatar-circle { width: 30px !important; height: 30px !important; font-size: 0.82rem !important; }
    }
</style>


<!-- UNIFIED STICKY HEADER CONTAINER -->
<div class="sticky-header-wrapper" id="stickyHeader">
    <!-- TOP BAR -->
    <div class="top-bar">
        <div class="top-bar-left">
            <span><i class="fas fa-truck" style="color:var(--primary-gold);margin-right:5px;"></i> Free shipping on orders over Rs. 1,999</span>
            <span style="display:none;" class="hide-sm">|</span>
            <span class="hide-sm"><i class="fas fa-tag" style="color:var(--primary-gold);margin-right:5px;"></i> Use code <strong style="color:var(--primary-gold);">JENNY10</strong> for 10% off</span>
        </div>
        <div class="top-bar-right">
            <a href="contact.php"><i class="fas fa-headset"></i> Support</a>
            <a href="about.php">About</a>
        </div>
    </div>

    <!-- MAIN HEADER -->
    <header class="main-header" role="banner">
        <div class="logo-container">
            <a href="index.php" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
                <img src="img/logo.jpg" alt="Jenny's Cosmetics Logo" class="logo-img">
                <div class="logo-text">Jenny's <span>Cosmetics</span></div>
            </a>
        </div>

        <form class="header-search-bar" action="products.php" method="GET" role="search">
            <input type="text" name="search" placeholder="Search cosmetics, jewelry, makeup..." aria-label="Search products"
                   value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
            <button type="submit" aria-label="Submit search"><i class="fas fa-search"></i></button>
        </form>

        <div class="nav-icons">
            <a href="products.php?filter=wishlist" title="Wishlist" aria-label="Wishlist">
                <i class="far fa-heart"></i>
            </a>

            <a href="cart.php" class="cart-icon-wrap" title="Shopping Cart" aria-label="Shopping Cart">
                <i class="fas fa-shopping-cart"></i>
                <span id="cart-badge" aria-live="polite">0</span>
            </a>

            <div class="user-actions">
                <?php if (isset($_SESSION['user_id']) || !empty($_SESSION['admin_logged_in'])): ?>
                    <?php
                    $navAvatar = null;
                    if (!empty($_SESSION['user_id']) && isset($pdo)) {
                        try {
                            $avStmt = $pdo->prepare("SELECT profile_image FROM users WHERE id = ?");
                            $avStmt->execute([$_SESSION['user_id']]);
                            $navAvatar = $avStmt->fetchColumn();
                        } catch (Exception $e) {}
                    }
                    ?>
                    <div class="avatar-container" onclick="toggleUserDropdown()" role="button" aria-haspopup="true" aria-expanded="false" tabindex="0" onkeypress="if(event.key==='Enter') toggleUserDropdown()">
                        <div class="avatar-circle">
                            <?php if ($navAvatar && file_exists(__DIR__ . '/../img/profiles/' . $navAvatar)): ?>
                                <img src="img/profiles/<?= htmlspecialchars($navAvatar) ?>" alt="Avatar" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                            <?php else: ?>
                                <?php echo isset($_SESSION['username']) ? strtoupper(substr($_SESSION['username'], 0, 1)) : (isset($_SESSION['admin_name']) ? strtoupper(substr($_SESSION['admin_name'], 0, 1)) : 'A'); ?>
                            <?php endif; ?>
                        </div>
                        <i class="fas fa-chevron-down" style="font-size:0.7rem;color:var(--text-grey);"></i>
                    </div>

                    <div class="user-dropdown" id="userDropdown" role="menu">
                        <div class="user-email">
                            <i class="fas fa-envelope" style="margin-right:8px;"></i>
                            <?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : (isset($_SESSION['admin_email']) ? htmlspecialchars($_SESSION['admin_email']) : 'Admin'); ?>
                        </div>
                        <a href="profile.php" class="dropdown-item" role="menuitem">
                            <i class="fas fa-user-circle"></i> My Account / Profile
                        </a>
                        <a href="profile.php" class="dropdown-item" role="menuitem">
                            <i class="fas fa-shopping-bag"></i> My Orders
                        </a>
                        <?php if ((isset($_SESSION['role']) && $_SESSION['role'] === 'admin') || !empty($_SESSION['admin_logged_in'])): ?>
                        <a href="admin/index.php" class="dropdown-item" role="menuitem" style="color:var(--primary-gold);font-weight:600;">
                            <i class="fas fa-crown"></i> Admin Panel
                        </a>
                        <?php endif; ?>
                        <a href="auth/logout.php" class="dropdown-item logout-item" role="menuitem">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                <?php else: ?>
                    <div id="guestActions" style="display:flex;align-items:center;gap:8px;">
                        <a href="auth/login.php" class="btn-auth btn-login">Login</a>
                        <a href="auth/register.php" class="btn-auth btn-register">Register</a>
                    </div>
                <?php endif; ?>
            </div>

            <button class="mobile-menu-btn" onclick="toggleMobileNav(true)" aria-label="Open navigation menu" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>
<!-- sticky-header-wrapper is closed by navbar.php -->

<!-- MOBILE NAV -->
<div class="mobile-nav-overlay" id="mobileNavOverlay" onclick="toggleMobileNav(false)"></div>
<div class="mobile-nav-panel" id="mobileNavPanel">
    <button class="mobile-nav-close" onclick="toggleMobileNav(false)" aria-label="Close navigation"><i class="fas fa-times"></i></button>
    <div class="mobile-nav-logo">Jenny's <span>Cosmetics</span></div>

    <!-- Mobile Search Form -->
    <form action="products.php" method="GET" class="mobile-search-form" style="margin-bottom:20px; display:flex; border:1.5px solid #e0e0e0; border-radius:25px; overflow:hidden; background:#fdfdfd;">
        <input type="text" name="search" placeholder="Search products..." style="width:100%; padding:9px 14px; border:none; outline:none; font-size:0.85rem; background:transparent;">
        <button type="submit" style="padding:0 16px; background:var(--primary-gold); color:var(--dark-black); border:none; cursor:pointer;"><i class="fas fa-search"></i></button>
    </form>

    <ul class="mobile-nav-links">
        <li><a href="index.php"><i class="fas fa-home" style="width:22px;color:var(--primary-gold);"></i> Home</a></li>
        <div class="mobile-section-title">Shop Collections</div>
        <li><a href="products.php"><i class="fas fa-border-all" style="width:22px;color:var(--primary-gold);"></i> All Products</a></li>
        <li><a href="cosmetics.php"><i class="fas fa-magic" style="width:22px;color:var(--primary-gold);"></i> Cosmetics</a></li>
        <li><a href="imitation-jewelry.php"><i class="fas fa-gem" style="width:22px;color:var(--primary-gold);"></i> Jewelry</a></li>
        <li><a href="new-arrivals.php"><i class="fas fa-star" style="width:22px;color:var(--primary-gold);"></i> New Arrivals</a></li>
        <li><a href="best-sellers.php"><i class="fas fa-fire" style="width:22px;color:var(--primary-gold);"></i> Best Sellers</a></li>
        <li><a href="offers.php"><i class="fas fa-tags" style="width:22px;color:var(--primary-gold);"></i> Special Offers</a></li>
        <div class="mobile-section-title">Account & Info</div>
        <li><a href="cart.php"><i class="fas fa-shopping-bag" style="width:22px;color:var(--primary-gold);"></i> My Cart</a></li>
        <li><a href="products.php?filter=wishlist"><i class="fas fa-heart" style="width:22px;color:var(--primary-gold);"></i> My Wishlist</a></li>
        <li><a href="about.php"><i class="fas fa-info-circle" style="width:22px;color:var(--primary-gold);"></i> About Us</a></li>
        <li><a href="contact.php"><i class="fas fa-headset" style="width:22px;color:var(--primary-gold);"></i> Customer Support</a></li>
    </ul>
    <div class="mobile-nav-auth">
        <?php if (isset($_SESSION['user_id']) || !empty($_SESSION['admin_logged_in'])): ?>
        <?php if ((isset($_SESSION['role']) && $_SESSION['role'] === 'admin') || !empty($_SESSION['admin_logged_in'])): ?>
        <a href="admin/index.php" class="mb-login"><i class="fas fa-crown"></i> Admin Panel</a>
        <?php endif; ?>
        <a href="auth/logout.php" style="border:1.5px solid #e74c3c;color:#e74c3c;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        <?php else: ?>
        <a href="auth/login.php" class="mb-login"><i class="fas fa-sign-in-alt"></i> Sign In</a>
        <a href="auth/register.php" class="mb-register"><i class="fas fa-user-plus"></i> Create Account</a>
        <?php endif; ?>
    </div>
</div>

<!-- AUTH POPUPS HTML (Your existing popups) -->
<div class="auth-overlay" id="loginOverlay" onclick="closeAuthPopup('login')"></div>
<div class="auth-modal" id="loginModal">
    <button class="auth-close" onclick="closeAuthPopup('login')"><i class="fas fa-times"></i></button>
    <h2>Welcome Back</h2>
    <p>Login to your Jenny's account.</p>
    <form class="auth-form" onsubmit="handleLogin(event)">
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" id="loginEmail" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" id="loginPassword" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="auth-submit-btn">Login</button>
    </form>
    <div class="auth-switch">
        Don't have an account? <a onclick="switchAuthPopup('register')">Register here</a>
    </div>
</div>

<div class="auth-overlay" id="registerOverlay" onclick="closeAuthPopup('register')"></div>
<div class="auth-modal" id="registerModal">
    <button class="auth-close" onclick="closeAuthPopup('register')"><i class="fas fa-times"></i></button>
    <h2>Create Account</h2>
    <p>Join Jenny's family today.</p>
    <form class="auth-form" onsubmit="handleRegister(event)">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" id="regName" placeholder="Enter your full name" required>
        </div>
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" id="regEmail" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" id="regPassword" placeholder="Create a password" required>
        </div>
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" id="regConfirmPassword" placeholder="Confirm your password" required>
        </div>
        <button type="submit" class="auth-submit-btn">Register</button>
    </form>
    <div class="auth-switch">
        Already have an account? <a onclick="switchAuthPopup('login')">Login here</a>
    </div>
</div>

<div class="auth-overlay" id="logoutOverlay" onclick="closeAuthPopup('logout')"></div>
<div class="auth-modal" id="logoutModal">
    <button class="auth-close" onclick="closeAuthPopup('logout')"><i class="fas fa-times"></i></button>
    <div class="logout-content">
        <i class="fas fa-sign-out-alt"></i>
        <h3>Ready to leave?</h3>
        <p>Are you sure you want to logout from your account?</p>
        <div class="logout-buttons">
            <button class="btn-cancel-logout" onclick="closeAuthPopup('logout')">Cancel</button>
            <button class="btn-confirm-logout" onclick="handleLogout()">Yes, Logout</button>
        </div>
    </div>
</div>