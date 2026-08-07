<?php
session_start();
$order = $_SESSION['last_order'] ?? null;
// Don't clear the session yet so a page refresh still shows it
?>

<style>
/* ============================================ */
/* --- ORDER CONFIRMATION PAGE STYLES ---       */
/* ============================================ */
.confirmation-section {
    padding: 80px 5%;
    background: var(--light-bg);
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
}
.confirmation-wrap {
    max-width: 680px;
    width: 100%;
    text-align: center;
}
.confirm-icon-ring {
    width: 100px; height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f9d423, #F4B400);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 24px;
    box-shadow: 0 12px 40px rgba(244,180,0,0.3);
    animation: popIn 0.6s cubic-bezier(0.34,1.56,0.64,1) both;
}
@keyframes popIn {
    from { transform: scale(0); opacity: 0; }
    to   { transform: scale(1); opacity: 1; }
}
.confirm-icon-ring i { font-size: 2.8rem; color: #fff; }

.confirmation-wrap h1 {
    font-family: var(--font-heading);
    font-size: 2.5rem;
    margin-bottom: 10px;
    color: var(--dark-black);
}
.confirmation-wrap h1 span { color: var(--primary-gold); }
.confirmation-wrap > p {
    color: var(--text-grey);
    font-size: 1rem;
    margin-bottom: 30px;
    line-height: 1.6;
}

.order-info-card {
    background: var(--white);
    border-radius: 16px;
    padding: 28px 32px;
    text-align: left;
    box-shadow: 0 8px 30px rgba(0,0,0,0.07);
    border: 1px solid #eee;
    margin-bottom: 24px;
}
.order-info-card h3 {
    font-family: var(--font-heading);
    font-size: 1.15rem;
    margin-bottom: 16px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--primary-gold);
    display: inline-block;
    color: var(--dark-black);
}
.order-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    font-size: 0.93rem;
    border-bottom: 1px solid #f4f4f4;
}
.order-row:last-child { border-bottom: none; }
.order-row .label { color: var(--text-grey); font-weight: 500; }
.order-row .value { color: var(--dark-black); font-weight: 600; text-align: right; max-width: 60%; }
.order-id-badge {
    display: inline-block;
    background: linear-gradient(135deg, #fdf3cc, #fde98a);
    color: #7a5c00;
    border: 1px solid #f4b400;
    border-radius: 6px;
    padding: 2px 10px;
    font-size: 0.88rem;
    font-weight: 700;
    letter-spacing: 0.5px;
}
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: rgba(46,204,113,0.1);
    color: #2ecc71;
    border: 1px solid rgba(46,204,113,0.3);
    border-radius: 20px;
    padding: 3px 12px;
    font-size: 0.82rem;
    font-weight: 700;
}
.confirm-actions {
    display: flex;
    gap: 14px;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 8px;
}
.confirm-actions a {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 13px 28px;
    border-radius: 30px;
    font-size: 0.95rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
    font-family: var(--font-body);
}
.confirm-actions a.btn-primary {
    background: var(--primary-gold);
    color: #111;
}
.confirm-actions a.btn-primary:hover { box-shadow: 0 6px 20px rgba(244,180,0,0.35); transform: translateY(-2px); }
.confirm-actions a.btn-outline {
    background: transparent;
    color: var(--dark-black);
    border: 2px solid var(--primary-gold);
}
.confirm-actions a.btn-outline:hover { background: var(--primary-gold); color: #111; }

.delivery-note {
    display: flex;
    align-items: center;
    gap: 10px;
    background: rgba(52,152,219,0.07);
    border: 1px solid rgba(52,152,219,0.2);
    border-radius: 10px;
    padding: 14px 18px;
    font-size: 0.88rem;
    color: #3498db;
    margin-bottom: 24px;
    text-align: left;
}
.delivery-note i { font-size: 1.1rem; flex-shrink: 0; }

@media (max-width: 600px) {
    .confirmation-wrap h1 { font-size: 1.8rem; }
    .order-info-card { padding: 20px; }
    .confirm-actions { flex-direction: column; }
    .confirm-actions a { justify-content: center; }
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

        <h1>Order <span>Confirmed!</span> 🎉</h1>
        <p>Thank you for shopping with <strong>Jenny's Cosmetics &amp; Jewelry</strong>!<br>
           Your order has been received and is being processed.</p>

        <?php if ($order): ?>

        <div class="delivery-note">
            <i class="fas fa-truck"></i>
            <span>Expected delivery within <strong>3–5 business days</strong>. Our team will contact you at <strong><?= htmlspecialchars($order['customer_phone']) ?></strong> to confirm your order.</span>
        </div>

        <div class="order-info-card">
            <h3>📦 Order Details</h3>
            <div class="order-row">
                <span class="label">Order ID</span>
                <span class="value"><span class="order-id-badge">#<?= htmlspecialchars($order['order_id']) ?></span></span>
            </div>
            <div class="order-row">
                <span class="label">Status</span>
                <span class="value"><span class="status-badge"><i class="fas fa-circle" style="font-size:0.5rem;"></i> Pending</span></span>
            </div>
            <div class="order-row">
                <span class="label">Date &amp; Time</span>
                <span class="value"><?= htmlspecialchars($order['date']) ?></span>
            </div>
            <div class="order-row">
                <span class="label">Payment Method</span>
                <span class="value"><?= htmlspecialchars(strtoupper($order['payment'])) ?></span>
            </div>
            <div class="order-row">
                <span class="label">Order Total</span>
                <span class="value" style="color:var(--primary-gold);font-size:1.05rem;">Rs. <?= number_format($order['total']) ?></span>
            </div>
        </div>

        <div class="order-info-card">
            <h3>👤 Customer Info</h3>
            <div class="order-row">
                <span class="label">Name</span>
                <span class="value"><?= htmlspecialchars($order['customer_name']) ?></span>
            </div>
            <div class="order-row">
                <span class="label">Email</span>
                <span class="value"><?= htmlspecialchars($order['customer_email']) ?></span>
            </div>
            <div class="order-row">
                <span class="label">Phone</span>
                <span class="value"><?= htmlspecialchars($order['customer_phone']) ?></span>
            </div>
            <div class="order-row">
                <span class="label">Delivery Address</span>
                <span class="value"><?= htmlspecialchars($order['address']) ?></span>
            </div>
        </div>

        <?php else: ?>
        <div class="order-info-card" style="text-align:center;">
            <p style="color:var(--text-grey);padding:20px 0;">No order details found. If you just placed an order, it was recorded successfully!</p>
        </div>
        <?php endif; ?>

        <div class="confirm-actions">
            <a href="products.php" class="btn-primary">
                <i class="fas fa-shopping-bag"></i> Continue Shopping
            </a>
            <a href="contact.php" class="btn-outline">
                <i class="fas fa-headset"></i> Need Help?
            </a>
        </div>
    </div>
</section>

<script>
// Clear the cart from localStorage after successful order
localStorage.removeItem('jennyCart');
</script>

<?php require 'includes/footer.php'; ?>