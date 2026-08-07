<?php session_start(); ?>
<?php require 'includes/header.php'; ?>
<link rel="stylesheet" href="css/style.css">
<?php require 'includes/navbar.php'; ?>

<style>
.policy-hero {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    padding: 80px 5%;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.policy-hero::before {
    content: '';
    position: absolute;
    top: -50%; left: -50%;
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
    <h1>Privacy <span>Policy</span></h1>
    <p>Your privacy is important to us. Here's how we handle your information.</p>
</section>

<div class="policy-body">
    <p class="last-updated"><i class="fas fa-calendar-alt" style="color:var(--primary-gold);"></i> Last Updated: July 2026</p>

    <h2>1. Information We Collect</h2>
    <p>When you place an order or contact us, we collect the following information:</p>
    <ul>
        <li>Full name, email address, and phone number</li>
        <li>Shipping and billing address</li>
        <li>Order details and purchase history</li>
        <li>Any messages you send through our contact form</li>
    </ul>

    <h2>2. How We Use Your Information</h2>
    <p>We use your personal information solely to:</p>
    <ul>
        <li>Process and fulfill your orders</li>
        <li>Contact you regarding your order status</li>
        <li>Respond to your queries and support requests</li>
        <li>Send promotional offers if you opt in (you can unsubscribe anytime)</li>
    </ul>

    <h2>3. Information Sharing</h2>
    <p>We <strong>never</strong> sell, trade, or share your personal information with third parties except where necessary to fulfill your order (e.g., delivery services). All data is stored securely on our servers.</p>

    <h2>4. Cookies</h2>
    <p>We use cookies to maintain your shopping cart session and improve your browsing experience. You can disable cookies in your browser settings, but this may affect site functionality.</p>

    <h2>5. Data Security</h2>
    <p>Your data is protected using industry-standard encryption and secure server practices. We do not store payment card details — all payments are processed through trusted payment gateways.</p>

    <h2>6. Your Rights</h2>
    <p>You have the right to request access to, correction of, or deletion of your personal data at any time. Contact us at <a href="mailto:info@jennyscosmetics.com" style="color:var(--primary-gold);">info@jennyscosmetics.com</a> for any such requests.</p>

    <h2>7. Contact Us</h2>
    <p>If you have questions about this Privacy Policy, please reach out through our <a href="contact.php" style="color:var(--primary-gold);">Contact Page</a> or email us at <a href="mailto:info@jennyscosmetics.com" style="color:var(--primary-gold);">info@jennyscosmetics.com</a>.</p>
</div>

<?php require 'includes/footer.php'; ?>
