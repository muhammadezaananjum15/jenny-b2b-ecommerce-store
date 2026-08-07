<?php session_start(); ?>

<!-- ============================================ -->
<!-- CUSTOM CSS FOR STEP-BY-STEP CHECKOUT -->
<!-- ============================================ -->
<style>
    /* --- CHECKOUT WRAPPER --- */
    .checkout-wrapper {
        padding: 60px 5%;
        background: var(--light-bg);
        min-height: 70vh;
    }
    .checkout-container {
        max-width: 800px;
        margin: 0 auto;
        background: var(--white);
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    }

    /* --- CHECKOUT HEADER --- */
    .checkout-header h1 {
        font-family: var(--font-heading);
        text-align: center;
        color: var(--dark-black);
        margin-bottom: 30px;
        font-size: 2rem;
    }
    .step-indicator {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 40px;
    }
    .step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
        color: #ccc;
        transition: 0.3s;
        cursor: default;
    }
    .step-item.active {
        color: var(--primary-gold);
    }
    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #eee;
        color: #999;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        transition: 0.3s;
    }
    .step-item.active .step-number {
        background: var(--primary-gold);
        color: var(--dark-black);
        box-shadow: 0 4px 15px rgba(244, 180, 0, 0.3);
    }
    .step-label {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .step-line {
        width: 60px;
        height: 2px;
        background: #eee;
        transition: 0.3s;
    }
    .step-item.active + .step-line {
        background: var(--primary-gold);
    }

    /* --- CHECKOUT STEPS --- */
    .checkout-step {
        display: none;
        animation: fadeIn 0.4s ease;
    }
    .checkout-step.active {
        display: block;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* --- FORM ELEMENTS --- */
    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }
    .form-group {
        flex: 1;
        margin-bottom: 15px;
    }
    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 5px;
        color: var(--dark-black);
        font-size: 0.9rem;
    }
    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 10px;
        font-family: var(--font-body);
        font-size: 0.95rem;
        outline: none;
        transition: 0.3s;
    }
    .form-group input.error,
    .form-group textarea.error {
        border-color: #e74c3c;
        background: #fff6f6;
    }
    .form-group input:focus,
    .form-group textarea:focus {
        border-color: var(--primary-gold);
        box-shadow: 0 0 5px rgba(244, 180, 0, 0.2);
    }

    /* --- ERROR MESSAGES --- */
    .error-message {
        display: none;
        color: #e74c3c;
        font-size: 0.8rem;
        margin-top: 5px;
        font-weight: 500;
    }

    /* --- PAYMENT OPTIONS --- */
    .payment-options {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin: 20px 0;
    }
    .payment-option {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px 20px;
        border: 2px solid #eee;
        border-radius: 12px;
        cursor: pointer;
        transition: 0.3s;
        background: #fafafa;
    }
    .payment-option:hover {
        border-color: #ddd;
        background: #f5f5f5;
    }
    .payment-option:has(input:checked) {
        border-color: var(--primary-gold);
        background: #fff8e6;
    }
    .payment-option input[type="radio"] {
        accent-color: var(--primary-gold);
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
    .payment-option i {
        color: var(--primary-gold);
        font-size: 1.3rem;
        width: 30px;
        text-align: center;
    }
    .payment-option span {
        font-weight: 500;
        color: var(--dark-black);
    }

    /* --- STEP ACTIONS --- */
    .step-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        justify-content: flex-end;
    }
    .btn-continue {
        padding: 14px 40px;
        font-size: 1rem;
    }
    .btn-outline {
        background: transparent;
        color: var(--dark-black);
        padding: 14px 30px;
        border-radius: 30px;
        border: 1px solid #ddd;
        cursor: pointer;
        transition: 0.3s;
        font-weight: 600;
        font-family: var(--font-body);
    }
    .btn-outline:hover {
        background: #f5f5f5;
        border-color: #bbb;
    }

    /* --- REVIEW LAYOUT (STEP 3) --- */
    .review-layout {
        display: flex;
        gap: 40px;
        flex-wrap: wrap;
    }
    .review-details {
        flex: 1;
        min-width: 250px;
    }
    .review-summary {
        flex: 1;
        min-width: 250px;
    }
    .review-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 0.95rem;
    }
    .review-label {
        color: var(--text-grey);
        font-weight: 500;
    }
    .review-value {
        color: var(--dark-black);
        font-weight: 600;
    }

    /* --- ORDER SUMMARY (INSIDE REVIEW) --- */
    .order-items {
        max-height: 250px;
        overflow-y: auto;
        margin-bottom: 15px;
    }
    .order-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }
    .order-item-img {
        width: 50px;
        height: 50px;
        object-fit: contain;
        border-radius: 5px;
        border: 1px solid #eee;
    }
    .order-item-details {
        flex: 1;
    }
    .order-item-name {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--dark-black);
    }
    .order-item-price {
        color: var(--text-grey);
        font-size: 0.8rem;
    }
    .order-item-total {
        font-weight: 700;
        color: var(--dark-black);
    }
    .order-totals {
        border-top: 1px solid #eee;
        padding-top: 15px;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 5px 0;
        font-size: 0.9rem;
        color: var(--text-grey);
    }
    .summary-row.total-row {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--dark-black);
        border-top: 1px solid #eee;
        padding-top: 10px;
        margin-top: 5px;
    }

    /* --- RESPONSIVE --- */
    @media (max-width: 768px) {
        .checkout-container { padding: 20px; }
        .form-row { flex-direction: column; gap: 0; }
        .step-line { width: 30px; }
        .step-actions { flex-direction: column; }
        .btn-continue, .btn-outline { width: 100%; justify-content: center; }
        .review-layout { flex-direction: column; }
    }
