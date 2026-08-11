<!-- Font Awesome 6.5 is loaded by header.php | no duplicate here -->

<!-- footer.php -->
<style>
    /* ========================================== */
    /* --- PROFESSIONAL FOOTER STYLES --- */
    /* ========================================== */
    .site-footer {
        background-color: #111111;
        color: #cccccc;
        padding: 60px 5% 0 5%;
        border-top: 4px solid var(--primary-gold);
        margin-top: 60px;
    }

    .footer-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 40px;
        max-width: 1200px;
        margin: 0 auto 40px auto;
    }

    .footer-col h4 {
        color: var(--white);
        font-family: var(--font-heading);
        font-size: 1.2rem;
        margin-bottom: 20px;
        position: relative;
        padding-bottom: 10px;
    }
    .footer-col h4::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 40px;
        height: 2px;
        background: var(--primary-gold);
    }

    .brand-col .footer-logo {
        font-family: var(--font-heading);
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--white);
        margin-bottom: 15px;
    }
    .brand-col .footer-logo span { color: var(--primary-gold); }
    .footer-about { font-size: 0.9rem; line-height: 1.6; margin-bottom: 20px; }

    .footer-social { display: flex; gap: 12px; }
    .footer-social a {
        display: flex; align-items: center; justify-content: center;
        width: 40px; height: 40px; background: #222222; color: #cccccc;
        border-radius: 50%; transition: all 0.3s ease; font-size: 1rem; text-decoration: none;
    }
    .footer-social a:hover {
        background: var(--primary-gold); color: #111111;
        transform: translateY(-3px); box-shadow: 0 5px 15px rgba(244, 180, 0, 0.3);
    }

    .links-col ul li { margin-bottom: 12px; }
    .links-col ul li a {
        color: #cccccc; text-decoration: none; font-size: 0.9rem;
        transition: all 0.3s ease; display: flex; align-items: center; gap: 8px;
    }
    .links-col ul li a i { font-size: 0.6rem; color: var(--primary-gold); transition: all 0.3s ease; }
    .links-col ul li a:hover { color: var(--primary-gold); padding-left: 5px; }
    .links-col ul li a:hover i { transform: translateX(4px); }

    .newsletter-col p { font-size: 0.9rem; margin-bottom: 15px; line-height: 1.5; }
    .footer-newsletter {
        display: flex; border: 1px solid #333; border-radius: 30px;
        overflow: hidden; margin-bottom: 20px; background: #1a1a1a;
    }
    .footer-newsletter input {
        flex: 1; padding: 12px 15px; border: none; outline: none;
        background: transparent; color: var(--white); font-family: var(--font-body);
    }
    .footer-newsletter input::placeholder { color: #777; }
    .footer-newsletter button {
        padding: 0 20px; background: var(--primary-gold); color: #111;
        border: none; cursor: pointer; font-size: 1.1rem; transition: 0.3s;
    }
    .footer-newsletter button:hover { background: #d19c00; }

    .footer-contact p { font-size: 0.85rem; margin-bottom: 8px; display: flex; align-items: center; gap: 10px; }
    .footer-contact p i { color: var(--primary-gold); width: 20px; }

    .footer-bottom { border-top: 1px solid #222; padding: 20px 0; text-align: center; font-size: 0.85rem; color: #777; }
    .footer-bottom strong { color: #aaa; }

    @media (max-width: 992px) { .footer-container { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 600px) {
        .footer-container { grid-template-columns: 1fr; text-align: center; }
        .footer-col h4::after { left: 50%; transform: translateX(-50%); }
        .footer-social { justify-content: center; }
        .footer-newsletter { max-width: 300px; margin: 0 auto 20px auto; }
        .footer-contact p { justify-content: center; }
    }
</style>

<!-- PROFESSIONAL FOOTER HTML -->
<!-- PROFESSIONAL FOOTER HTML -->
<footer class="site-footer" role="contentinfo">
    <div class="footer-container">
        <div class="footer-col brand-col">
            <div class="footer-logo">
                <i class="fas fa-gem" style="color: var(--primary-gold);"></i> Jenny's <span>Cosmetics</span>
            </div>
            <p class="footer-about">
                Premium cosmetics & imitation jewelry bringing out the beauty in you. 
                Quality, trust, and elegance since day one.
            </p>
            <div class="footer-social">
                <a href="https://www.facebook.com/" target="_blank" title="Facebook" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="https://www.instagram.com/" target="_blank" title="Instagram" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="https://www.youtube.com/" target="_blank" title="YouTube" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                <a href="https://x.com/" target="_blank" title="Twitter/X" aria-label="Twitter X"><i class="fa-brands fa-x-twitter"></i></a>
                <a href="https://www.pinterest.com/" target="_blank" title="Pinterest" aria-label="Pinterest"><i class="fab fa-pinterest-p"></i></a>
            </div>
        </div>

        <div class="footer-col links-col">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="index.php"><i class="fas fa-chevron-right"></i> Home</a></li>
                <li><a href="cosmetics.php"><i class="fas fa-chevron-right"></i> Cosmetics</a></li>
                <li><a href="imitation-jewelry.php"><i class="fas fa-chevron-right"></i> Imitation Jewelry</a></li>
                <li><a href="new-arrivals.php"><i class="fas fa-chevron-right"></i> New Arrivals</a></li>
                <li><a href="best-sellers.php"><i class="fas fa-chevron-right"></i> Best Sellers</a></li>
            </ul>
        </div>

        <div class="footer-col links-col">
            <h4>Support</h4>
            <ul>
                <li><a href="about.php"><i class="fas fa-chevron-right"></i> About Us</a></li>
                <li><a href="contact.php"><i class="fas fa-chevron-right"></i> Contact Us</a></li>
                <li><a href="privacy-policy.php"><i class="fas fa-chevron-right"></i> Privacy Policy</a></li>
                <li><a href="terms-conditions.php"><i class="fas fa-chevron-right"></i> Terms & Conditions</a></li>
                <li><a href="return-policy.php"><i class="fas fa-chevron-right"></i> Return Policy</a></li>
            </ul>
        </div>

        <div class="footer-col newsletter-col">
            <h4>Stay in the Loop</h4>
            <p>Subscribe to get exclusive offers, new arrivals, and beauty tips directly in your inbox.</p>
            <form class="footer-newsletter" onsubmit="event.preventDefault(); showNotification('Thank you for subscribing!');">
                <input type="email" placeholder="Enter your email address" aria-label="Newsletter email address" required>
                <button type="submit" aria-label="Subscribe to newsletter"><i class="fas fa-paper-plane"></i></button>
            </form>
            <div class="footer-contact">
                <p><i class="fas fa-phone-alt"></i> +92 300 1234567</p>
                <p><i class="fas fa-envelope"></i> info@jennyscosmetics.com</p>
            </div>
        </div>
    </div>

    <div class="footer-bottom" style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
        <div class="payment-trust-badges" style="display: flex; gap: 15px; font-size: 1.4rem; color: #888; margin-bottom: 5px;">
            <i class="fab fa-cc-visa" title="Visa"></i>
            <i class="fab fa-cc-mastercard" title="Mastercard"></i>
            <i class="fab fa-cc-apple-pay" title="Apple Pay"></i>
            <i class="fas fa-money-bill-wave" title="Cash on Delivery"></i>
            <i class="fas fa-shield-alt" title="Secure SSL Checkout"></i>
        </div>
        <p>&copy; 2026 <strong>Jenny's Cosmetics & Imitation Jewelry</strong>. All Rights Reserved.</p>
    </div>
</footer>

<!-- SCROLL TO TOP BUTTON -->
<button id="scrollTopBtn" onclick="scrollToTop()" aria-label="Scroll to top" title="Back to top">
    <i class="fas fa-chevron-up"></i>
</button>

<style>
#scrollTopBtn {
    position: fixed;
    bottom: 28px;
    right: 28px;
    width: 46px;
    height: 46px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-gold) 0%, #D19C00 100%);
    color: var(--dark-black);
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    box-shadow: 0 8px 25px rgba(244, 180, 0, 0.4);
    opacity: 0;
    visibility: hidden;
    transform: translateY(15px);
    transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
    z-index: 999;
}
#scrollTopBtn.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}
#scrollTopBtn:hover {
    transform: translateY(-4px) scale(1.08);
    box-shadow: 0 12px 30px rgba(244, 180, 0, 0.5);
    background: linear-gradient(135deg, #FFD740 0%, var(--primary-gold) 100%);
}
@media (max-width: 576px) {
    #scrollTopBtn { bottom: 20px; right: 20px; width: 40px; height: 40px; font-size: 0.95rem; }
}
</style>

<!-- PRODUCT QUICK VIEW PREVIEW MODAL -->
<div class="product-popup-overlay" id="productPopupOverlay" onclick="closeProductPopup()"></div>
<div class="product-popup-modal" id="productPopupModal">
    <button class="popup-close" onclick="closeProductPopup()"><i class="fas fa-times"></i></button>
    <div class="popup-content">
        <div class="popup-image">
            <img id="popupProductImage" src="img/foundation.jpg" alt="Product Image">
        </div>
        <div class="popup-details">
            <h2 id="popupProductName">Product Name</h2>
            <div class="popup-price" id="popupProductPrice">Rs. 0</div>
            <div class="popup-description">
                <h4>Description</h4>
                <p id="popupProductDesc">Product description will appear here.</p>
            </div>
            <div class="popup-qty">
                <label>Quantity:</label>
                <div class="qty-box">
                    <button type="button" onclick="changePopupQty(-1)">-</button>
                    <span id="popupQty">1</span>
                    <button type="button" onclick="changePopupQty(1)">+</button>
                </div>
            </div>
            <button class="btn-primary" style="width:100%;padding:14px;border-radius:30px;font-size:1rem;font-weight:600;" onclick="addToCartFromPopup()">
                <i class="fas fa-shopping-bag"></i> Add to Cart
            </button>
        </div>
    </div>
</div>

<!-- AOS JS -->
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

<!-- Floating WhatsApp Button (Right Side, above scroll-to-top) -->
<a href="https://wa.me/923000000000?text=Hello%20Jenny%27s%20Cosmetics%2C%20I%20have%20an%20inquiry" 
   class="whatsapp-float-btn" 
   target="_blank" 
   rel="noopener noreferrer" 
   aria-label="Chat on WhatsApp"
   title="Chat with us on WhatsApp"
   style="position:fixed !important; bottom:88px !important; right:28px !important; left:auto !important; width:56px !important; height:56px !important; background:linear-gradient(135deg, #25D366 0%, #128C7E 100%) !important; color:#ffffff !important; border-radius:50% !important; display:flex !important; align-items:center !important; justify-content:center !important; font-size:2rem !important; box-shadow:0 8px 25px rgba(37, 211, 102, 0.45) !important; z-index:99999 !important; text-decoration:none !important; cursor:pointer !important; transition:all 0.3s ease !important;">
    <i class="fab fa-whatsapp" style="color:#ffffff !important; font-size:2.1rem !important; margin:0 !important; line-height:1 !important; pointer-events:none !important;"></i>
    <span class="whatsapp-tooltip">Chat with Us</span>
</a>

<script>
AOS.init({ duration:1000, once:true });
</script>
<!-- Custom JS -->
<script src="includes/js/script.js"></script>

<script>
/* ── Dynamic header offset ──────────────────────────────────────────────── */
function syncHeaderOffset() {
    var hdr = document.getElementById('stickyHeader');
    if (hdr) document.body.style.paddingTop = hdr.offsetHeight + 'px';
}
syncHeaderOffset();
window.addEventListener('resize', syncHeaderOffset);

/* ── NOTE: toggleMobileNav() is defined in includes/js/script.js ─────────── */
/* ── It is NOT redefined here to avoid conflicts. ────────────────────────── */

/* ── Scroll-to-top button handler ────────────────────────────────────────── */
window.addEventListener('scroll', function() {
    var btn = document.getElementById('scrollTopBtn');
    if (btn) {
        if (window.scrollY > 300) btn.classList.add('show');
        else btn.classList.remove('show');
    }
}, { passive: true });

function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

/* ── User dropdown ──────────────────────────────────────────────────────── */
document.addEventListener('click', function(e) {
    if (!e.target.closest('.avatar-container')) {
        var dd = document.getElementById('userDropdown');
        if (dd) dd.classList.remove('show');
    }
});
</script>