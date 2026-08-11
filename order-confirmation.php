<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$order = $_SESSION['last_order'] ?? null;
?>

<style>
/* ============================================ */
/* --- PREMIUM ORDER CONFIRMATION STYLES ---     */
/* ============================================ */
.confirmation-section {
    padding: 60px 5% 90px;
    background: linear-gradient(135deg, #fafafa 0%, #f4f1eb 100%);
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
}
.confirmation-wrap {
    max-width: 760px;
    width: 100%;
    text-align: center;
}

/* CONFIRM RING ANIMATION */
.confirm-icon-ring {
    width: 96px; height: 96px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2ECC71, #27AE60);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 20px;
    box-shadow: 0 12px 35px rgba(46,204,113,0.35);
    animation: popIn 0.6s cubic-bezier(0.34,1.56,0.64,1) both;
}
@keyframes popIn {
    from { transform: scale(0); opacity: 0; }
    to   { transform: scale(1); opacity: 1; }
}
.confirm-icon-ring i { font-size: 2.8rem; color: #fff; }

.confirmation-wrap h1 {
    font-family: var(--font-heading);
    font-size: clamp(1.8rem, 4vw, 2.5rem);
    margin-bottom: 8px;
    color: var(--dark-black);
}
.confirmation-wrap h1 span { color: var(--primary-gold); }
.confirmation-wrap > p {
    color: #777;
    font-size: 0.95rem;
    margin-bottom: 32px;
    line-height: 1.6;
}

/* ORDER TIMELINE TRACKER */
.order-tracker {
    background: #fff;
    border-radius: 20px;
    padding: 28px 24px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.06);
    margin-bottom: 28px;
    border: 1px solid #e8e8e8;
}
.tracker-title {
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: #999;
    margin-bottom: 24px;
}
.tracker-steps {
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    max-width: 600px;
    margin: 0 auto;
}
.tracker-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    position: relative;
    z-index: 2;
    flex: 1;
}
.tracker-icon {
    width: 44px; height: 44px;
    border-radius: 50%;
    background: #e8e8e8;
    color: #aaa;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
    transition: all 0.4s ease;
}
.tracker-step.active .tracker-icon {
    background: linear-gradient(135deg, var(--primary-gold), #d19c00);
    color: #111;
    box-shadow: 0 4px 16px rgba(244,180,0,0.35);
    transform: scale(1.1);
}
.tracker-step.done .tracker-icon {
    background: #2ECC71;
    color: #fff;
    box-shadow: 0 4px 14px rgba(46,204,113,0.3);
}
.tracker-label {
    font-size: 0.76rem;
    font-weight: 700;
    color: #bbb;
    white-space: nowrap;
}
.tracker-step.active .tracker-label { color: var(--dark-black); }
.tracker-step.done .tracker-label { color: #2ECC71; }
.tracker-line {
    position: absolute;
    top: 22px; left: 12%; right: 12%;
    height: 3px;
    background: #e8e8e8;
    z-index: 1;
}
.tracker-line-progress {
    height: 100%;
    width: 25%;
    background: linear-gradient(90deg, #2ECC71, var(--primary-gold));
    border-radius: 2px;
    transition: width 0.6s ease;
}

/* CARDS */
.order-card {
    background: #fff;
    border-radius: 20px;
    padding: 28px 32px;
    text-align: left;
    box-shadow: 0 8px 30px rgba(0,0,0,0.06);
    border: 1px solid #e8e8e8;
    margin-bottom: 24px;
}
.order-card h3 {
    font-family: var(--font-heading);
    font-size: 1.15rem;
    margin-bottom: 18px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--primary-gold);
    display: inline-block;
    color: var(--dark-black);
}
.order-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    font-size: 0.92rem;
    border-bottom: 1px solid #f4f4f4;
}
.order-row:last-child { border-bottom: none; }
.order-row .label { color: #777; font-weight: 500; }
.order-row .value { color: var(--dark-black); font-weight: 700; text-align: right; max-width: 65%; }

.order-id-badge {
    display: inline-block;
    background: linear-gradient(135deg, #fffbe6, #ffe599);
    color: #7a5c00;
    border: 1px solid var(--primary-gold);
    border-radius: 8px;
    padding: 3px 12px;
    font-size: 0.9rem;
    font-weight: 800;
    letter-spacing: 0.5px;
}
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #e6f9ed;
    color: #27ae60;
    border: 1px solid #a3e9be;
    border-radius: 20px;
    padding: 4px 14px;
    font-size: 0.82rem;
    font-weight: 700;
}

/* ITEMS TABLE IN CONFIRMATION */
.conf-items-list { margin-top: 14px; }
.conf-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 10px 0;
    border-bottom: 1px solid #f4f4f4;
}
.conf-item:last-child { border-bottom: none; }
.conf-item img {
    width: 48px; height: 48px;
    object-fit: contain;
    border-radius: 10px;
    border: 1px solid #eee;
}
.conf-item-info { flex: 1; }
.conf-item-name { font-size: 0.88rem; font-weight: 700; color: var(--dark-black); }
.conf-item-qty { font-size: 0.78rem; color: #888; margin-top: 2px; }
.conf-item-price { font-weight: 800; color: var(--primary-gold); font-size: 0.92rem; }

/* ACTIONS */
.confirm-actions {
    display: flex;
    gap: 14px;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 12px;
}
.confirm-actions a, .confirm-actions button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 30px;
    border-radius: 30px;
    font-size: 0.92rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s;
    font-family: var(--font-body);
    cursor: pointer;
    border: none;
}
.btn-primary-action {
    background: var(--primary-gold);
    color: #111;
}
.btn-primary-action:hover { box-shadow: 0 6px 20px rgba(244,180,0,0.35); transform: translateY(-2px); }
.btn-outline-action {
    background: #fff;
    color: var(--dark-black);
    border: 1.5px solid #ddd !important;
}
.btn-outline-action:hover { background: #f5f5f5; border-color: #bbb !important; }

.delivery-note {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 14px;
    padding: 16px 20px;
    font-size: 0.88rem;
    color: #166534;
    margin-bottom: 24px;
    text-align: left;
}
.delivery-note i { font-size: 1.2rem; flex-shrink: 0; color: #22c55e; }

@media (max-width: 600px) {
    .order-card { padding: 20px 18px; }
    .confirm-actions { flex-direction: column; }
    .confirm-actions a, .confirm-actions button { width: 100%; justify-content: center; }
    .tracker-label { font-size: 0.65rem; }
}
</style>

<?php require 'includes/header.php'; ?>
<link rel="stylesheet" href="css/style.css">
<?php require 'includes/navbar.php'; ?>

<section class="confirmation-section">
    <div class="confirmation-wrap">

        <div class="confirm-icon-ring">
            <i class="fas fa-check"></i>
        </div>

        <h1>Order <span>Confirmed!</span> <i class="fas fa-gift"></i></h1>
        <p>Thank you for shopping with <strong>Jenny's Cosmetics &amp; Jewelry</strong>!<br>
           We are preparing your package with love and care.</p>

        <!-- VISUAL TRACKER -->
        <div class="order-tracker">
            <div class="tracker-title"><i class="fas fa-route" style="color:var(--primary-gold);margin-right:6px;"></i> Live Order Status</div>
            <div class="tracker-steps">
                <div class="tracker-line"><div class="tracker-line-progress" id="trackerProgress"></div></div>
                <div class="tracker-step done">
                    <div class="tracker-icon"><i class="fas fa-check"></i></div>
                    <span class="tracker-label">Order Placed</span>
                </div>
                <div class="tracker-step active">
                    <div class="tracker-icon"><i class="fas fa-box-open"></i></div>
                    <span class="tracker-label">Processing</span>
                </div>
                <div class="tracker-step">
                    <div class="tracker-icon"><i class="fas fa-truck"></i></div>
                    <span class="tracker-label">Shipped</span>
                </div>
                <div class="tracker-step">
                    <div class="tracker-icon"><i class="fas fa-home"></i></div>
                    <span class="tracker-label">Delivered</span>
                </div>
            </div>
        </div>

        <div class="delivery-note">
            <i class="fas fa-truck-fast"></i>
            <div>
                <strong>Estimated Delivery: 3 to 5 Business Days</strong><br>
                <span style="font-size:0.82rem; opacity:0.85;">Our delivery agent will call your phone prior to arrival.</span>
            </div>
        </div>

        <!-- PHP SESSION ORDER DETAILS (or populated via JS) -->
        <div id="orderDetailsCard">
            <?php if ($order): ?>
            <div class="order-card">
                <h3><i class="fas fa-box"></i> Order Summary</h3>
                <div class="order-row">
                    <span class="label">Order Number</span>
                    <span class="value"><span class="order-id-badge">#<?= htmlspecialchars($order['order_id']) ?></span></span>
                </div>
                <div class="order-row">
                    <span class="label">Status</span>
                    <span class="value"><span class="status-badge"><i class="fas fa-circle" style="font-size:0.5rem;"></i> Confirmed &amp; Processing</span></span>
                </div>
                <div class="order-row">
                    <span class="label">Date</span>
                    <span class="value"><?= htmlspecialchars($order['date']) ?></span>
                </div>
                <div class="order-row">
                    <span class="label">Payment Method</span>
                    <span class="value"><?= htmlspecialchars(strtoupper($order['payment'])) ?></span>
                </div>
                <div class="order-row">
                    <span class="label">Total Amount</span>
                    <span class="value" style="color:var(--primary-gold);font-size:1.1rem;">Rs. <?= number_format($order['total']) ?></span>
                </div>
            </div>

            <div class="order-card">
                <h3><i class="fas fa-user"></i> Customer Details</h3>
                <div class="order-row"><span class="label">Name</span><span class="value"><?= htmlspecialchars($order['customer_name']) ?></span></div>
                <div class="order-row"><span class="label">Email</span><span class="value"><?= htmlspecialchars($order['customer_email']) ?></span></div>
                <div class="order-row"><span class="label">Phone</span><span class="value"><?= htmlspecialchars($order['customer_phone']) ?></span></div>
                <div class="order-row"><span class="label">Delivery Address</span><span class="value"><?= htmlspecialchars($order['address']) ?></span></div>
            </div>
            <?php endif; ?>
        </div>

        <div class="confirm-actions">
            <a href="products.php" class="btn-primary-action">
                <i class="fas fa-shopping-bag"></i> Continue Shopping
            </a>
            <button onclick="window.print()" class="btn-outline-action">
                <i class="fas fa-print"></i> Print Invoice
            </button>
            <a href="contact.php" class="btn-outline-action">
                <i class="fas fa-headset"></i> Contact Support
            </a>
        </div>
    </div>
</section>

<script>
// Load from localStorage if PHP session order is empty
window.addEventListener('DOMContentLoaded', function() {
    const cardContainer = document.getElementById('orderDetailsCard');
    const lastOrder = localStorage.getItem('jennyLastOrder');

    if (lastOrder && (!cardContainer.children.length || cardContainer.innerHTML.trim() === '')) {
        try {
            const o = JSON.parse(lastOrder);
            const itemsHtml = o.items ? o.items.map(i => `
                <div class="conf-item">
                    <img src="${i.image}" alt="${i.name}" onerror="this.src='img/placeholder.jpg'">
                    <div class="conf-item-info">
                        <div class="conf-item-name">${i.name}</div>
                        <div class="conf-item-qty">Qty: ${i.quantity} × Rs. ${i.price}</div>
                    </div>
                    <div class="conf-item-price">Rs. ${i.price * i.quantity}</div>
                </div>
            `).join('') : '';

            cardContainer.innerHTML = `
                <div class="order-card">
                    <h3><i class="fas fa-box"></i> Order Summary</h3>
                    <div class="order-row"><span class="label">Order Number</span><span class="value"><span class="order-id-badge">#${o.orderNum}</span></span></div>
                    <div class="order-row"><span class="label">Status</span><span class="value"><span class="status-badge"><i class="fas fa-circle" style="font-size:0.5rem;"></i> Confirmed &amp; Processing</span></span></div>
                    <div class="order-row"><span class="label">Date</span><span class="value">${o.date}</span></div>
                    <div class="order-row"><span class="label">Payment Method</span><span class="value">${(o.payment || 'COD').toUpperCase()}</span></div>
                    <div class="order-row"><span class="label">Delivery Method</span><span class="value">${(o.delivery || 'standard').toUpperCase()}</span></div>
                    <div class="order-row"><span class="label">Total Amount</span><span class="value" style="color:var(--primary-gold);font-size:1.1rem;">Rs. ${o.total}</span></div>
                    <div style="margin-top:16px;">
                        <div style="font-size:0.82rem;font-weight:700;color:#999;text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;">Purchased Items</div>
                        <div class="conf-items-list">${itemsHtml}</div>
                    </div>
                </div>
                <div class="order-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Delivery Details</h3>
                    <div class="order-row"><span class="label">Name</span><span class="value">${o.name || ' | '}</span></div>
                    <div class="order-row"><span class="label">Email</span><span class="value">${o.email || ' | '}</span></div>
                    <div class="order-row"><span class="label">Phone</span><span class="value">${o.phone || ' | '}</span></div>
                    <div class="order-row"><span class="label">Address</span><span class="value">${o.address || ' | '}, ${o.city || ''}</span></div>
                </div>
            `;
        } catch(e) {}
    }

    // Clear active cart from localStorage
    localStorage.removeItem('jennyCart');
});
</script>

<?php require 'includes/footer.php'; ?>