</style>

<?php require 'includes/header.php'; ?>

<!-- CSS LINK -->
<link rel="stylesheet" href="css/style.css">

<?php require 'includes/navbar.php'; ?>

<!-- ============================================ -->
<!-- CHECKOUT STEP-BY-STEP SECTION -->
<!-- ============================================ -->
<section class="checkout-wrapper">
    <div class="checkout-container">
        
        <!-- HEADER: STEP INDICATOR -->
        <div class="checkout-header">
            <h1>Checkout</h1>
            <div class="step-indicator">
                <div class="step-item active" data-step="1">
                    <span class="step-number">1</span>
                    <span class="step-label">Address</span>
                </div>
                <div class="step-line"></div>
                <div class="step-item" data-step="2">
                    <span class="step-number">2</span>
                    <span class="step-label">Payment</span>
                </div>
                <div class="step-line"></div>
                <div class="step-item" data-step="3">
                    <span class="step-number">3</span>
                    <span class="step-label">Review</span>
                </div>
            </div>
        </div>

        <!-- STEP 1: ADDRESS & BILLING -->
        <div class="checkout-step active" id="step1">
            <div class="form-row">
                <div class="form-group">
                    <label>Full Name <span style="color:red;">*</span></label>
                    <input type="text" id="fullName" placeholder="e.g. Ayesha Khan" required>
                    <div class="error-message" id="fullNameError">This field is required</div>
                </div>
                <div class="form-group">
                    <label>Phone Number <span style="color:red;">*</span></label>
                    <input type="tel" id="phone" placeholder="+92 300 1234567" required>
                    <div class="error-message" id="phoneError">This field is required</div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Email Address <span style="color:red;">*</span></label>
                <input type="email" id="email" placeholder="ayesha@example.com" required>
                <div class="error-message" id="emailError">This field is required</div>
            </div>
            
            <div class="form-group">
                <label>Address <span style="color:red;">*</span></label>
                <textarea id="address" rows="3" placeholder="House 12, Block 5, Gulshan-e-Iqbal, Karachi" required></textarea>
                <div class="error-message" id="addressError">This field is required</div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>City <span style="color:red;">*</span></label>
                    <input type="text" id="city" placeholder="e.g. Karachi" required>
                    <div class="error-message" id="cityError">This field is required</div>
                </div>
                <div class="form-group">
                    <label>Zip / Postal Code <span style="color:red;">*</span></label>
                    <input type="text" id="zip" placeholder="e.g. 75300" required>
                    <div class="error-message" id="zipError">This field is required</div>
                </div>
            </div>

            <div class="form-group">
                <label>Order Notes (Optional)</label>
                <textarea id="orderNotes" rows="2" placeholder="Any special instructions?"></textarea>
            </div>

            <div class="step-actions">
                <button class="btn-primary btn-continue" onclick="validateStep1()">Continue to Payment <i class="fas fa-arrow-right"></i></button>
            </div>
        </div>

        <!-- STEP 2: PAYMENT METHOD -->
        <div class="checkout-step" id="step2">
            <h3>Payment Method</h3>
        <div class="payment-options">
                <label class="payment-option selected">
                    <input type="radio" name="paymentMethod" value="cod" checked>
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Cash on Delivery (COD)</span>
                </label>
                <label class="payment-option">
                    <input type="radio" name="paymentMethod" value="easypaisa">
                    <i class="fas fa-mobile-alt"></i>
                    <span>EasyPaisa</span>
                </label>
                <label class="payment-option">
                    <input type="radio" name="paymentMethod" value="jazzcash">
                    <i class="fas fa-wallet"></i>
                    <span>JazzCash</span>
                </label>
                <label class="payment-option">
                    <input type="radio" name="paymentMethod" value="bank">
                    <i class="fas fa-university"></i>
                    <span>Bank Transfer</span>
                </label>
            </div>

            <div class="step-actions">
                <button class="btn-outline" onclick="goToStep(1)"><i class="fas fa-arrow-left"></i> Back</button>
                <button class="btn-primary btn-continue" onclick="goToStep(3)">Continue to Review <i class="fas fa-arrow-right"></i></button>
            </div>
        </div>

        <!-- STEP 3: REVIEW & PLACE ORDER -->
        <div class="checkout-step" id="step3">
            <div class="review-layout">
                <!-- LEFT: DETAILS REVIEW -->
                <div class="review-details">
                    <h3>Delivery Address</h3>
                    <div class="review-item">
                        <span class="review-label">Name:</span>
                        <span class="review-value" id="reviewName">-</span>
                    </div>
                    <div class="review-item">
                        <span class="review-label">Phone:</span>
                        <span class="review-value" id="reviewPhone">-</span>
                    </div>
                    <div class="review-item">
                        <span class="review-label">Email:</span>
                        <span class="review-value" id="reviewEmail">-</span>
                    </div>
                    <div class="review-item">
                        <span class="review-label">Address:</span>
                        <span class="review-value" id="reviewAddress">-</span>
                    </div>
                    <div class="review-item">
                        <span class="review-label">City / Zip:</span>
                        <span class="review-value" id="reviewCityZip">-</span>
                    </div>
                    <div class="review-item">
                        <span class="review-label">Payment:</span>
                        <span class="review-value" id="reviewPayment">-</span>
                    </div>
                </div>

                <!-- RIGHT: ORDER SUMMARY -->
                <div class="review-summary">
                    <h3>Order Summary</h3>
                    <div class="order-items" id="orderItems">
                        <!-- Items will be injected here via JS -->
                    </div>
                    
                    <div class="order-totals">
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span id="orderSubtotal">Rs. 0</span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping:</span>
                            <span id="orderShipping">Free</span>
                        </div>
                        <div class="summary-row total-row">
                            <span>Total:</span>
                            <span id="orderTotal">Rs. 0</span>
                        </div>
                    </div>

                    <div class="step-actions">
                        <button class="btn-outline" onclick="goToStep(2)"><i class="fas fa-arrow-left"></i> Back</button>
                        <button class="btn-primary btn-continue" onclick="validateAndPlaceOrder()">Place Order <i class="fas fa-check-circle"></i></button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- ============================================ -->
