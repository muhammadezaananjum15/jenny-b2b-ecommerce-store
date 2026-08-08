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
    .payment-option.selected,
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
        font-weight: 600;
        color: var(--dark-black);
    }

    /* --- EXPANDABLE PAYMENT DETAILS --- */
    .payment-details-wrapper {
        margin-top: 20px;
    }
    .payment-details-box {
        display: none;
        animation: fadeIn 0.3s ease;
    }
    .payment-details-box.active {
        display: block;
    }
    .payment-info-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
    }
    .payment-info-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 5px; height: 100%;
        background: var(--primary-gold);
    }
    .payment-info-card.easypaisa-info::before { background: #00B140; }
    .payment-info-card.jazzcash-info::before { background: #FF0000; }
    .payment-info-card.bank-info::before { background: #1B365D; }
    
    .info-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f0f0f0;
    }
    .payment-badge-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: #fff;
        flex-shrink: 0;
    }
    .ep-badge { background: linear-gradient(135deg, #00B140, #008730); }
    .jc-badge { background: linear-gradient(135deg, #FF0000, #C00000); }
    .bank-badge { background: linear-gradient(135deg, #1B365D, #0A192F); }
    .cod-badge { background: linear-gradient(135deg, var(--primary-gold), #d19c00); color: #111; }
    
    .info-header h4 {
        margin: 0;
        font-size: 1.05rem;
        color: var(--dark-black);
        font-family: var(--font-heading);
    }
    .status-verified {
        font-size: 0.78rem;
        color: #27ae60;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-top: 2px;
    }
    
    .acc-details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 12px 20px;
        background: #f8fafc;
        padding: 14px;
        border-radius: 10px;
        margin-bottom: 16px;
        border: 1px solid #edf2f7;
    }
    .acc-item {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .acc-label {
        font-size: 0.78rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .acc-value {
        font-size: 0.95rem;
        color: var(--dark-black);
        font-weight: 700;
    }
    .acc-copy-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .btn-copy {
        background: var(--primary-gold);
        color: #111;
        border: none;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        cursor: pointer;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .btn-copy:hover {
        background: #d19c00;
        transform: translateY(-1px);
    }
    
    .transfer-instructions {
        background: #fffdf5;
        border: 1px solid #fef3c7;
        border-radius: 10px;
        padding: 12px 16px;
    }
    .transfer-instructions h5 {
        margin: 0 0 8px 0;
        font-size: 0.88rem;
        color: #92400e;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .transfer-instructions ol,
    .transfer-instructions ul {
        margin: 0;
        padding-left: 20px;
        font-size: 0.85rem;
        color: #4b5563;
        line-height: 1.5;
    }
    .transfer-instructions code {
        background: #fee2e2;
        color: #991b1b;
        padding: 1px 6px;
        border-radius: 4px;
        font-weight: 700;
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
            <h3>Select Payment Method</h3>
            <div class="payment-options">
                <label class="payment-option selected" data-method="cod">
                    <input type="radio" name="paymentMethod" value="cod" checked onchange="switchPaymentDetails('cod')">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Cash on Delivery (COD)</span>
                </label>
                <label class="payment-option" data-method="easypaisa">
                    <input type="radio" name="paymentMethod" value="easypaisa" onchange="switchPaymentDetails('easypaisa')">
                    <i class="fas fa-mobile-alt" style="color:#00B140;"></i>
                    <span>EasyPaisa</span>
                </label>
                <label class="payment-option" data-method="jazzcash">
                    <input type="radio" name="paymentMethod" value="jazzcash" onchange="switchPaymentDetails('jazzcash')">
                    <i class="fas fa-wallet" style="color:#FF0000;"></i>
                    <span>JazzCash</span>
                </label>
                <label class="payment-option" data-method="bank">
                    <input type="radio" name="paymentMethod" value="bank" onchange="switchPaymentDetails('bank')">
                    <i class="fas fa-university" style="color:#1B365D;"></i>
                    <span>Bank Transfer</span>
                </label>
            </div>

            <!-- DYNAMIC PAYMENT DETAILS BOXES -->
            <div class="payment-details-wrapper">
                <!-- COD DETAILS -->
                <div class="payment-details-box active" id="details-cod">
                    <div class="payment-info-card">
                        <div class="info-header">
                            <div class="payment-badge-icon cod-badge"><i class="fas fa-truck"></i></div>
                            <div>
                                <h4>Cash on Delivery (COD)</h4>
                                <span class="status-verified"><i class="fas fa-check-circle"></i> Pay upon parcel arrival</span>
                            </div>
                        </div>
                        <div class="transfer-instructions">
                            <h5><i class="fas fa-info-circle"></i> Payment Instructions:</h5>
                            <ul>
                                <li>Please keep exact cash ready upon delivery to avoid delays.</li>
                                <li>Our delivery agent will collect the payment at your shipping address.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- EASYPAISA DETAILS -->
                <div class="payment-details-box" id="details-easypaisa">
                    <div class="payment-info-card easypaisa-info">
                        <div class="info-header">
                            <div class="payment-badge-icon ep-badge"><i class="fas fa-mobile-alt"></i></div>
                            <div>
                                <h4>EasyPaisa Account Details</h4>
                                <span class="status-verified"><i class="fas fa-shield-alt"></i> Verified Account</span>
                            </div>
                        </div>
                        <div class="acc-details-grid">
                            <div class="acc-item">
                                <span class="acc-label">Account Title</span>
                                <strong class="acc-value">Jenny's Cosmetics & Jewelry</strong>
                            </div>
                            <div class="acc-item">
                                <span class="acc-label">EasyPaisa Mobile Number</span>
                                <div class="acc-copy-wrap">
                                    <strong class="acc-value">0300-1234567</strong>
                                    <button type="button" class="btn-copy" onclick="copyToClipboard('03001234567', 'EasyPaisa Number Copied!')"><i class="fas fa-copy"></i> Copy</button>
                                </div>
                            </div>
                            <div class="acc-item">
                                <span class="acc-label">Account Ref</span>
                                <strong class="acc-value">EP-03001234567</strong>
                            </div>
                        </div>
                        <div class="transfer-instructions">
                            <h5><i class="fas fa-info-circle"></i> How to pay via EasyPaisa:</h5>
                            <ol>
                                <li>Open your EasyPaisa app or dial <code>*786#</code>.</li>
                                <li>Select <strong>Send Money</strong> &rarr; <strong>EasyPaisa Account</strong>.</li>
                                <li>Enter Mobile Number: <code>03001234567</code> and total order amount.</li>
                                <li>Keep the Transaction ID / receipt screenshot to share via WhatsApp.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- JAZZCASH DETAILS -->
                <div class="payment-details-box" id="details-jazzcash">
                    <div class="payment-info-card jazzcash-info">
                        <div class="info-header">
                            <div class="payment-badge-icon jc-badge"><i class="fas fa-wallet"></i></div>
                            <div>
                                <h4>JazzCash Account Details</h4>
                                <span class="status-verified"><i class="fas fa-shield-alt"></i> Verified Account</span>
                            </div>
                        </div>
                        <div class="acc-details-grid">
                            <div class="acc-item">
                                <span class="acc-label">Account Title</span>
                                <strong class="acc-value">Jenny's Cosmetics & Jewelry</strong>
                            </div>
                            <div class="acc-item">
                                <span class="acc-label">JazzCash Mobile Number</span>
                                <div class="acc-copy-wrap">
                                    <strong class="acc-value">0300-7654321</strong>
                                    <button type="button" class="btn-copy" onclick="copyToClipboard('03007654321', 'JazzCash Number Copied!')"><i class="fas fa-copy"></i> Copy</button>
                                </div>
                            </div>
                            <div class="acc-item">
                                <span class="acc-label">Account Ref</span>
                                <strong class="acc-value">JC-03007654321</strong>
                            </div>
                        </div>
                        <div class="transfer-instructions">
                            <h5><i class="fas fa-info-circle"></i> How to pay via JazzCash:</h5>
                            <ol>
                                <li>Open your JazzCash app or dial <code>*786#</code>.</li>
                                <li>Select <strong>Send Money</strong> &rarr; <strong>JazzCash Mobile Account</strong>.</li>
                                <li>Enter Mobile Number: <code>03007654321</code> and order total.</li>
                                <li>Keep the Transaction ID / receipt screenshot to share via WhatsApp.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- BANK TRANSFER DETAILS -->
                <div class="payment-details-box" id="details-bank">
                    <div class="payment-info-card bank-info">
                        <div class="info-header">
                            <div class="payment-badge-icon bank-badge"><i class="fas fa-university"></i></div>
                            <div>
                                <h4>Bank Transfer Account Details</h4>
                                <span class="status-verified"><i class="fas fa-shield-alt"></i> Official Company Bank Account</span>
                            </div>
                        </div>
                        <div class="acc-details-grid">
                            <div class="acc-item">
                                <span class="acc-label">Bank Name</span>
                                <strong class="acc-value">Meezan Bank Limited</strong>
                            </div>
                            <div class="acc-item">
                                <span class="acc-label">Account Title</span>
                                <strong class="acc-value">Jenny's Cosmetics & Jewelry</strong>
                            </div>
                            <div class="acc-item">
                                <span class="acc-label">Account Number</span>
                                <div class="acc-copy-wrap">
                                    <strong class="acc-value">01020304050607</strong>
                                    <button type="button" class="btn-copy" onclick="copyToClipboard('01020304050607', 'Account Number Copied!')"><i class="fas fa-copy"></i> Copy</button>
                                </div>
                            </div>
                            <div class="acc-item">
                                <span class="acc-label">IBAN</span>
                                <div class="acc-copy-wrap">
                                    <strong class="acc-value">PK36MEZN0001020304050607</strong>
                                    <button type="button" class="btn-copy" onclick="copyToClipboard('PK36MEZN0001020304050607', 'IBAN Copied!')"><i class="fas fa-copy"></i> Copy</button>
                                </div>
                            </div>
                            <div class="acc-item">
                                <span class="acc-label">Branch Code & City</span>
                                <strong class="acc-value">0102 - Main Branch, Karachi</strong>
                            </div>
                        </div>
                        <div class="transfer-instructions">
                            <h5><i class="fas fa-info-circle"></i> Instructions for Bank Transfer:</h5>
                            <ol>
                                <li>Transfer total order amount via Internet Banking, Mobile App, or ATM.</li>
                                <li>Use the IBAN or Account Number provided above.</li>
                                <li>Please save the payment receipt or transaction screenshot for order verification.</li>
                            </ol>
                        </div>
                    </div>
                </div>
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
            const fakeEvent = { preventDefault: function() {} };
            placeOrder(fakeEvent);
        } else {
            window.location.href = 'order-confirmation.php';
        }
    }

    // --- 8. SWITCH PAYMENT DETAILS BOX ---
    function switchPaymentDetails(method) {
        // Hide all detail boxes
        document.querySelectorAll('.payment-details-box').forEach(box => {
            box.classList.remove('active');
        });
        // Remove .selected from all option labels
        document.querySelectorAll('.payment-option').forEach(opt => {
            opt.classList.remove('selected');
        });

        // Show the selected detail box
        const targetBox = document.getElementById('details-' + method);
        if (targetBox) targetBox.classList.add('active');

        // Highlight the selected payment option label
        const targetLabel = document.querySelector('.payment-option[data-method="' + method + '"]');
        if (targetLabel) targetLabel.classList.add('selected');
    }

    // --- 9. COPY TO CLIPBOARD ---
    function copyToClipboard(text, successMsg) {
        navigator.clipboard.writeText(text).then(() => {
            // Show a temporary toast
            const toast = document.createElement('div');
            toast.innerHTML = '<i class="fas fa-check-circle" style="color:#27ae60;margin-right:8px;"></i>' + (successMsg || 'Copied!');
            toast.style.cssText = `
                position: fixed; bottom: 30px; left: 50%; transform: translateX(-50%) translateY(20px);
                background: #fff; padding: 12px 24px; border-radius: 30px;
                box-shadow: 0 8px 30px rgba(0,0,0,0.15); z-index: 99999;
                font-weight: 600; font-size: 0.9rem; opacity: 0;
                transition: all 0.3s ease; border: 1px solid #e2e8f0;
                display: flex; align-items: center;
            `;
            document.body.appendChild(toast);
            // Animate in
            requestAnimationFrame(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateX(-50%) translateY(0)';
            });
            // Animate out and remove
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(-50%) translateY(10px)';
                setTimeout(() => toast.remove(), 350);
            }, 2200);
        }).catch(() => {
            // Fallback for older browsers
            const el = document.createElement('textarea');
            el.value = text;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            showNotification(successMsg || 'Copied!');
        });
    }

    // --- 10. INITIALIZE ON PAGE LOAD ---
    window.onload = function() {
        if (document.getElementById('orderItems')) {
            loadOrderSummary();
        }
        updateCartUI();
        // Ensure COD details are visible by default
        switchPaymentDetails('cod');
    };
</script>
</body>
</html>