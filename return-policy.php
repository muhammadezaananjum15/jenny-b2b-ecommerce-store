<?php session_start(); ?>
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
.return-card {
    background: var(--white);
    border: 1px solid #eee;
    border-radius: 14px;
    padding: 24px;
    margin-bottom: 20px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.05);
    display: flex;
    gap: 18px;
    align-items: flex-start;
}
.return-card-icon {
    width: 48px; height: 48px; flex-shrink: 0;
    background: linear-gradient(135deg, #fdf3cc, #fde98a);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; color: #7a5c00;
}
.return-card h3 { font-family: var(--font-heading); font-size: 1rem; color: var(--dark-black); margin-bottom: 6px; }
.return-card p { margin: 0; font-size: 0.9rem; }
.return-not-eligible { background: #fff8f8; border: 1px solid #fde8e8; border-radius: 10px; padding: 16px 20px; margin: 20px 0; }
.return-not-eligible h4 { color: #e74c3c; font-family: var(--font-heading); margin-bottom: 8px; }
</style>

<section class="policy-hero">
    <h1>Return <span>Policy</span></h1>
    <p>We want you to love your purchase. Here's our hassle-free return &amp; exchange policy.</p>
</section>

<div class="policy-body">
    <p class="last-updated"><i class="fas fa-calendar-alt" style="color:var(--primary-gold);"></i> Last Updated: July 2026</p>

    <div class="return-card">
        <div class="return-card-icon"><i class="fas fa-undo-alt"></i></div>
        <div>
            <h3>7-Day Easy Returns</h3>
            <p>We accept returns within <strong>7 days</strong> of delivery if the product is unused, undamaged, and in its original packaging.</p>
        </div>
    </div>
    <div class="return-card">
        <div class="return-card-icon"><i class="fas fa-exchange-alt"></i></div>
        <div>
            <h3>Free Exchange</h3>
            <p>If you received the wrong item or a defective product, we'll replace it at <strong>no extra cost</strong>. Just contact us within 48 hours of delivery.</p>
        </div>
    </div>
    <div class="return-card">
        <div class="return-card-icon"><i class="fas fa-rupee-sign"></i></div>
        <div>
            <h3>Refund Process</h3>
            <p>Once your returned item is received and inspected, the refund will be processed within <strong>3–5 business days</strong> via the original payment method or store credit.</p>
        </div>
    </div>

    <h2>How to Initiate a Return</h2>
    <ol style="padding-left:20px;color:var(--text-grey);line-height:1.8;font-size:0.95rem;">
        <li>Contact us via WhatsApp or email within 7 days of receiving your order.</li>
        <li>Provide your Order ID and photos of the item(s) and packaging.</li>
        <li>Our team will guide you through the return shipping process.</li>
        <li>Once received, we process your refund or exchange within 3–5 days.</li>
    </ol>

    <div class="return-not-eligible">
        <h4><i class="fas fa-times-circle"></i> Items NOT Eligible for Return</h4>
        <ul>
            <li>Products that have been used, washed, or damaged after delivery</li>
            <li>Items returned without original packaging or tags</li>
            <li>Earrings and intimate jewelry items (for hygiene reasons)</li>
            <li>Sale or heavily discounted items (unless defective)</li>
            <li>Items returned after the 7-day window</li>
        </ul>
    </div>

    <h2>Damaged or Wrong Items</h2>
    <p>If you receive a damaged, defective, or incorrect item, please contact us within <strong>48 hours</strong> of delivery with photos. We will arrange a free replacement or full refund immediately.</p>

    <h2>Shipping Costs for Returns</h2>
    <p>For returns due to our error (wrong/defective item), we cover the shipping cost. For returns due to personal preference (e.g., change of mind), the customer is responsible for return shipping charges.</p>

    <h2>Contact Us for Returns</h2>
    <p>📞 Phone/WhatsApp: <strong>+92 300 1234567</strong><br>
    📧 Email: <a href="mailto:support@jennyscosmetics.com" style="color:var(--primary-gold);">support@jennyscosmetics.com</a></p>
</div>

<?php require 'includes/footer.php'; ?>
