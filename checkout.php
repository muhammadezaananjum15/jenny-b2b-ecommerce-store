<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Checkout | Jenny's Cosmetics & Jewelry</title>
</head>
<body>

<style>
    /* === GLOBAL BOX-SIZING FIX FOR OVERFLOW === */
    .checkout-wrapper *,
    .checkout-wrapper *::before,
    .checkout-wrapper *::after {
        box-sizing: border-box !important;
    }

    .checkout-wrapper {
        padding: 50px 5% 80px;
        background: linear-gradient(135deg, #FAF8F5 0%, #F3EFEA 100%);
        min-height: 85vh;
        overflow-x: hidden;
    }

    .checkout-page-title {
        text-align: center;
        font-family: var(--font-heading, 'Playfair Display', serif);
        font-size: clamp(1.8rem, 4vw, 2.5rem);
        color: var(--dark-black, #111);
        margin-bottom: 6px;
        font-weight: 700;
    }
    .checkout-page-subtitle {
        text-align: center;
        color: #777;
        font-size: 0.9rem;
        margin-bottom: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    /* === LUXURY STEPPER === */
    .premium-stepper {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0;
        margin-bottom: 40px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }
    .stepper-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        position: relative;
        z-index: 2;
    }
    .stepper-circle {
        width: 44px; height: 44px;
        border-radius: 50%;
        background: #EAE6DF;
        color: #999;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.95rem;
        transition: all 0.35s ease;
        border: 2px solid transparent;
    }
    .stepper-step.active .stepper-circle {
        background: linear-gradient(135deg, #D4AF37, #AA7C11);
        color: #fff;
        box-shadow: 0 6px 20px rgba(212,175,55,0.35);
        transform: scale(1.08);
    }
    .stepper-step.completed .stepper-circle {
        background: #111;
        color: var(--primary-gold, #D4AF37);
        border-color: var(--primary-gold, #D4AF37);
    }
    .stepper-label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #aaa;
        transition: color 0.3s;
    }
    .stepper-step.active .stepper-label { color: var(--dark-black, #111); }
    .stepper-step.completed .stepper-label { color: var(--primary-gold, #D4AF37); }

    .stepper-connector {
        flex: 1;
        height: 2px;
        background: #EA5E4E3;
        background: #E2DBD0;
        margin-bottom: 22px;
        transition: background 0.4s;
    }
    .stepper-connector.done { background: linear-gradient(90deg, #111, #D4AF37); }

    /* === MAIN LAYOUT === */
    .checkout-main {
        display: grid;
        grid-template-columns: 1fr 370px;
        gap: 32px;
        max-width: 1120px;
        margin: 0 auto;
        align-items: start;
    }

    /* === FORM CARD === */
    .checkout-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 36px 40px;
        box-shadow: 0 10px 35px rgba(0,0,0,0.05);
        border: 1px solid #EFEAE3;
        width: 100%;
        overflow: hidden;
    }
    .checkout-step { display: none; animation: checkoutFadeIn 0.35s ease; }
    .checkout-step.active { display: block; }
    @keyframes checkoutFadeIn {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .step-section-title {
        font-family: var(--font-heading, 'Playfair Display', serif);
        font-size: 1.3rem;
        color: var(--dark-black, #111);
        margin-bottom: 22px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        padding-bottom: 10px;
        border-bottom: 1.5px solid #F4EFEA;
    }
    .step-section-title i { color: var(--primary-gold, #D4AF37); font-size: 1.1rem; }

    /* === FORM INPUTS FIX === */
    .form-row { display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 0; }
    .form-row .form-group { flex: 1; min-width: 220px; }
    .form-group { margin-bottom: 20px; width: 100%; }
    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 6px;
        color: #333;
        font-size: 0.84rem;
        letter-spacing: 0.2px;
    }
    .form-group label .req { color: var(--primary-gold, #D4AF37); margin-left: 2px; }

    .input-field-wrap { position: relative; width: 100%; }
    .input-field-wrap .field-icon {
        position: absolute;
        left: 15px; top: 50%; transform: translateY(-50%);
        color: #AA9C84; font-size: 0.9rem; pointer-events: none;
    }
    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100% !important;
        max-width: 100% !important;
        padding: 12px 14px 12px 42px;
        border: 1.5px solid #E2DBD0;
        border-radius: 12px;
        font-family: var(--font-body, 'Montserrat', sans-serif);
        font-size: 0.88rem;
        outline: none;
        transition: all 0.25s ease;
        background: #FAF8F5;
        color: #222;
    }
    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        border-color: #D4AF37;
        background: #FFFFFF;
        box-shadow: 0 0 0 3px rgba(212,175,55,0.12);
    }
    .form-group textarea { padding-left: 14px; resize: vertical; min-height: 85px; }
    .form-group input.error, .form-group textarea.error { border-color: #E74C3C; background: #FFF6F6; }

    .error-message {
        display: none;
        color: #E74C3C;
        font-size: 0.76rem;
        margin-top: 4px;
        font-weight: 600;
    }

    /* === LUXURY DELIVERY SELECTOR === */
    .delivery-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 14px;
        margin-bottom: 22px;
    }
    .delivery-option {
        border: 1.5px solid #E2DBD0;
        border-radius: 14px;
        padding: 16px 18px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #FAF8F5;
        position: relative;
    }
    .delivery-option:hover { border-color: #D4AF37; background: #FFFDF8; }
    .delivery-option.selected {
        border-color: #D4AF37;
        background: #FFFDF0;
        box-shadow: 0 4px 18px rgba(212,175,55,0.15);
    }
    .delivery-option input[type="radio"] { position: absolute; opacity: 0; pointer-events: none; }
    .delivery-option-inner { display: flex; align-items: center; gap: 14px; }
    .delivery-icon {
        width: 40px; height: 40px; border-radius: 10px;
        background: linear-gradient(135deg, #111111, #333333);
        color: var(--primary-gold, #D4AF37);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; flex-shrink: 0;
    }
    .delivery-info h5 { margin: 0 0 2px; font-size: 0.9rem; color: #111; font-weight: 700; }
    .delivery-info p { margin: 0; font-size: 0.76rem; color: #777; }
    .delivery-price { font-weight: 800; color: #D4AF37; font-size: 0.88rem; margin-top: 3px; }

    /* === ELEGANT LUXURY PAYMENT CARDS (NO OVERFLOW) === */
    .payment-options {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
        margin-bottom: 24px;
        width: 100%;
    }
    .payment-option {
        border: 1.5px solid #E2DBD0;
        border-radius: 14px;
        padding: 14px 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #FAF8F5;
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        box-sizing: border-box;
        overflow: hidden;
    }
    .payment-option:hover {
        border-color: #D4AF37;
        background: #FFFDF8;
        transform: translateY(-2px);
    }
    .payment-option.selected {
        border-color: #D4AF37;
        background: linear-gradient(135deg, #FFFDF7 0%, #FFF8E7 100%);
        box-shadow: 0 6px 20px rgba(212,175,55,0.18);
    }
    .payment-option input[type="radio"] { accent-color: #D4AF37; width: 16px; height: 16px; flex-shrink: 0; }
    
    .payment-method-icon {
        width: 38px; height: 38px;
        border-radius: 10px;
        background: linear-gradient(135deg, #D4AF37 0%, #AA7C11 100%);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.05rem; flex-shrink: 0; color: #FFFFFF;
        box-shadow: 0 3px 10px rgba(212,175,55,0.25);
    }
    .pm-title {
        font-weight: 700;
        color: #111;
        font-size: 0.84rem;
        display: block;
        line-height: 1.2;
    }
    .pm-subtitle {
        font-size: 0.7rem;
        color: #777;
        margin-top: 2px;
        line-height: 1.2;
    }

    /* PAYMENT DETAILS */
    .payment-details-wrapper { margin-top: 16px; width: 100%; }
    .payment-details-box { display: none; animation: checkoutFadeIn 0.3s ease; width: 100%; }
    .payment-details-box.active { display: block; }
    .payment-info-card {
        background: #FFFFFF;
        border: 1.5px solid #EFEAE3;
        border-radius: 16px;
        padding: 22px;
        box-shadow: 0 6px 25px rgba(0,0,0,0.04);
        position: relative; overflow: hidden;
        width: 100%;
    }
    .payment-info-card::before {
        content: ''; position: absolute; top:0; left:0; width:4px; height:100%;
        background: linear-gradient(180deg, #D4AF37, #AA7C11);
    }
    .info-header { display: flex; align-items: center; gap: 14px; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid #F4EFEA; }
    .payment-badge-icon {
        width: 44px; height: 44px; border-radius: 12px;
        background: linear-gradient(135deg, #111, #333);
        color: #D4AF37;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; flex-shrink: 0;
    }
    .info-header h4 { margin: 0; font-size: 1rem; color: #111; font-family: var(--font-heading, serif); }
    .status-verified { font-size: 0.74rem; color: #27AE60; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; margin-top: 2px; }

    .acc-details-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px 16px; background: #FAF8F5; padding: 14px; border-radius: 12px; margin-bottom: 16px; border: 1px solid #EFEAE3;
    }
    .acc-item { display: flex; flex-direction: column; gap: 2px; min-width: 0; }
    .acc-label { font-size: 0.7rem; color: #888; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; }
    .acc-value { font-size: 0.88rem; color: #111; font-weight: 700; word-break: break-all; }
    .acc-copy-wrap { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .btn-copy {
        background: #D4AF37; color: #111; border: none;
        padding: 4px 10px; border-radius: 6px; font-size: 0.7rem;
        font-weight: 700; cursor: pointer; transition: 0.2s;
        display: inline-flex; align-items: center; gap: 4px; flex-shrink: 0;
    }
    .btn-copy:hover { background: #AA7C11; color: #fff; }
    .transfer-instructions { background: #FFFDF7; border: 1px solid #F5EAD4; border-radius: 12px; padding: 14px 16px; }
    .transfer-instructions h5 { margin: 0 0 8px; font-size: 0.83rem; color: #7A5C00; display: flex; align-items: center; gap: 6px; }
    .transfer-instructions ol, .transfer-instructions ul { margin: 0; padding-left: 18px; font-size: 0.8rem; color: #555; line-height: 1.6; }

    /* === STEP ACTIONS === */
    .step-actions { display: flex; gap: 14px; margin-top: 32px; justify-content: flex-end; flex-wrap: wrap; }
    .btn-continue {
        padding: 14px 38px; font-size: 0.92rem; border-radius: 30px;
        background: linear-gradient(135deg, #D4AF37, #AA7C11); color: #fff;
        border: none; cursor: pointer; font-weight: 700; transition: all 0.3s ease;
        box-shadow: 0 6px 20px rgba(212,175,55,0.3);
    }
    .btn-continue:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(212,175,55,0.4); }
    .btn-outline {
        background: transparent; color: #111;
        padding: 14px 28px; border-radius: 30px;
        border: 1.5px solid #E2DBD0; cursor: pointer;
        transition: 0.3s; font-weight: 600;
        display: inline-flex; align-items: center; gap: 8px; font-size: 0.88rem;
    }
    .btn-outline:hover { background: #FAF8F5; border-color: #111; }

    /* === REVIEW STEP (NO TEXT OVERFLOW) === */
    .review-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        width: 100%;
    }
    .review-grid > div { min-width: 0; }
    .review-section-title { font-weight: 700; font-size: 0.84rem; text-transform: uppercase; letter-spacing: 1px; color: #D4AF37; margin-bottom: 14px; display: flex; align-items: center; gap: 8px; }
    .review-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid #F4EFEA;
        font-size: 0.85rem;
        width: 100%;
    }
    .review-label { color: #888; font-weight: 500; flex-shrink: 0; }
    .review-value {
        color: #111;
        font-weight: 700;
        text-align: right;
        max-width: 60%;
        word-break: break-word;
        overflow-wrap: anywhere;
        white-space: normal;
    }
    .review-order-items { max-height: 220px; overflow-y: auto; margin-bottom: 12px; }
    .review-order-item { display: flex; align-items: center; gap: 12px; padding: 8px 0; border-bottom: 1px solid #F4EFEA; }
    .review-order-item img { width: 44px; height: 44px; object-fit: contain; border-radius: 8px; border: 1px solid #EEE; flex-shrink: 0; }
    .review-order-item-info { flex: 1; min-width: 0; }
    .review-order-item-name { font-size: 0.83rem; font-weight: 700; color: #111; word-break: break-word; }
    .review-order-item-qty { font-size: 0.75rem; color: #888; margin-top: 2px; }
    .review-order-item-price { font-weight: 800; color: #D4AF37; font-size: 0.88rem; flex-shrink: 0; }
    .review-totals { border-top: 2px solid #F4EFEA; padding-top: 14px; }
    .summary-row { display: flex; justify-content: space-between; padding: 4px 0; font-size: 0.86rem; color: #777; }
    .summary-row.total-row { font-size: 1.05rem; font-weight: 800; color: #111; border-top: 2px dashed #E2DBD0; padding-top: 10px; margin-top: 6px; }

    /* === STICKY CART SIDEBAR === */
    .checkout-sidebar { position: sticky; top: 100px; width: 100%; }
    .sidebar-card { background: #FFFFFF; border-radius: 20px; padding: 24px; box-shadow: 0 8px 30px rgba(0,0,0,0.05); border: 1px solid #EFEAE3; margin-bottom: 16px; }
    .sidebar-title { font-family: var(--font-heading, serif); font-size: 1.05rem; color: #111; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; font-weight: 700; }
    .sidebar-title i { color: #D4AF37; }
    .sidebar-items { max-height: 260px; overflow-y: auto; }
    .sidebar-item { display: flex; align-items: center; gap: 12px; padding: 9px 0; border-bottom: 1px solid #F4EFEA; }
    .sidebar-item img { width: 44px; height: 44px; object-fit: contain; border-radius: 8px; border: 1px solid #EEE; flex-shrink: 0; }
    .sidebar-item-info { flex: 1; min-width: 0; }
    .sidebar-item-name { font-size: 0.82rem; font-weight: 700; color: #111; word-break: break-word; }
    .sidebar-item-qty { font-size: 0.73rem; color: #888; }
    .sidebar-item-price { font-weight: 800; font-size: 0.88rem; color: #D4AF37; flex-shrink: 0; }
    .sidebar-totals { border-top: 2px solid #F4EFEA; margin-top: 12px; padding-top: 14px; }
    .sidebar-total-row { display: flex; justify-content: space-between; font-size: 0.85rem; color: #777; padding: 4px 0; }
    .sidebar-total-row.grand { font-size: 1.05rem; font-weight: 800; color: #111; border-top: 2px dashed #E2DBD0; padding-top: 10px; margin-top: 6px; }
    .sidebar-total-row.grand span:last-child { color: #D4AF37; }

    .secure-badge { display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 0.78rem; color: #27AE60; font-weight: 600; padding: 12px; background: #F0FDF4; border-radius: 12px; border: 1px solid #BBF7D0; }

    /* === RESPONSIVE FIXES === */
    @media (max-width: 900px) {
        .checkout-main { grid-template-columns: 1fr; }
        .checkout-sidebar { position: static; }
        .review-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 550px) {
        .checkout-card { padding: 20px 14px; }
        .form-row { flex-direction: column; gap: 0; }
        .payment-options { grid-template-columns: 1fr; }
        .delivery-options { grid-template-columns: 1fr; }
        .step-actions { flex-direction: column; }
        .btn-continue, .btn-outline { width: 100%; justify-content: center; text-align: center; }
    }
</style>

<?php require 'includes/header.php'; ?>
<link rel="stylesheet" href="css/style.css">
<?php require 'includes/navbar.php'; ?>

<!-- ============================================ -->
<!-- PREMIUM CHECKOUT CONTAINER -->
<!-- ============================================ -->
<section class="checkout-wrapper">

    <h1 class="checkout-page-title">Secure Checkout</h1>
    <p class="checkout-page-subtitle"><i class="fas fa-shield-halved" style="color:#D4AF37;"></i> Protected by 256-bit SSL encryption</p>

    <!-- STEP INDICATOR -->
    <div class="premium-stepper">
        <div class="stepper-step active" id="stepperStep1">
            <div class="stepper-circle" id="stepCircle1"><i class="fas fa-map-marker-alt"></i></div>
            <span class="stepper-label">Delivery</span>
        </div>
        <div class="stepper-connector" id="stepConn1"></div>
        <div class="stepper-step" id="stepperStep2">
            <div class="stepper-circle" id="stepCircle2"><i class="fas fa-credit-card"></i></div>
            <span class="stepper-label">Payment</span>
        </div>
        <div class="stepper-connector" id="stepConn2"></div>
        <div class="stepper-step" id="stepperStep3">
            <div class="stepper-circle" id="stepCircle3"><i class="fas fa-clipboard-check"></i></div>
            <span class="stepper-label">Review</span>
        </div>
    </div>

    <!-- MAIN GRID -->
    <div class="checkout-main">

        <!-- FORM CARD -->
        <div class="checkout-card">

            <!-- STEP 1: DELIVERY & AGE VERIFICATION -->
            <div class="checkout-step active" id="step1">
                <div class="step-section-title"><i class="fas fa-user-check"></i> Delivery & Customer Details</div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name <span class="req">*</span></label>
                        <div class="input-field-wrap">
                            <i class="fas fa-user field-icon"></i>
                            <input type="text" id="fullName" placeholder="e.g. Ayesha Khan" required>
                        </div>
                        <div class="error-message" id="fullNameError">Full name is required</div>
                    </div>
                    <div class="form-group">
                        <label>Phone Number <span class="req">*</span></label>
                        <div class="input-field-wrap">
                            <i class="fas fa-phone field-icon"></i>
                            <input type="tel" id="phone" placeholder="+92 300 1234567" required>
                        </div>
                        <div class="error-message" id="phoneError">Valid phone number required</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Email Address <span class="req">*</span></label>
                        <div class="input-field-wrap">
                            <i class="fas fa-envelope field-icon"></i>
                            <input type="email" id="email" placeholder="ayesha@example.com" required>
                        </div>
                        <div class="error-message" id="emailError">Valid email required</div>
                    </div>
                    <!-- DATE OF BIRTH FIELD (AGE 16+ CHECK) -->
                    <div class="form-group">
                        <label>Date of Birth <span class="req">*</span> <span style="font-size:0.72rem; color:#D4AF37; font-weight:700;">(Must be 16+)</span></label>
                        <div class="input-field-wrap">
                            <i class="fas fa-calendar-alt field-icon"></i>
                            <input type="date" id="checkoutDob" required oninput="validateCheckoutAge(this)">
                        </div>
                        <div class="error-message" id="dobError">You must be at least 16 years old to order.</div>
                        <div id="checkoutAgeBadge" style="font-size:0.75rem; color:#27AE60; margin-top:4px; font-weight:700; display:none;"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Full Delivery Address <span class="req">*</span></label>
                    <textarea id="address" rows="3" placeholder="House No. 12, Block 5, Gulshan-e-Iqbal, Karachi" required></textarea>
                    <div class="error-message" id="addressError">Delivery address is required</div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>City <span class="req">*</span></label>
                        <div class="input-field-wrap">
                            <i class="fas fa-city field-icon"></i>
                            <input type="text" id="city" placeholder="e.g. Karachi" required>
                        </div>
                        <div class="error-message" id="cityError">City is required</div>
                    </div>
                    <div class="form-group">
                        <label>Postal Code / Zip</label>
                        <div class="input-field-wrap">
                            <i class="fas fa-location-dot field-icon"></i>
                            <input type="text" id="zip" placeholder="e.g. 75300">
                        </div>
                    </div>
                </div>

                <!-- DELIVERY METHOD -->
                <div class="step-section-title" style="margin-top:12px;"><i class="fas fa-truck"></i> Shipping Method</div>
                <div class="delivery-options">
                    <label class="delivery-option selected" id="delStd" onclick="selectDelivery('standard',this)">
                        <input type="radio" name="deliveryMethod" value="standard" checked>
                        <div class="delivery-option-inner">
                            <div class="delivery-icon"><i class="fas fa-box"></i></div>
                            <div class="delivery-info">
                                <h5>Standard Delivery</h5>
                                <p>3 to 5 Business Days</p>
                                <div class="delivery-price">Free</div>
                            </div>
                        </div>
                    </label>
                    <label class="delivery-option" id="delExp" onclick="selectDelivery('express',this)">
                        <input type="radio" name="deliveryMethod" value="express">
                        <div class="delivery-option-inner">
                            <div class="delivery-icon"><i class="fas fa-rocket"></i></div>
                            <div class="delivery-info">
                                <h5>Express Shipping</h5>
                                <p>1 to 2 Business Days</p>
                                <div class="delivery-price">Rs. 150</div>
                            </div>
                        </div>
                    </label>
                </div>

                <div class="form-group">
                    <label>Order Notes (Optional)</label>
                    <textarea id="orderNotes" rows="2" placeholder="Any special delivery instructions..."></textarea>
                </div>

                <div class="step-actions">
                    <button type="button" class="btn-continue" onclick="validateStep1()">
                        Proceed to Payment &nbsp;<i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- STEP 2: ELEGANT PAYMENT SELECTION (NON-ROBOTIC) -->
            <div class="checkout-step" id="step2">
                <div class="step-section-title"><i class="fas fa-credit-card"></i> Select Payment Method</div>

                <div class="payment-options">
                    <label class="payment-option selected" data-method="cod" onclick="switchPaymentDetails('cod');selectPaymentOption(this)">
                        <input type="radio" name="paymentMethod" value="cod" checked>
                        <div class="payment-method-icon"><i class="fas fa-hand-holding-dollar"></i></div>
                        <div>
                            <span class="pm-title">Cash on Delivery</span>
                            <span class="pm-subtitle">Pay upon parcel arrival</span>
                        </div>
                    </label>
                    <label class="payment-option" data-method="easypaisa" onclick="switchPaymentDetails('easypaisa');selectPaymentOption(this)">
                        <input type="radio" name="paymentMethod" value="easypaisa">
                        <div class="payment-method-icon"><i class="fas fa-mobile-screen-button"></i></div>
                        <div>
                            <span class="pm-title">EasyPaisa</span>
                            <span class="pm-subtitle">Instant Mobile Transfer</span>
                        </div>
                    </label>
                    <label class="payment-option" data-method="jazzcash" onclick="switchPaymentDetails('jazzcash');selectPaymentOption(this)">
                        <input type="radio" name="paymentMethod" value="jazzcash">
                        <div class="payment-method-icon"><i class="fas fa-wallet"></i></div>
                        <div>
                            <span class="pm-title">JazzCash</span>
                            <span class="pm-subtitle">Mobile Wallet Transfer</span>
                        </div>
                    </label>
                    <label class="payment-option" data-method="bank" onclick="switchPaymentDetails('bank');selectPaymentOption(this)">
                        <input type="radio" name="paymentMethod" value="bank">
                        <div class="payment-method-icon"><i class="fas fa-building-columns"></i></div>
                        <div>
                            <span class="pm-title">Bank Transfer</span>
                            <span class="pm-subtitle">Direct Bank Transfer</span>
                        </div>
                    </label>
                </div>

                <!-- PAYMENT INSTRUCTION CARDS -->
                <div class="payment-details-wrapper">
                    <!-- COD -->
                    <div class="payment-details-box active" id="details-cod">
                        <div class="payment-info-card">
                            <div class="info-header">
                                <div class="payment-badge-icon"><i class="fas fa-truck-ramp-box"></i></div>
                                <div><h4>Cash on Delivery</h4><span class="status-verified"><i class="fas fa-check"></i> Safe &amp; Convenient</span></div>
                            </div>
                            <div class="transfer-instructions">
                                <h5><i class="fas fa-circle-info"></i> How it Works</h5>
                                <ul>
                                    <li>Pay with exact cash directly to the courier agent upon arrival.</li>
                                    <li>Delivery typically takes 3 to 5 business days across Pakistan.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- EASYPAISA -->
                    <div class="payment-details-box" id="details-easypaisa">
                        <div class="payment-info-card">
                            <div class="info-header">
                                <div class="payment-badge-icon"><i class="fas fa-mobile-screen"></i></div>
                                <div><h4>EasyPaisa Transfer Details</h4><span class="status-verified"><i class="fas fa-shield-check"></i> Official Account</span></div>
                            </div>
                            <div class="acc-details-grid">
                                <div class="acc-item"><span class="acc-label">Account Title</span><strong class="acc-value">Jenny's Cosmetics</strong></div>
                                <div class="acc-item"><span class="acc-label">Mobile Number</span><div class="acc-copy-wrap"><strong class="acc-value">0300-1234567</strong><button type="button" class="btn-copy" onclick="copyToClipboard('03001234567','EasyPaisa Number Copied!')"><i class="fas fa-copy"></i> Copy</button></div></div>
                            </div>
                            <div class="transfer-instructions">
                                <h5><i class="fas fa-circle-info"></i> Payment Steps</h5>
                                <ol><li>Open your EasyPaisa App or dial <code>*786#</code>.</li><li>Send total order amount to <code>03001234567</code>.</li><li>Save transaction receipt to confirm order.</li></ol>
                            </div>
                        </div>
                    </div>
                    <!-- JAZZCASH -->
                    <div class="payment-details-box" id="details-jazzcash">
                        <div class="payment-info-card">
                            <div class="info-header">
                                <div class="payment-badge-icon"><i class="fas fa-wallet"></i></div>
                                <div><h4>JazzCash Transfer Details</h4><span class="status-verified"><i class="fas fa-shield-check"></i> Official Account</span></div>
                            </div>
                            <div class="acc-details-grid">
                                <div class="acc-item"><span class="acc-label">Account Title</span><strong class="acc-value">Jenny's Cosmetics</strong></div>
                                <div class="acc-item"><span class="acc-label">Mobile Number</span><div class="acc-copy-wrap"><strong class="acc-value">0300-7654321</strong><button type="button" class="btn-copy" onclick="copyToClipboard('03007654321','JazzCash Number Copied!')"><i class="fas fa-copy"></i> Copy</button></div></div>
                            </div>
                            <div class="transfer-instructions">
                                <h5><i class="fas fa-circle-info"></i> Payment Steps</h5>
                                <ol><li>Open your JazzCash App or dial <code>*786#</code>.</li><li>Send total order amount to <code>03007654321</code>.</li><li>Save receipt screenshot for verification.</li></ol>
                            </div>
                        </div>
                    </div>
                    <!-- BANK -->
                    <div class="payment-details-box" id="details-bank">
                        <div class="payment-info-card">
                            <div class="info-header">
                                <div class="payment-badge-icon"><i class="fas fa-building-columns"></i></div>
                                <div><h4>Bank Transfer Details</h4><span class="status-verified"><i class="fas fa-shield-check"></i> Company Account</span></div>
                            </div>
                            <div class="acc-details-grid">
                                <div class="acc-item"><span class="acc-label">Bank Name</span><strong class="acc-value">Meezan Bank Limited</strong></div>
                                <div class="acc-item"><span class="acc-label">Account Title</span><strong class="acc-value">Jenny's Cosmetics</strong></div>
                                <div class="acc-item"><span class="acc-label">Account Number</span><div class="acc-copy-wrap"><strong class="acc-value">01020304050607</strong><button type="button" class="btn-copy" onclick="copyToClipboard('01020304050607','Account Copied!')"><i class="fas fa-copy"></i> Copy</button></div></div>
                                <div class="acc-item"><span class="acc-label">IBAN</span><div class="acc-copy-wrap"><strong class="acc-value">PK36MEZN0001020304050607</strong><button type="button" class="btn-copy" onclick="copyToClipboard('PK36MEZN0001020304050607','IBAN Copied!')"><i class="fas fa-copy"></i> Copy</button></div></div>
                            </div>
                            <div class="transfer-instructions">
                                <h5><i class="fas fa-circle-info"></i> Payment Steps</h5>
                                <ol><li>Transfer order total via ATM or Mobile Banking App using the IBAN above.</li><li>Keep transaction confirmation for order fulfillment.</li></ol>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="step-actions">
                    <button type="button" class="btn-outline" onclick="goToStep(1)"><i class="fas fa-arrow-left"></i> Back</button>
                    <button type="button" class="btn-continue" onclick="goToStep(3)">Review Order &nbsp;<i class="fas fa-arrow-right"></i></button>
                </div>
            </div>

            <!-- STEP 3: REVIEW & CONFIRM -->
            <div class="checkout-step" id="step3">
                <div class="step-section-title"><i class="fas fa-clipboard-check"></i> Final Review</div>

                <div class="review-grid">
                    <div>
                        <div class="review-section-title"><i class="fas fa-user"></i> Customer &amp; Delivery Info</div>
                        <div class="review-item"><span class="review-label">Name</span><span class="review-value" id="reviewName"> | </span></div>
                        <div class="review-item"><span class="review-label">Phone</span><span class="review-value" id="reviewPhone"> | </span></div>
                        <div class="review-item"><span class="review-label">Email</span><span class="review-value" id="reviewEmail"> | </span></div>
                        <div class="review-item"><span class="review-label">Date of Birth</span><span class="review-value" id="reviewDob"> | </span></div>
                        <div class="review-item"><span class="review-label">Address</span><span class="review-value" id="reviewAddress"> | </span></div>
                        <div class="review-item"><span class="review-label">City</span><span class="review-value" id="reviewCityZip"> | </span></div>
                        <div class="review-item"><span class="review-label">Payment</span><span class="review-value" id="reviewPayment"> | </span></div>
                    </div>
                    <div>
                        <div class="review-section-title"><i class="fas fa-shopping-bag"></i> Order Summary</div>
                        <div class="review-order-items" id="reviewOrderItems"></div>
                        <div class="review-totals">
                            <div class="summary-row"><span>Subtotal</span><span id="reviewSubtotal">Rs. 0</span></div>
                            <div class="summary-row"><span>Shipping</span><span id="reviewShipping">Free</span></div>
                            <div class="summary-row total-row"><span>Total</span><span id="reviewTotal">Rs. 0</span></div>
                        </div>
                    </div>
                </div>

                <div class="step-actions">
                    <button type="button" class="btn-outline" onclick="goToStep(2)"><i class="fas fa-arrow-left"></i> Back</button>
                    <button type="button" class="btn-continue" onclick="validateAndPlaceOrder()" id="placeOrderBtn">
                        <i class="fas fa-shield-halved"></i>&nbsp; Place Order Now
                    </button>
                </div>
            </div>

        </div><!-- /.checkout-card -->

        <!-- STICKY SIDEBAR -->
        <div class="checkout-sidebar">
            <div class="sidebar-card">
                <div class="sidebar-title"><i class="fas fa-shopping-bag"></i> Order Cart</div>
                <div class="sidebar-items" id="checkoutSidebarItems">
                    <div style="text-align:center;color:#999;padding:20px;font-size:0.85rem;" id="sidebarEmptyMsg">Your cart is empty</div>
                </div>
                <div class="sidebar-totals">
                    <div class="sidebar-total-row"><span>Subtotal</span><span id="sidebarSubtotal">Rs. 0</span></div>
                    <div class="sidebar-total-row"><span>Shipping</span><span id="sidebarShipping">Free</span></div>
                    <div class="sidebar-total-row grand"><span>Total</span><span id="sidebarTotal">Rs. 0</span></div>
                </div>
            </div>
            <div class="secure-badge">
                <i class="fas fa-lock"></i> 256-Bit SSL Encrypted Checkout
            </div>
        </div>

    </div>
</section>

<?php require 'includes/footer.php'; ?>
<script src="includes/js/script.js"></script>

<script>
let currentStep = 1;
let shippingCost = 0;

// Set max DOB date to 16 years ago today
document.addEventListener('DOMContentLoaded', function() {
    const dobInput = document.getElementById('checkoutDob');
    if (dobInput) {
        const maxDate = new Date();
        maxDate.setFullYear(maxDate.getFullYear() - 16);
        dobInput.max = maxDate.toISOString().split('T')[0];
    }
});

function validateCheckoutAge(input) {
    const errorMsg = document.getElementById('dobError');
    const badge = document.getElementById('checkoutAgeBadge');
    if (!input.value) {
        if (errorMsg) errorMsg.style.display = 'none';
        if (badge) badge.style.display = 'none';
        return true;
    }
    const dob = new Date(input.value);
    const today = new Date();
    let age = today.getFullYear() - dob.getFullYear();
    const m = today.getMonth() - dob.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;

    if (age < 16) {
        input.classList.add('error');
        if (errorMsg) { errorMsg.innerText = 'You must be at least 16 years old to place an order.'; errorMsg.style.display = 'block'; }
        if (badge) badge.style.display = 'none';
        return false;
    } else {
        input.classList.remove('error');
        if (errorMsg) errorMsg.style.display = 'none';
        if (badge) { badge.innerText = `Age Verified (${age} years old) ✓`; badge.style.display = 'block'; }
        return true;
    }
}

function goToStep(step) {
    document.querySelectorAll('.checkout-step').forEach(el => el.classList.remove('active'));
    document.getElementById('step' + step).classList.add('active');
    currentStep = step;

    for (let i = 1; i <= 3; i++) {
        const s = document.getElementById('stepperStep' + i);
        const c = document.getElementById('stepCircle' + i);
        s.classList.remove('active', 'completed');
        if (i < step) { s.classList.add('completed'); c.innerHTML = '<i class="fas fa-check"></i>'; }
        else if (i === step) { s.classList.add('active'); restoreStepIcon(i, c); }
        else { restoreStepIcon(i, c); }
    }
    document.getElementById('stepConn1').classList.toggle('done', step > 1);
    document.getElementById('stepConn2').classList.toggle('done', step > 2);

    if (step === 3) loadReviewSummary();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function restoreStepIcon(i, el) {
    const icons = ['fa-map-marker-alt', 'fa-credit-card', 'fa-clipboard-check'];
    el.innerHTML = '<i class="fas ' + icons[i - 1] + '"></i>';
}

function selectDelivery(type, el) {
    document.querySelectorAll('.delivery-option').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    shippingCost = type === 'express' ? 150 : 0;
    updateSidebarTotals();
}

function selectPaymentOption(el) {
    document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
}

function switchPaymentDetails(method) {
    document.querySelectorAll('.payment-details-box').forEach(b => b.classList.remove('active'));
    const box = document.getElementById('details-' + method);
    if (box) box.classList.add('active');
}

function hideAllErrors() {
    document.querySelectorAll('.error-message').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.form-group input, .form-group textarea').forEach(el => el.classList.remove('error'));
}

function validateStep1() {
    hideAllErrors();
    let ok = true;
    const fullName = document.getElementById('fullName').value.trim();
    const phone    = document.getElementById('phone').value.trim();
    const email    = document.getElementById('email').value.trim();
    const dobInput = document.getElementById('checkoutDob');
    const address  = document.getElementById('address').value.trim();
    const city     = document.getElementById('city').value.trim();

    if (!fullName) { showError('fullName', 'fullNameError', 'Full name is required'); ok = false; }
    if (!phone || !/^[0-9+\-\s]{10,}$/.test(phone)) { showError('phone', 'phoneError', 'Enter a valid phone number'); ok = false; }
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { showError('email', 'emailError', 'Enter a valid email address'); ok = false; }
    if (!dobInput.value.trim() || !validateCheckoutAge(dobInput)) {
        showError('checkoutDob', 'dobError', 'Date of birth is required (must be 16+)'); ok = false;
    }
    if (!address) { showError('address', 'addressError', 'Delivery address is required'); ok = false; }
    if (!city) { showError('city', 'cityError', 'City is required'); ok = false; }
    if (ok) goToStep(2);
}

function showError(fieldId, errorId, msg) {
    const f = document.getElementById(fieldId), e = document.getElementById(errorId);
    if (f) f.classList.add('error');
    if (e) { e.innerText = msg; e.style.display = 'block'; }
}

function loadReviewSummary() {
    document.getElementById('reviewName').innerText = document.getElementById('fullName').value || ' | ';
    document.getElementById('reviewPhone').innerText = document.getElementById('phone').value || ' | ';
    document.getElementById('reviewEmail').innerText = document.getElementById('email').value || ' | ';
    document.getElementById('reviewDob').innerText = document.getElementById('checkoutDob').value || ' | ';
    document.getElementById('reviewAddress').innerText = document.getElementById('address').value || ' | ';
    const city = document.getElementById('city').value;
    const zip  = document.getElementById('zip').value;
    document.getElementById('reviewCityZip').innerText = [city, zip].filter(Boolean).join(' | ') || ' | ';

    const payment = document.querySelector('input[name="paymentMethod"]:checked');
    const pmLabels = { cod: 'Cash on Delivery', easypaisa: 'EasyPaisa', jazzcash: 'JazzCash', bank: 'Bank Transfer' };
    document.getElementById('reviewPayment').innerText = payment ? (pmLabels[payment.value] || payment.value) : ' | ';

    const itemsContainer = document.getElementById('reviewOrderItems');
    if (itemsContainer) {
        itemsContainer.innerHTML = '';
        let subtotal = 0;
        if (typeof cart === 'undefined' || cart.length === 0) {
            itemsContainer.innerHTML = '<div style="text-align:center;color:#aaa;padding:15px;">No items in cart</div>';
        } else {
            cart.forEach(item => {
                const tot = item.price * item.quantity;
                subtotal += tot;
                itemsContainer.innerHTML += `
                    <div class="review-order-item">
                        <img src="${item.image}" alt="${item.name}" onerror="this.src='img/placeholder.jpg'">
                        <div class="review-order-item-info">
                            <div class="review-order-item-name">${item.name}</div>
                            <div class="review-order-item-qty">Qty: ${item.quantity} × Rs. ${item.price}</div>
                        </div>
                        <div class="review-order-item-price">Rs. ${tot}</div>
                    </div>`;
            });
        }
        const total = subtotal + shippingCost;
        document.getElementById('reviewSubtotal').innerText = 'Rs. ' + subtotal;
        document.getElementById('reviewShipping').innerText = shippingCost > 0 ? 'Rs. ' + shippingCost : 'Free';
        document.getElementById('reviewTotal').innerText = 'Rs. ' + total;
    }
}

function validateAndPlaceOrder() {
    if (typeof cart === 'undefined' || cart.length === 0) {
        alert('Your cart is empty! Please add products to your cart.');
        return;
    }
    const btn = document.getElementById('placeOrderBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>&nbsp; Placing Order...';

    const orderData = {
        name: document.getElementById('fullName').value,
        phone: document.getElementById('phone').value,
        email: document.getElementById('email').value,
        dob: document.getElementById('checkoutDob').value,
        address: document.getElementById('address').value,
        city: document.getElementById('city').value,
        delivery: document.querySelector('input[name="deliveryMethod"]:checked')?.value || 'standard',
        payment: document.querySelector('input[name="paymentMethod"]:checked')?.value || 'cod',
        notes: document.getElementById('orderNotes')?.value || '',
        items: cart,
        shipping: shippingCost,
        total: cart.reduce((s, i) => s + i.price * i.quantity, 0) + shippingCost,
        orderNum: 'JN-' + Date.now().toString(36).toUpperCase(),
        date: new Date().toLocaleDateString('en-PK', { day:'numeric', month:'long', year:'numeric' })
    };
    localStorage.setItem('jennyLastOrder', JSON.stringify(orderData));
    cart = [];
    localStorage.setItem('jennyCart', JSON.stringify(cart));
    setTimeout(() => { window.location.href = 'order-confirmation.php'; }, 600);
}

function copyToClipboard(text, msg) {
    navigator.clipboard.writeText(text).then(() => {
        const toast = document.createElement('div');
        toast.innerHTML = '<i class="fas fa-check-circle" style="color:#27AE60;margin-right:8px;"></i>' + (msg || 'Copied!');
        toast.style.cssText = 'position:fixed;bottom:30px;left:50%;transform:translateX(-50%) translateY(20px);background:#fff;padding:12px 24px;border-radius:30px;box-shadow:0 8px 30px rgba(0,0,0,0.15);z-index:99999;font-weight:600;font-size:0.88rem;opacity:0;transition:all 0.3s;border:1px solid #EFEAE3;display:flex;align-items:center;';
        document.body.appendChild(toast);
        requestAnimationFrame(() => { toast.style.opacity='1'; toast.style.transform='translateX(-50%) translateY(0)'; });
        setTimeout(() => { toast.style.opacity='0'; setTimeout(() => toast.remove(), 350); }, 2000);
    }).catch(() => {});
}

function updateCheckoutSidebar() {
    const container = document.getElementById('checkoutSidebarItems');
    if (!container) return;
    if (typeof cart === 'undefined' || cart.length === 0) {
        container.innerHTML = '<div style="text-align:center;color:#999;padding:20px;font-size:0.85rem;">Your cart is empty</div>';
        updateSidebarTotals();
        return;
    }
    let html = '';
    cart.forEach(item => {
        html += `<div class="sidebar-item">
            <img src="${item.image}" alt="${item.name}" onerror="this.src='img/placeholder.jpg'">
            <div class="sidebar-item-info">
                <div class="sidebar-item-name">${item.name}</div>
                <div class="sidebar-item-qty">Qty: ${item.quantity}</div>
            </div>
            <div class="sidebar-item-price">Rs. ${item.price * item.quantity}</div>
        </div>`;
    });
    container.innerHTML = html;
    updateSidebarTotals();
}

function updateSidebarTotals() {
    const subtotal = (typeof cart !== 'undefined' ? cart : []).reduce((s, i) => s + i.price * i.quantity, 0);
    const total = subtotal + shippingCost;
    const sub = document.getElementById('sidebarSubtotal');
    const sh  = document.getElementById('sidebarShipping');
    const tot = document.getElementById('sidebarTotal');
    if (sub) sub.innerText = 'Rs. ' + subtotal;
    if (sh)  sh.innerText  = shippingCost > 0 ? 'Rs. ' + shippingCost : 'Free';
    if (tot) tot.innerText = 'Rs. ' + total;
}

window.addEventListener('DOMContentLoaded', function() {
    if (typeof updateCartUI === 'function') updateCartUI();
    updateCheckoutSidebar();
});
</script>
</body>
</html>