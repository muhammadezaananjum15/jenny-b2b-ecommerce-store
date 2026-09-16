<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<?php require_once 'config/db.php'; ?>

<?php
$msg = '';
$msgType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $msg = 'Please fill out all required fields.';
        $msgType = 'error';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $phone, $subject, $message]);
            $msg = 'Thank you! Your message has been sent successfully. We will get back to you within 24 hours.';
            $msgType = 'success';
        } catch (PDOException $e) {
            $msg = 'Failed to send message. Please try again later.';
            $msgType = 'error';
        }
    }
}
?>

<?php require 'includes/header.php'; ?>
<!-- CSS LINK -->
<link rel="stylesheet" href="css/style.css">
<?php require 'includes/navbar.php'; ?>

<section class="contact-hero">
    <canvas id="contactParticleCanvas" style="position:absolute;top:0;left:0;width:100%;height:100%;z-index:1;pointer-events:none;"></canvas>
    <div class="contact-hero-content" data-aos="fade-up">
        <h1>Contact <span>Us</span></h1>
        <p>Have a question about our cosmetics or jewelry? Need assistance with an order? We are here to help!</p>
    </div>
</section>

<section class="contact-section" id="contact-form">
    <div class="contact-container">
        
        <!-- LEFT: CONTACT FORM -->
        <div class="contact-form-card" data-aos="fade-right">
            <div class="contact-form-header">
                <h2>Send Us a Message</h2>
                <p>Fill out the form below and our beauty experts will get back to you shortly.</p>
            </div>

            <?php if ($msg): ?>
            <div style="padding:14px 18px;border-radius:10px;margin-bottom:20px;font-size:0.9rem;display:flex;align-items:center;gap:10px;<?= $msgType==='success'?'background:rgba(46,204,113,0.15);border:1px solid #2ecc71;color:#2ecc71;':'background:rgba(231,76,60,0.15);border:1px solid #e74c3c;color:#e74c3c;' ?>">
                <i class="fas <?= $msgType==='success'?'fa-check-circle':'fa-exclamation-circle' ?>"></i>
                <?= htmlspecialchars($msg) ?>
            </div>
            <?php endif; ?>
            
            <form class="contact-form" method="POST" action="contact.php">
                <div class="form-group">
                    <label for="contactName">Your Name *</label>
                    <input type="text" id="contactName" name="name" placeholder="Enter your full name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                    <div class="form-group">
                        <label for="contactEmail">Your Email *</label>
                        <input type="email" id="contactEmail" name="email" placeholder="Enter your email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="contactPhone">Phone Number</label>
                        <input type="tel" id="contactPhone" name="phone" placeholder="+92 300 1234567" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="contactSubject">Subject *</label>
                    <input type="text" id="contactSubject" name="subject" placeholder="What is this regarding?" required value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="contactMessage">Message *</label>
                    <textarea id="contactMessage" name="message" rows="5" placeholder="Write your message here..." required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                </div>
                <button type="submit" class="btn-primary contact-submit-btn">
                    Send Message <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>

        <!-- RIGHT: CONTACT INFO -->
        <div class="contact-info-card" data-aos="fade-left">
            <div class="contact-info-header">
                <h2>Get in Touch</h2>
                <p>Reach out to us through any of the channels below.</p>
            </div>
            
            <div class="contact-info-item">
                <div class="contact-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div>
                    <h4>Visit Us</h4>
                    <p>123 Beauty Lane, Garden City<br>Karachi, Pakistan</p>
                </div>
            </div>
            
            <div class="contact-info-item">
                <div class="contact-icon">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <div>
                    <h4>Call Us</h4>
                    <p>+92 300 1234567</p>
                    <p>+92 321 9876543</p>
                </div>
            </div>
            
            <div class="contact-info-item">
                <div class="contact-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div>
                    <h4>Email Us</h4>
                    <p>info@jennyscosmetics.com</p>
                    <p>support@jennyscosmetics.com</p>
                </div>
            </div>
            
            <div class="contact-info-item">
                <div class="contact-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <h4>Working Hours</h4>
                    <p>Monday - Saturday: 10:00 AM - 8:00 PM</p>
                    <p>Sunday: Closed</p>
                </div>
            </div>
            
            <div class="contact-social">
                <h4>Follow Us</h4>
                <div class="social-links">
                    <a href="https://facebook.com/" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://instagram.com/" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="https://youtube.com/" target="_blank"><i class="fab fa-youtube"></i></a>
                    <a href="https://x.com/" target="_blank"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
        
    </div>
</section>

<section class="map-section" style="padding:0 5% 60px;">
    <h2 class="section-title" style="text-align: center;margin-bottom:30px;">Find Us on <span style="color: var(--primary-gold);">Map</span></h2>
    <div class="map-container" style="border-radius:15px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.1);">
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3619.123456789!2d67.0011!3d24.8607!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjTCsDUxJzM4LjUiTiA2N8KwMDAnMDQuMCJF!5e0!3m2!1sen!2s!4v1234567890" 
            width="100%" 
            height="450" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy">
        </iframe>
    </div>
</section>

<!-- CART SIDEBAR -->
<div class="cart-overlay" id="cartOverlay" onclick="closeCart()"></div>
<div class="cart-sidebar" id="cartSidebar">
    <div class="cart-header">
        <h2>Your Cart</h2>
        <span class="cart-close" onclick="closeCart()"><i class="fas fa-times"></i></span>
    </div>
    <div class="cart-items-container" id="cartItemsContainer">
        <div class="empty-cart-msg" id="emptyCartMsg">
            <i class="fas fa-shopping-bag" style="font-size: 3rem; color: #ddd; margin-bottom: 10px; display: block;"></i>
            Your cart is empty.
        </div>
    </div>
    <div class="cart-footer">
        <div class="cart-total">
            <span>Total:</span>
            <span id="cartTotalPrice">Rs. 0</span>
        </div>
        <button class="checkout-btn" onclick="location.href='checkout.php'">Proceed to Checkout</button>
    </div>
</div>

<!-- Footer -->
<?php require 'includes/footer.php'; ?>
<script src="includes/js/threejs-bg.js"></script>
</body>
</html>