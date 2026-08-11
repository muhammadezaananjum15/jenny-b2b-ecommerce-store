<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<?php require 'includes/header.php'; ?>
<link rel="stylesheet" href="css/style.css">
<?php require 'includes/navbar.php'; ?>

<style>
.policy-hero {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    padding: 80px 5%; text-align: center; position: relative; overflow: hidden;
}
.policy-hero::before {
    content: ''; position: absolute; top: -50%; left: -50%;
    width: 200%; height: 200%;
    background: radial-gradient(circle at center, rgba(244,180,0,0.06) 0%, transparent 60%);
}
.policy-hero h1 { font-family: var(--font-heading); font-size: 2.5rem; color: #fff; margin-bottom: 12px; position: relative; }
.policy-hero h1 span { color: var(--primary-gold); }
.policy-hero p { color: #aaa; font-size: 0.95rem; position: relative; }
.policy-body { max-width: 860px; margin: 60px auto; padding: 0 5% 80px; }
.policy-body h2 { font-family: var(--font-heading); font-size: 1.4rem; color: var(--dark-black); margin: 32px 0 12px; border-left: 4px solid var(--primary-gold); padding-left: 14px; }
.policy-body p, .policy-body li { color: var(--text-grey); line-height: 1.8; font-size: 0.95rem; margin-bottom: 10px; }
.policy-body ul { padding-left: 20px; margin-bottom: 16px; }
.policy-body .last-updated { font-size: 0.82rem; color: #aaa; margin-bottom: 30px; }
</style>

<section class="policy-hero">
    <h1>Terms &amp; <span>Conditions</span></h1>
    <p>Please read these terms carefully before using our website or placing an order.</p>
</section>

<div class="policy-body">
    <p class="last-updated"><i class="fas fa-calendar-alt" style="color:var(--primary-gold);"></i> Last Updated: July 2026</p>

    <h2>1. Acceptance of Terms</h2>
    <p>By accessing and using the Jenny's Cosmetics &amp; Jewelry website, you accept and agree to be bound by these Terms and Conditions. If you do not agree with any part of these terms, please do not use our website.</p>

    <h2>2. Products &amp; Pricing</h2>
    <ul>
        <li>All product images are for illustration purposes only. Actual products may vary slightly.</li>
        <li>Prices are listed in Pakistani Rupees (Rs.) and are subject to change without prior notice.</li>
        <li>We reserve the right to cancel orders if a product is out of stock or there is a pricing error.</li>
        <li>Jewelry products are <strong>imitation/artificial gold</strong> and are not real gold. Please read product descriptions carefully.</li>
    </ul>

    <h2>3. Placing Orders</h2>
    <p>When you place an order, you confirm that:</p>
    <ul>
        <li>All information provided is accurate and complete.</li>
        <li>You are legally capable of entering into a binding contract.</li>
        <li>The shipping address provided is valid and accessible for delivery.</li>
    </ul>

    <h2>4. Payment</h2>
    <p>We currently accept Cash on Delivery (COD) as our primary payment method. For online payments, you agree not to engage in any fraudulent transactions. We reserve the right to refuse or cancel any order suspected of fraud.</p>

    <h2>5. Intellectual Property</h2>
    <p>All content on this website | including text, images, logos, and design | is the property of Jenny's Cosmetics &amp; Jewelry and is protected by copyright law. You may not reproduce, distribute, or use any content without our written consent.</p>

    <h2>6. Limitation of Liability</h2>
    <p>Jenny's Cosmetics &amp; Jewelry shall not be liable for any indirect, incidental, or consequential damages arising from the use of our products or website. Our maximum liability is limited to the amount paid for the specific product in question.</p>

    <h2>7. Governing Law</h2>
    <p>These terms shall be governed by the laws of Pakistan. Any disputes shall be resolved in the courts of Karachi, Pakistan.</p>

    <h2>8. Changes to Terms</h2>
    <p>We reserve the right to modify these terms at any time. Continued use of the website after changes constitutes acceptance of the new terms.</p>

    <h2>9. Contact</h2>
    <p>For any questions about these terms, contact us at <a href="mailto:info@jennyscosmetics.com" style="color:var(--primary-gold);">info@jennyscosmetics.com</a>.</p>
</div>

<?php require 'includes/footer.php'; ?>
