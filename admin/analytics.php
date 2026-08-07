<?php
// admin/analytics.php — Detailed Business Analytics v2.0
require_once 'includes/auth_check.php';
require_once '../config/db.php';

// Analytics queries
$monthlySales = $pdo->query("
    SELECT DATE_FORMAT(created_at, '%b %Y') as month, SUM(total) as revenue, COUNT(*) as order_count 
    FROM orders 
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
    GROUP BY DATE_FORMAT(created_at, '%Y-%m'), month
    ORDER BY DATE_FORMAT(created_at, '%Y-%m')
")->fetchAll();

$topSelling = $pdo->query("
    SELECT oi.product_name, SUM(oi.qty) as total_qty, SUM(oi.price * oi.qty) as total_revenue
    FROM order_items oi
    GROUP BY oi.product_name
    ORDER BY total_qty DESC
    LIMIT 5
")->fetchAll();

$paymentBreakdown = $pdo->query("
    SELECT payment_method, COUNT(*) as count, SUM(total) as total_amount
    FROM orders
    GROUP BY payment_method
")->fetchAll();

$orderStatusCount = $pdo->query("
    SELECT status, COUNT(*) as count FROM orders GROUP BY status
")->fetchAll();

// Monthly Labels and Data Arrays
$months = array_column($monthlySales, 'month');
$revenues = array_map('floatval', array_column($monthlySales, 'revenue'));
$orderCounts = array_map('intval', array_column($monthlySales, 'order_count'));

$topNames = array_column($topSelling, 'product_name');
$topQtys = array_map('intval', array_column($topSelling, 'total_qty'));

$payMethods = array_column($paymentBreakdown, 'payment_method');
$payTotals = array_map('floatval', array_column($paymentBreakdown, 'total_amount'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Analytics — Jenny's Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="css/admin.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body>
<?php include 'includes/sidebar.php'; ?>

<div class="admin-main">
    <div class="admin-topbar">
        <div class="topbar-title">Business Analytics & Performance</div>
    </div>

    <div class="admin-content">
        <!-- CHARTS GRID -->
        <div class="analytics-grid" style="margin-bottom:24px;">
            <!-- Revenue Trend (Line) -->
            <div class="chart-card" style="grid-column:1 / -1;">
                <div class="chart-header">
                    <div>
                        <div class="chart-title">12-Month Revenue & Sales Growth</div>
                        <div class="chart-subtitle">Monthly financial overview</div>
                    </div>
                </div>
                <canvas id="revenueTrendChart" height="90"></canvas>
            </div>

            <!-- Top Selling Products (Bar) -->
            <div class="chart-card">
                <div class="chart-header">
                    <div>
                        <div class="chart-title">Most Popular Products</div>
                        <div class="chart-subtitle">Units sold per item</div>
                    </div>
                </div>
                <canvas id="topProductsChart" height="160"></canvas>
            </div>

            <!-- Payment Method Split (Doughnut) -->
            <div class="chart-card">
                <div class="chart-header">
                    <div>
                        <div class="chart-title">Payment Method Share</div>
                        <div class="chart-subtitle">Revenue volume by method</div>
                    </div>
                </div>
                <canvas id="paymentSplitChart" height="160"></canvas>
            </div>
        </div>

        <!-- DETAILED TABLES -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <div class="table-card">
                <div class="table-header"><div class="table-title">Top Selling Items Summary</div></div>
                <table class="admin-table">
                    <thead><tr><th>Product Name</th><th>Units Sold</th><th>Total Revenue</th></tr></thead>
                    <tbody>
                        <?php foreach ($topSelling as $ts): ?>
                        <tr>
                            <td style="font-weight:600;color:#fff;"><?= htmlspecialchars($ts['product_name']) ?></td>
                            <td><span class="badge-status badge-processing"><?= $ts['total_qty'] ?> units</span></td>
                            <td style="font-weight:700;color:var(--gold);">Rs.<?= number_format($ts['total_revenue']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($topSelling)): ?>
                        <tr><td colspan="3" class="empty-state"><i class="fas fa-chart-line"></i>No sales recorded yet</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="table-card">
                <div class="table-header"><div class="table-title">Payment Gateways Breakdown</div></div>
                <table class="admin-table">
                    <thead><tr><th>Method</th><th>Orders Count</th><th>Total Value</th></tr></thead>
                    <tbody>
                        <?php foreach ($paymentBreakdown as $pb): ?>
                        <tr>
                            <td style="font-weight:600;color:var(--gold);"><?= htmlspecialchars($pb['payment_method']) ?></td>
                            <td><?= $pb['count'] ?> orders</td>
                            <td style="font-weight:700;color:var(--green);">Rs.<?= number_format($pb['total_amount']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
const chartTheme = {
    tooltip: { backgroundColor: '#1a1a1a', titleColor: '#F4B400', bodyColor: '#ccc', borderColor: '#333', borderWidth: 1 }
};

// 1. REVENUE TREND
new Chart(document.getElementById('revenueTrendChart').getContext('2d'), {
    type: 'line',
    data: {
        labels: <?= json_encode($months ?: ['Jan','Feb','Mar','Apr','May','Jun']) ?>,
        datasets: [{
            label: 'Revenue (Rs.)',
            data: <?= json_encode($revenues ?: [15000, 22000, 18000, 31000, 27000, 39000]) ?>,
            borderColor: '#F4B400',
            backgroundColor: 'rgba(244,180,0,0.08)',
            fill: true, tension: 0.35, pointRadius: 5
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false }, tooltip: chartTheme.tooltip },
        scales: {
            x: { grid: { color: 'rgba(255,255,255,0.03)' }, ticks: { color: '#666' } },
            y: { grid: { color: 'rgba(255,255,255,0.03)' }, ticks: { color: '#666', callback: v => 'Rs.' + v.toLocaleString() } }
        }
    }
});

// 2. TOP PRODUCTS
new Chart(document.getElementById('topProductsChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_map(fn($n)=>substr($n,0,14).'...', $topNames ?: ['Foundation','Lipstick','Eye Shadow','Earrings'])) ?>,
        datasets: [{
            label: 'Units Sold',
            data: <?= json_encode($topQtys ?: [45, 38, 29, 21]) ?>,
            backgroundColor: 'rgba(52,152,219,0.7)',
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false }, tooltip: chartTheme.tooltip },
        scales: {
            x: { grid: { display: false }, ticks: { color: '#666' } },
            y: { grid: { color: 'rgba(255,255,255,0.03)' }, ticks: { color: '#666' } }
        }
    }
});

// 3. PAYMENT SPLIT
new Chart(document.getElementById('paymentSplitChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($payMethods ?: ['COD','Online']) ?>,
        datasets: [{
            data: <?= json_encode($payTotals ?: [65000, 35000]) ?>,
            backgroundColor: ['#F4B400', '#2ecc71', '#3498db', '#9b59b6'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        cutout: '70%',
        plugins: { legend: { position: 'bottom', labels: { color: '#ccc' } }, tooltip: chartTheme.tooltip }
    }
});
</script>
</body>
</html>
