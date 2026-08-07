<?php
// admin/includes/sidebar.php v2.0
$current = basename($_SERVER['PHP_SELF']);
// Get notification count & pending orders
$notifCount = 0; $pendingCount = 0; $unreadCount = 0;
try {
    require_once '../config/db.php';
    $pendingCount = (int)$pdo->query("SELECT COUNT(*) FROM orders WHERE status='pending'")->fetchColumn();
    $unreadCount  = (int)$pdo->query("SELECT COUNT(*) FROM contact_messages WHERE is_read=0")->fetchColumn();
    $notifCount   = $pendingCount + $unreadCount;
} catch(Exception $e) {}

$adminImg = $_SESSION['admin_img'] ?? null;
$adminName = $_SESSION['admin_name'] ?? 'Admin';
?>
<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-logo">
        <div class="logo-icon"><i class="fas fa-gem"></i></div>
        <div>
            <div class="logo-text">Jenny's <span>Admin</span></div>
            <div class="logo-sub">Control Panel v2.0</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-title">Main</div>
        <a href="index.php" class="sidebar-link <?= $current==='index.php'?'active':'' ?>">
            <span class="link-icon"><i class="fas fa-tachometer-alt"></i></span>
            Dashboard
            <?php if ($notifCount > 0): ?>
            <span class="badge"><?= $notifCount ?></span>
            <?php endif; ?>
        </a>
        <a href="analytics.php" class="sidebar-link <?= $current==='analytics.php'?'active':'' ?>">
            <span class="link-icon"><i class="fas fa-chart-bar"></i></span>
            Analytics
        </a>

        <div class="nav-section-title">Catalog</div>
        <a href="products.php" class="sidebar-link <?= $current==='products.php'?'active':'' ?>">
            <span class="link-icon"><i class="fas fa-box-open"></i></span>
            Products
        </a>
        <a href="testimonials.php" class="sidebar-link <?= $current==='testimonials.php'?'active':'' ?>">
            <span class="link-icon"><i class="fas fa-star"></i></span>
            Testimonials
        </a>
        <a href="reviews.php" class="sidebar-link <?= $current==='reviews.php'?'active':'' ?>">
            <span class="link-icon"><i class="fas fa-comment-dots"></i></span>
            Reviews
        </a>
        <a href="coupons.php" class="sidebar-link <?= $current==='coupons.php'?'active':'' ?>">
            <span class="link-icon"><i class="fas fa-ticket-alt"></i></span>
            Coupons
        </a>

        <div class="nav-section-title">Orders & Payments</div>
        <a href="orders.php" class="sidebar-link <?= $current==='orders.php'?'active':'' ?>">
            <span class="link-icon"><i class="fas fa-shopping-bag"></i></span>
            Orders
            <?php if ($pendingCount > 0): ?>
            <span class="badge"><?= $pendingCount ?></span>
            <?php endif; ?>
        </a>
        <a href="payments.php" class="sidebar-link <?= $current==='payments.php'?'active':'' ?>">
            <span class="link-icon"><i class="fas fa-credit-card"></i></span>
            Payments
        </a>
        <a href="messages.php" class="sidebar-link <?= $current==='messages.php'?'active':'' ?>">
            <span class="link-icon"><i class="fas fa-envelope"></i></span>
            Messages
            <?php if ($unreadCount > 0): ?>
            <span class="badge"><?= $unreadCount ?></span>
            <?php endif; ?>
        </a>
        <a href="users.php" class="sidebar-link <?= $current==='users.php'?'active':'' ?>">
            <span class="link-icon"><i class="fas fa-users"></i></span>
            Customers
        </a>

        <div class="nav-section-title">Website</div>
        <a href="hero-slides.php" class="sidebar-link <?= $current==='hero-slides.php'?'active':'' ?>">
            <span class="link-icon"><i class="fas fa-images"></i></span>
            Hero Slides
        </a>
        <a href="../index.php" class="sidebar-link" target="_blank">
            <span class="link-icon"><i class="fas fa-globe"></i></span>
            View Website
        </a>

        <div class="nav-section-title">System</div>
        <a href="settings.php" class="sidebar-link <?= $current==='settings.php'?'active':'' ?>">
            <span class="link-icon"><i class="fas fa-cog"></i></span>
            Settings
        </a>
        <a href="logout.php" class="sidebar-link" style="color:#e74c3c;">
            <span class="link-icon" style="color:#e74c3c;"><i class="fas fa-sign-out-alt"></i></span>
            Logout
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="admin-user-info">
            <div class="admin-avatar">
                <?php if ($adminImg && file_exists('../img/profiles/' . $adminImg)): ?>
                <img src="../img/profiles/<?= htmlspecialchars($adminImg) ?>" alt="Admin">
                <?php else: ?>
                <?= strtoupper(substr($adminName, 0, 1)) ?>
                <?php endif; ?>
            </div>
            <div>
                <div class="admin-name"><?= htmlspecialchars($adminName) ?></div>
                <div class="admin-role">Super Administrator</div>
            </div>
        </div>
    </div>
</aside>

<div class="toast-container" id="toastContainer"></div>

<!-- Mobile Sidebar Overlay Backdrop -->
<div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleAdminSidebar(false)"></div>

<script>
function toggleAdminSidebar(force) {
    const sb = document.getElementById('adminSidebar');
    const bd = document.getElementById('sidebarBackdrop');
    if (!sb) return;
    const shouldOpen = force !== undefined ? force : !sb.classList.contains('open');
    if (shouldOpen) {
        sb.classList.add('open');
        if (bd) bd.classList.add('active');
    } else {
        sb.classList.remove('open');
        if (bd) bd.classList.remove('active');
    }
}

// Auto-inject hamburger toggle button into admin-topbar on mobile if not present
document.addEventListener('DOMContentLoaded', function() {
    const topbar = document.querySelector('.admin-topbar');
    if (topbar && !document.getElementById('adminMobileToggle')) {
        const btn = document.createElement('button');
        btn.id = 'adminMobileToggle';
        btn.className = 'admin-mobile-toggle';
        btn.innerHTML = '<i class="fas fa-bars"></i>';
        btn.setAttribute('aria-label', 'Toggle Menu');
        btn.onclick = function() { toggleAdminSidebar(); };
        topbar.insertBefore(btn, topbar.firstChild);
    }
});

function showToast(message, type = 'success') {
    const icons = { success: 'fa-check-circle', error: 'fa-exclamation-circle', info: 'fa-info-circle' };
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `<i class="fas ${icons[type] || icons.info}"></i> ${message}`;
    document.getElementById('toastContainer').appendChild(toast);
    setTimeout(() => { toast.style.opacity='0'; toast.style.transform='translateX(60px)'; setTimeout(() => toast.remove(), 400); }, 3600);
}

function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }

// Close modal on overlay click
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        e.target.classList.remove('active');
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.active').forEach(m => m.classList.remove('active'));
    }
});
</script>
