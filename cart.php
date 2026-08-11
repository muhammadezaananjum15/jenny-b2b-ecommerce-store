<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<?php require 'includes/header.php'; ?>

<!-- CSS LINK -->
<link rel="stylesheet" href="css/style.css">

<?php require 'includes/navbar.php'; ?>


<!-- ============================================ -->
<!-- CART SECTION -->
<!-- ============================================ -->
<section class="cart-section">
    <div class="cart-container">
        
<!-- CART ITEMS -->
<div class="cart-items-wrapper" id="cartItemsWrapper">
    <!-- Items will be injected here via JS -->
    <div class="empty-cart-message" id="emptyCartMsg" style="display: block;">
        <i class="fas fa-shopping-cart" style="font-size: 4rem; color: #ddd; margin-bottom: 20px; display: block;"></i>
        <h3>Your cart is empty</h3>
        <p>Looks like you haven't added any items to your cart yet.</p>
        <a href="products.php" class="btn-primary" style="display: inline-block; margin-top: 20px;">Start Shopping</a>
    </div>
</div>

        <!-- CART SUMMARY (TOTAL) -->
        <div class="cart-summary-wrapper" id="cartSummaryWrapper">
            <div class="cart-summary" id="cartSummary">
                <h3>Cart Summary</h3>
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span id="cartSubtotal">Rs. 0</span>
                </div>
                <div class="summary-row">
                    <span>Shipping:</span>
                    <span id="cartShipping">Free</span>
                </div>
                <div class="summary-row total-row">
                    <span>Total:</span>
                    <span id="cartTotal">Rs. 0</span>
                </div>
                <div class="cart-actions">
                    <button class="btn-clear-cart" onclick="clearCart()">Clear Cart</button>
                    <button class="btn-primary checkout-btn" onclick="proceedToCheckout()">Proceed to Checkout <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- ============================================ -->
<!-- CART SIDEBAR (DRAWER) - REMOVED FROM HERE -->
<!-- ============================================ -->

<!-- Footer -->
<?php require 'includes/footer.php'; ?>

<!-- JS LINK -->
<script src="includes/js/script.js"></script>
</body>
</html>