<!-- CART SIDEBAR (DRAWER) - REMOVED -->
<!-- ============================================ -->

<!-- Footer -->
<?php require 'includes/footer.php'; ?>

<!-- JS LINK -->
<script src="includes/js/script.js"></script>

<!-- ============================================ -->
<!-- CHECKOUT STEP LOGIC (INLINE JS) -->
<!-- ============================================ -->
<script>
    // --- 1. STEP NAVIGATION ---
    function goToStep(step) {
        // Hide all steps
        document.querySelectorAll('.checkout-step').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.step-item').forEach(el => el.classList.remove('active'));

        // Show selected step
        document.getElementById('step' + step).classList.add('active');
        document.querySelector('.step-item[data-step="' + step + '"]').classList.add('active');

        // If going to Step 3 (Review), load summary
        if (step === 3) {
            loadReviewSummary();
        }
    }

    // --- 2. HIDE ALL ERRORS ---
    function hideAllErrors() {
        document.querySelectorAll('.error-message').forEach(el => {
            el.style.display = 'none';
        });
        document.querySelectorAll('.form-group input, .form-group textarea').forEach(el => {
            el.classList.remove('error');
        });
    }

    // --- 3. SHOW SPECIFIC ERROR ---
    function showError(fieldId, errorId, message) {
        const field = document.getElementById(fieldId);
        const errorDiv = document.getElementById(errorId);
        if (field && errorDiv) {
            field.classList.add('error');
            errorDiv.innerText = message;
            errorDiv.style.display = 'block';
        }
    }

    // --- 4. VALIDATE STEP 1 (ADDRESS) ---
    function validateStep1() {
        // Hide all errors first
        hideAllErrors();
        
        let isValid = true;
        
        const fullName = document.getElementById('fullName').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const email = document.getElementById('email').value.trim();
        const address = document.getElementById('address').value.trim();
        const city = document.getElementById('city').value.trim();
        const zip = document.getElementById('zip').value.trim();

        // --- Validate Full Name (Only letters and spaces) ---
        const nameRegex = /^[A-Za-z\s]+$/;
        if (!fullName) {
            showError('fullName', 'fullNameError', 'This field is required');
            isValid = false;
        } else if (!nameRegex.test(fullName)) {
            showError('fullName', 'fullNameError', 'Numbers are not allowed');
            isValid = false;
        }

        // --- Validate Phone ---
        const phoneRegex = /^[0-9+\-\s]{10,}$/;
        if (!phone) {
            showError('phone', 'phoneError', 'This field is required');
            isValid = false;
        } else if (!phoneRegex.test(phone)) {
            showError('phone', 'phoneError', 'Please enter a valid phone number');
            isValid = false;
        }

        // --- Validate Email ---
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email) {
            showError('email', 'emailError', 'This field is required');
            isValid = false;
        } else if (!emailRegex.test(email)) {
            showError('email', 'emailError', 'Please enter a valid email address');
            isValid = false;
        }

        // --- Validate Address ---
        if (!address) {
            showError('address', 'addressError', 'This field is required');
            isValid = false;
        }

        // --- Validate City ---
        if (!city) {
            showError('city', 'cityError', 'This field is required');
            isValid = false;
        }

        // --- Validate Zip ---
        if (!zip) {
            showError('zip', 'zipError', 'This field is required');
            isValid = false;
        }

        // If all valid, go to step 2
        if (isValid) {
            goToStep(2);
        }
    }

    // --- 5. LOAD REVIEW SUMMARY (STEP 3) ---
    function loadReviewSummary() {
        const fullName = document.getElementById('fullName').value || '-';
        const phone = document.getElementById('phone').value || '-';
        const email = document.getElementById('email').value || '-';
        const address = document.getElementById('address').value || '-';
        const city = document.getElementById('city').value || '-';
        const zip = document.getElementById('zip').value || '-';
        const payment = document.querySelector('input[name="paymentMethod"]:checked');
        const paymentLabel = payment ? payment.parentElement.querySelector('span').innerText : '-';

        document.getElementById('reviewName').innerText = fullName;
        document.getElementById('reviewPhone').innerText = phone;
        document.getElementById('reviewEmail').innerText = email;
        document.getElementById('reviewAddress').innerText = address;
        document.getElementById('reviewCityZip').innerText = city + ' - ' + zip;
        document.getElementById('reviewPayment').innerText = paymentLabel;
    }

    // --- 6. VALIDATE AND PLACE ORDER (STEP 3) ---
    function validateAndPlaceOrder() {
        // Call the existing placeOrder function from script.js
        if (typeof placeOrder === 'function') {
            // Create a fake event object
            const fakeEvent = { preventDefault: function() {} };
            placeOrder(fakeEvent);
        } else {
            // Fallback if placeOrder is not defined in script.js
            window.location.href = 'order-confirmation.php';
        }
    }

    // --- 7. LOAD ORDER SUMMARY (FROM YOUR EXISTING JS) ---
    window.onload = function() {
        if (document.getElementById('orderItems')) {
            loadOrderSummary();
        }
        updateCartUI();
    };
</script>
</body>
</html>