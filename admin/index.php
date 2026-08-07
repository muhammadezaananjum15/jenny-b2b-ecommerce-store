<?php
// admin/index.php — Dashboard v2.0
require_once 'includes/auth_check.php';
require_once '../config/db.php';

// Fetch all stats
try {
    $totalProducts   = (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $totalOrders     = (int)$pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    $totalRevenue    = (float)($pdo->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE status='delivered'")->fetchColumn() ?? 0);
    $totalCustomers  = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='customer'")->fetchColumn();
    $pendingOrders   = (int)$pdo->query("SELECT COUNT(*) FROM orders WHERE status='pending'")->fetchColumn();
    $unreadMsgs      = (int)$pdo->query("SELECT COUNT(*) FROM contact_messages WHERE is_read=0")->fetchColumn();
    $lowStock        = (int)$pdo->query("SELECT COUNT(*) FROM products WHERE stock < 20")->fetchColumn();
    $todayRevenue    = (float)($pdo->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE DATE(created_at)=CURDATE()")->fetchColumn() ?? 0);

    // Sales last 6 months
    $salesData = $pdo->query("
        SELECT DATE_FORMAT(created_at,'%b %Y') AS month_label,
               DATE_FORMAT(created_at,'%Y-%m') AS month_key,
               SUM(total) AS total
        FROM orders
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
        GROUP BY month_key, month_label
        ORDER BY month_key
    ")->fetchAll();

    // Orders by status
    $ordersByStatus = $pdo->query("SELECT status, COUNT(*) as cnt FROM orders GROUP BY status")->fetchAll();

    // Revenue by category (join order_items → products)
    $catRevenue = $pdo->query("
        SELECT p.category, COALESCE(SUM(oi.price * oi.qty),0) AS revenue
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        GROUP BY p.category
    ")->fetchAll();

    // Recent orders
    $recentOrders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 10")->fetchAll();

    // Top products by rating
    $topProducts = $pdo->query("SELECT * FROM products ORDER BY rating DESC, review_count DESC LIMIT 6")->fetchAll();

    // Recent customers
    $recentCustomers = $pdo->query("SELECT * FROM users WHERE role='customer' ORDER BY created_at DESC LIMIT 5")->fetchAll();

    // Monthly order count (for trend)
    $thisMonthOrders = (int)$pdo->query("SELECT COUNT(*) FROM orders WHERE MONTH(created_at)=MONTH(NOW()) AND YEAR(created_at)=YEAR(NOW())")->fetchColumn();
    $lastMonthOrders = (int)$pdo->query("SELECT COUNT(*) FROM orders WHERE MONTH(created_at)=MONTH(DATE_SUB(NOW(),INTERVAL 1 MONTH)) AND YEAR(created_at)=YEAR(DATE_SUB(NOW(),INTERVAL 1 MONTH))")->fetchColumn();

} catch (PDOException $e) {
    $totalProducts = $totalOrders = $totalRevenue = $totalCustomers = 0;
    $pendingOrders = $unreadMsgs = $lowStock = $todayRevenue = 0;
    $salesData = $ordersByStatus = $catRevenue = $recentOrders = $topProducts = $recentCustomers = [];
    $thisMonthOrders = $lastMonthOrders = 0;
}

$salesMonths = array_column($salesData, 'month_label');
$salesTotals = array_map('floatval', array_column($salesData, 'total'));
$statusLabels = array_column($ordersByStatus, 'status');
$statusCounts = array_map('intval', array_column($ordersByStatus, 'cnt'));
$catLabels = array_column($catRevenue, 'category');
$catRevenues = array_map('floatval', array_column($catRevenue, 'revenue'));

$hour = (int)date('H');
$greeting = $hour < 12 ? 'Good Morning' : ($hour < 17 ? 'Good Afternoon' : 'Good Evening');
$orderTrend = $lastMonthOrders > 0 ? round((($thisMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard — Jenny's Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="css/admin.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body>
<?php include 'includes/sidebar.php'; ?>

<div class="admin-main">
    <!-- TOPBAR -->
    <div class="admin-topbar">
        <div class="topbar-title">Dashboard</div>
        <div class="topbar-right">
            <div class="topbar-search">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Quick search..." id="dashSearch">
            </div>
            <?php if ($unreadMsgs > 0): ?>
            <a href="messages.php" class="topbar-btn" style="position:relative;" title="<?= $unreadMsgs ?> unread messages">
                <i class="fas fa-bell"></i>
                <span style="position:absolute;top:-4px;right:-4px;background:var(--gold);color:#000;border-radius:50%;width:16px;height:16px;font-size:0.6rem;display:flex;align-items:center;justify-content:center;font-weight:700;"><?= $unreadMsgs ?></span>
            </a>
            <?php endif; ?>
            <a href="settings.php" class="topbar-btn" title="Settings"><i class="fas fa-cog"></i></a>
        </div>
    </div>

    <div class="admin-content">
        <!-- WELCOME -->
        <div style="margin-bottom:24px;display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <div>
                <h2 style="font-family:'Playfair Display',serif;font-size:1.7rem;color:#fff;margin-bottom:4px;">
                    <?= $greeting ?>, <?= htmlspecialchars($_SESSION['admin_name']) ?> 👑
                </h2>
                <p style="color:#666;font-size:0.875rem;">Here's what's happening with Jenny's store today — <?= date('l, F j, Y') ?></p>
            </div>
            <div style="display:flex;gap:8px;">
                <a href="products.php?action=add" class="btn-gold" onclick="event.preventDefault();window.location='products.php'"><i class="fas fa-plus"></i> Add Product</a>
                <a href="orders.php" class="btn-outline-gold"><i class="fas fa-shopping-bag"></i> View Orders</a>
            </div>
        </div>

        <!-- QUICK ACTIONS -->
        <div class="quick-actions">
            <a href="products.php" class="quick-action-btn">
                <div class="qa-icon" style="background:rgba(244,180,0,0.1);"><i class="fas fa-box-open" style="color:var(--gold);"></i></div>
                <span>Products</span>
            </a>
            <a href="orders.php" class="quick-action-btn">
                <div class="qa-icon" style="background:rgba(52,152,219,0.1);"><i class="fas fa-shopping-bag" style="color:var(--blue);"></i></div>
                <span>Orders</span>
            </a>
            <a href="users.php" class="quick-action-btn">
                <div class="qa-icon" style="background:rgba(46,204,113,0.1);"><i class="fas fa-users" style="color:var(--green);"></i></div>
                <span>Customers</span>
            </a>
            <a href="testimonials.php" class="quick-action-btn">
                <div class="qa-icon" style="background:rgba(155,89,182,0.1);"><i class="fas fa-star" style="color:var(--purple);"></i></div>
                <span>Testimonials</span>
            </a>
            <a href="hero-slides.php" class="quick-action-btn">
                <div class="qa-icon" style="background:rgba(231,76,60,0.1);"><i class="fas fa-images" style="color:var(--red);"></i></div>
                <span>Hero Slides</span>
            </a>
            <a href="coupons.php" class="quick-action-btn">
                <div class="qa-icon" style="background:rgba(243,156,18,0.1);"><i class="fas fa-ticket-alt" style="color:var(--orange);"></i></div>
                <span>Coupons</span>
            </a>
            <a href="payments.php" class="quick-action-btn">
                <div class="qa-icon" style="background:rgba(46,204,113,0.1);"><i class="fas fa-credit-card" style="color:var(--green);"></i></div>
                <span>Payments</span>
            </a>
            <a href="settings.php" class="quick-action-btn">
                <div class="qa-icon" style="background:rgba(136,136,136,0.1);"><i class="fas fa-cog" style="color:#aaa;"></i></div>
                <span>Settings</span>
            </a>
        </div>

        <!-- STAT CARDS -->
        <div class="stats-grid" style="grid-template-columns:repeat(4,1fr);">
            <div class="stat-card">
                <div class="stat-icon gold"><i class="fas fa-box-open"></i></div>
                <div class="stat-value counter" data-target="<?= $totalProducts ?>"><?= $totalProducts ?></div>
                <div class="stat-label">Total Products</div>
                <span class="stat-change up"><i class="fas fa-arrow-up"></i> Active Catalog</span>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-shopping-bag"></i></div>
                <div class="stat-value counter" data-target="<?= $totalOrders ?>"><?= $totalOrders ?></div>
                <div class="stat-label">Total Orders</div>
                <span class="stat-change <?= $pendingOrders > 0 ? 'neutral' : 'up' ?>">
                    <i class="fas fa-clock"></i> <?= $pendingOrders ?> pending
                </span>
            </div>
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-rupee-sign"></i></div>
                <div class="stat-value">Rs.<?= number_format($totalRevenue) ?></div>
                <div class="stat-label">Total Revenue</div>
                <span class="stat-change up"><i class="fas fa-arrow-up"></i> From delivered</span>
            </div>
            <div class="stat-card">
                <div class="stat-icon red"><i class="fas fa-users"></i></div>
                <div class="stat-value counter" data-target="<?= $totalCustomers ?>"><?= $totalCustomers ?></div>
                <div class="stat-label">Customers</div>
                <span class="stat-change up"><i class="fas fa-heart"></i> Loyal base</span>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-hourglass-half"></i></div>
                <div class="stat-value counter" data-target="<?= $pendingOrders ?>"><?= $pendingOrders ?></div>
                <div class="stat-label">Pending Orders</div>
                <span class="stat-change <?= $pendingOrders > 5 ? 'down' : 'neutral' ?>">
                    <i class="fas fa-exclamation"></i> Needs action
                </span>
            </div>
            <div class="stat-card">
                <div class="stat-icon red"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="stat-value counter" data-target="<?= $lowStock ?>"><?= $lowStock ?></div>
                <div class="stat-label">Low Stock Items</div>
                <span class="stat-change <?= $lowStock > 0 ? 'down' : 'up' ?>">
                    <i class="fas fa-<?= $lowStock > 0 ? 'arrow-down' : 'check' ?>"></i> <?= $lowStock > 0 ? 'Restock needed' : 'All good' ?>
                </span>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple"><i class="fas fa-envelope"></i></div>
                <div class="stat-value counter" data-target="<?= $unreadMsgs ?>"><?= $unreadMsgs ?></div>
                <div class="stat-label">Unread Messages</div>
                <span class="stat-change <?= $unreadMsgs > 0 ? 'neutral' : 'up' ?>">
                    <i class="fas fa-<?= $unreadMsgs > 0 ? 'bell' : 'check' ?>"></i> <?= $unreadMsgs > 0 ? 'Check inbox' : 'All read' ?>
                </span>
            </div>
            <div class="stat-card">
                <div class="stat-icon gold"><i class="fas fa-calendar-day"></i></div>
                <div class="stat-value">Rs.<?= number_format($todayRevenue) ?></div>
                <div class="stat-label">Today's Revenue</div>
                <span class="stat-change up"><i class="fas fa-star"></i> Today</span>
            </div>
        </div>

        <!-- CHARTS ROW -->
        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-header">
                    <div>
                        <div class="chart-title">Sales Overview</div>
                        <div class="chart-subtitle">Revenue trend for the last 6 months</div>
                    </div>
                    <div class="chart-actions">
                        <button class="chart-btn active" id="btnMonthly">Monthly</button>
                    </div>
                </div>
                <canvas id="salesChart" height="110"></canvas>
            </div>
            <div class="chart-card">
                <div class="chart-header">
                    <div>
                        <div class="chart-title">Orders by Status</div>
                        <div class="chart-subtitle">Current distribution</div>
                    </div>
                </div>
                <canvas id="statusChart" height="160"></canvas>
                <div id="statusLegend" style="margin-top:14px;display:flex;flex-direction:column;gap:6px;"></div>
            </div>
        </div>

        <!-- CATEGORY REVENUE + TOP PRODUCTS -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
            <div class="chart-card">
                <div class="chart-header">
                    <div>
                        <div class="chart-title">Revenue by Category</div>
                        <div class="chart-subtitle">Sales breakdown</div>
                    </div>
                </div>
                <canvas id="catChart" height="160"></canvas>
            </div>
            <div class="table-card" style="margin-bottom:0;">
                <div class="table-header">
                    <div class="table-title">Top Products</div>
                    <a href="products.php" class="btn-outline-gold" style="font-size:0.78rem;padding:5px 12px;">Manage</a>
                </div>
                <table class="admin-table">
                    <thead><tr><th>Product</th><th>Rating</th><th>Price</th><th>Stock</th></tr></thead>
                    <tbody>
                        <?php foreach ($topProducts as $p): ?>
                        <tr>
                            <td>
                                <div class="product-img-cell">
                                    <img src="../img/<?= htmlspecialchars($p['image'] ?? '') ?>" onerror="this.src='../img/foundation.jpg'" class="product-thumb" alt="">
                                    <div style="font-size:0.8rem;font-weight:600;color:#fff;"><?= htmlspecialchars(substr($p['name'],0,20)) ?>...</div>
                                </div>
                            </td>
                            <td><span style="color:var(--gold);">★ <?= $p['rating'] ?></span></td>
                            <td style="font-weight:700;color:var(--gold);">Rs.<?= number_format($p['price']) ?></td>
                            <td><span style="color:<?= $p['stock'] > 20 ? 'var(--green)' : 'var(--red)' ?>;font-weight:600;"><?= $p['stock'] ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($topProducts)): ?>
                        <tr><td colspan="4" class="empty-state"><i class="fas fa-box-open"></i>No products</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- RECENT ORDERS + RECENT CUSTOMERS -->
        <div style="display:grid;grid-template-columns:3fr 2fr;gap:16px;">
            <div class="table-card">
                <div class="table-header">
                    <div class="table-title">Recent Orders</div>
                    <a href="orders.php" class="btn-outline-gold" style="font-size:0.78rem;padding:5px 12px;">View All</a>
                </div>
                <table class="admin-table">
                    <thead><tr><th>#ID</th><th>Customer</th><th>Amount</th><th>Payment</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td style="color:var(--gold);font-weight:700;">#<?= $order['id'] ?></td>
                            <td>
                                <div style="font-weight:600;color:#fff;"><?= htmlspecialchars($order['customer_name']) ?></div>
                                <div style="font-size:0.72rem;color:#666;"><?= htmlspecialchars($order['customer_email']) ?></div>
                            </td>
                            <td style="font-weight:700;color:var(--gold);">Rs.<?= number_format($order['total']) ?></td>
                            <td style="font-size:0.78rem;"><?= htmlspecialchars($order['payment_method']) ?></td>
                            <td><span class="badge-status badge-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span></td>
                            <td style="color:#666;font-size:0.78rem;"><?= date('M d', strtotime($order['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($recentOrders)): ?>
                        <tr><td colspan="6" class="empty-state"><i class="fas fa-shopping-bag"></i>No orders yet</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="table-card">
                <div class="table-header">
                    <div class="table-title">Recent Customers</div>
                    <a href="users.php" class="btn-outline-gold" style="font-size:0.78rem;padding:5px 12px;">View All</a>
                </div>
                <table class="admin-table">
                    <thead><tr><th>Customer</th><th>City</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php foreach ($recentCustomers as $cu): ?>
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div class="user-initials"><?= strtoupper(substr($cu['username'],0,1)) ?></div>
                                    <div>
                                        <div style="font-weight:600;color:#fff;font-size:0.84rem;"><?= htmlspecialchars($cu['full_name'] ?: $cu['username']) ?></div>
                                        <div style="font-size:0.72rem;color:#666;"><?= htmlspecialchars($cu['email']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-size:0.8rem;color:#888;"><?= htmlspecialchars($cu['city'] ?? '—') ?></td>
                            <td><span class="badge-status badge-<?= $cu['status'] ?? 'active' ?>"><?= ucfirst($cu['status'] ?? 'active') ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($recentCustomers)): ?>
                        <tr><td colspan="3" class="empty-state"><i class="fas fa-users"></i>No customers</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
// ── COUNTER ANIMATION ──
document.querySelectorAll('.counter').forEach(el => {
    const target = parseInt(el.dataset.target) || 0;
    if (target === 0) return;
    let current = 0;
    const step = Math.ceil(target / 40);
    const t = setInterval(() => {
        current = Math.min(current + step, target);
        el.textContent = current.toLocaleString();
        if (current >= target) clearInterval(t);
    }, 30);
});

// ── CHART CONFIG ──
const chartDefaults = {
    plugins: {
        legend: { display: false },
        tooltip: {
            backgroundColor: '#1a1a1a', titleColor: '#F4B400',
            bodyColor: '#ccc', borderColor: '#333', borderWidth: 1
        }
    }
};

// ── SALES CHART ──
const salesCtx = document.getElementById('salesChart').getContext('2d');
const salesMonths = <?= json_encode($salesMonths ?: ['Jan','Feb','Mar','Apr','May','Jun']) ?>;
const salesTotals = <?= json_encode($salesTotals ?: [12000,18000,14000,22000,19000,26000]) ?>;

new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: salesMonths,
        datasets: [{
            label: 'Revenue (Rs.)',
            data: salesTotals,
            borderColor: '#F4B400',
            backgroundColor: 'rgba(244,180,0,0.06)',
            borderWidth: 2.5, fill: true, tension: 0.4,
            pointBackgroundColor: '#F4B400', pointRadius: 5, pointHoverRadius: 8
        }]
    },
    options: {
        ...chartDefaults,
        responsive: true,
        scales: {
            x: { grid: { color: 'rgba(255,255,255,0.03)' }, ticks: { color: '#666', font: { size: 11 } } },
            y: { grid: { color: 'rgba(255,255,255,0.03)' }, ticks: { color: '#666', font: { size: 11 }, callback: v => 'Rs.' + v.toLocaleString() } }
        },
        plugins: { ...chartDefaults.plugins, tooltip: { ...chartDefaults.plugins.tooltip, callbacks: { label: ctx => 'Rs. ' + ctx.raw.toLocaleString() } } }
    }
});

// ── STATUS DOUGHNUT ──
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusLabels = <?= json_encode($statusLabels ?: ['pending','processing','shipped','delivered','cancelled']) ?>;
const statusCounts = <?= json_encode($statusCounts ?: [2,3,2,5,0]) ?>;
const statusColors = { pending:'#f39c12', processing:'#3498db', shipped:'#9b59b6', delivered:'#2ecc71', cancelled:'#e74c3c', refunded:'#ff7675' };
const colors = statusLabels.map(s => statusColors[s] || '#888');

new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: statusLabels.map(s => s.charAt(0).toUpperCase() + s.slice(1)),
        datasets: [{ data: statusCounts, backgroundColor: colors, borderWidth: 0, hoverOffset: 8 }]
    },
    options: { ...chartDefaults, responsive: true, cutout: '68%' }
});

const legend = document.getElementById('statusLegend');
statusLabels.forEach((s, i) => {
    legend.innerHTML += `<div style="display:flex;align-items:center;justify-content:space-between;font-size:0.78rem;color:#888;">
        <div style="display:flex;align-items:center;gap:8px;">
            <span style="width:10px;height:10px;border-radius:50%;background:${colors[i]};flex-shrink:0;"></span>
            ${s.charAt(0).toUpperCase()+s.slice(1)}
        </div>
        <strong style="color:#fff;">${statusCounts[i]}</strong>
    </div>`;
});

// ── CATEGORY BAR CHART ──
const catCtx = document.getElementById('catChart').getContext('2d');
const catLabels = <?= json_encode($catLabels ?: ['Cosmetics','Jewelry']) ?>;
const catRevenues = <?= json_encode($catRevenues ?: [45000, 35000]) ?>;

new Chart(catCtx, {
    type: 'bar',
    data: {
        labels: catLabels,
        datasets: [{
            label: 'Revenue',
            data: catRevenues,
            backgroundColor: ['rgba(244,180,0,0.7)', 'rgba(155,89,182,0.7)', 'rgba(52,152,219,0.7)'],
            borderRadius: 8, borderSkipped: false
        }]
    },
    options: {
        ...chartDefaults,
        responsive: true,
        scales: {
            x: { grid: { display: false }, ticks: { color: '#666' } },
            y: { grid: { color: 'rgba(255,255,255,0.03)' }, ticks: { color: '#666', callback: v => 'Rs.' + v.toLocaleString() } }
        },
        plugins: { ...chartDefaults.plugins, tooltip: { ...chartDefaults.plugins.tooltip, callbacks: { label: ctx => 'Rs. ' + ctx.raw.toLocaleString() } } }
    }
});
</script>
</body>
</html>
