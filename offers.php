<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<?php require 'includes/header.php'; ?>

<!-- CSS LINK -->
<link rel="stylesheet" href="css/style.css">

<?php require 'includes/navbar.php'; ?>

<section class="offers-hero">
    <div class="offers-hero-content" data-aos="fade-left">
        <h1>Special <span>Offers</span></h1>
        <p>Grab the best deals on your favorite cosmetics and jewelry. Limited time only!</p>
        <div class="offers-hero-buttons">
             <button class="btn-primary" onclick="location.href='#categoryGrid'">Shop Now</button>
             <button class="btn-outline" onclick="location.href='products.php'">Explore Collection</button>
        </div>
    </div>
</section>

<section class="category-page-wrapper">
    
    <!-- LEFT SIDEBAR (ALL CATEGORIES) -->
    <div class="filter-sidebar">
        <h3>Filter Offers</h3>
        
        <div class="filter-group">
            <h4>Category</h4>
            <ul class="filter-list">
                <li><label><input type="radio" name="categoryFilter" value="all" checked onchange="applyFilters()"> All Products</label></li>
                <li><label><input type="radio" name="categoryFilter" value="Foundation" onchange="applyFilters()"> Foundation</label></li>
                <li><label><input type="radio" name="categoryFilter" value="blush" onchange="applyFilters()"> Blush</label></li>
                <li><label><input type="radio" name="categoryFilter" value="Mascara" onchange="applyFilters()"> Mascara</label></li>
                <li><label><input type="radio" name="categoryFilter" value="Lip Stick" onchange="applyFilters()"> Lip Stick</label></li>
                <li><label><input type="radio" name="categoryFilter" value="necklace" onchange="applyFilters()"> Necklace</label></li>
                <li><label><input type="radio" name="categoryFilter" value="earing" onchange="applyFilters()"> Earrings</label></li>
                <li><label><input type="radio" name="categoryFilter" value="ring" onchange="applyFilters()"> Rings</label></li>
                <li><label><input type="radio" name="categoryFilter" value="bracelet" onchange="applyFilters()"> Bracelets</label></li>
            </ul>
        </div>

        <div class="filter-group">
            <h4>Price Range (Rs.)</h4>
            <div class="price-range-wrapper">
                <input type="number" id="minPriceInput" placeholder="Min Price (e.g. 300)" min="0" oninput="applyFilters()">
                <input type="number" id="maxPriceInput" placeholder="Max Price (e.g. 1000)" min="0" oninput="applyFilters()">
                <button class="btn-apply-price" onclick="applyFilters()">Apply Price</button>
            </div>
        </div>

        <button class="btn-clear-filters" onclick="clearAllFilters()">Clear All Filters</button>
    </div>

    <!-- RIGHT CONTENT -->
    <div class="category-content">
        <div class="category-header-toolbar" style="display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; margin-bottom:20px; gap:15px; border-bottom:1px solid #eee; padding-bottom:15px;">
            <div>
                <div style="display:flex; align-items:center; gap:10px;">
                    <h2 class="section-title" style="text-align: left; margin:0; font-size:1.8rem; font-family:var(--font-heading);">Special <span style="color:var(--primary-gold)">Offers</span></h2>
                    <span style="background:rgba(231,76,60,0.12); color:#e74c3c; font-size:0.75rem; font-weight:700; padding:4px 12px; border-radius:20px; text-transform:uppercase; letter-spacing:1px;"><i class="fas fa-bolt"></i> Up to 30% OFF</span>
                </div>
                <p style="color:var(--text-grey); font-size:0.88rem; margin-top:4px;" id="productsCount">Loading offers...</p>
            </div>
            
            <div style="display:flex; align-items:center; gap:12px;">
                <label for="sortSelect" style="font-size:0.85rem; font-weight:600; color:var(--dark-black); display:flex; align-items:center; gap:6px;">
                    <i class="fas fa-sort-amount-down" style="color:var(--primary-gold);"></i> Sort:
                </label>
                <select id="sortSelect" onchange="applyFilters()" style="padding:8px 14px; border:1px solid #ddd; border-radius:20px; font-size:0.85rem; background:#fff; font-family:var(--font-body); cursor:pointer; outline:none;">
                    <option value="default">Default</option>
                    <option value="price-low">Price: Low to High</option>
                    <option value="price-high">Price: High to Low</option>
                    <option value="rating">Highest Rated</option>
                    <option value="name">Name (A-Z)</option>
                </select>
            </div>
        </div>
        
        <div class="category-search-wrapper">
            <input type="text" id="categorySearchInput" placeholder="Search offers (e.g., lipstick, foundation, necklace)..." oninput="applyFilters()" onkeyup="applyFilters()">
            <button type="button" class="clear-search-btn" id="clearSearchBtn" onclick="clearAllFilters()" title="Clear search"><i class="fas fa-times-circle"></i></button>
            <button type="button" onclick="applyFilters()"><i class="fas fa-search"></i></button>
        </div>

        <div id="activeFilterChips" style="display:flex; flex-wrap:wrap; gap:8px; margin-bottom:20px;"></div>

        <!-- PRODUCTS GRID (SIRF OFFER PRODUCTS) -->
        <div class="category-grid" id="categoryGrid" data-aos="fade-up">
            <?php 
            require_once 'includes/products_helper.php';
            $dbProducts = getAllProducts(null, 'offers');
            if (!empty($dbProducts)):
                foreach ($dbProducts as $p):
                    echo renderSingleProductCard($p);
                endforeach;
            else:
            ?>
            <!-- OFFER 1: Foundation (20% OFF) -->
            <div class="category-card visible offer-card" data-name="Foundation" data-id="foundation_offer_1">
                <div class="card-img-wrapper">
                    <div class="offer-badge">-20%</div>
                    <img src="./img/foundation.jpg" alt="Foundation" onclick="openProductPopup('Foundation', 480, './img/foundation.jpg', 'Offer! A lightweight, full-coverage foundation for a flawless glow.')">
                </div>
                <h4>Foundation</h4>
                <div class="card-price">
                    <span class="old-price">Rs. 600</span>
                    <span class="new-price">Rs. 480</span>
                </div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Foundation', 480, './img/foundation.jpg', 'foundation_offer_1')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Foundation', 480, './img/foundation.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- OFFER 2: Lipstick (15% OFF) -->
            <div class="category-card visible offer-card" data-name="Lip Stick" data-id="lipstick_offer_1">
                <div class="card-img-wrapper">
                    <div class="offer-badge">-15%</div>
                    <img src="./img/Lip stick.jpg" alt="Lipstick" onclick="openProductPopup('Lipstick', 297, './img/Lip stick.jpg', 'Offer! Classic matte lipstick in a bold red shade.')">
                </div>
                <h4>Lipstick</h4>
                <div class="card-price">
                    <span class="old-price">Rs. 350</span>
                    <span class="new-price">Rs. 297</span>
                </div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Lipstick', 297, './img/Lip stick.jpg', 'lipstick_offer_1')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Lipstick', 297, './img/Lip stick.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- OFFER 3: Necklace (25% OFF) -->
            <div class="category-card visible offer-card" data-name="necklace" data-id="necklace_offer_1">
                <div class="card-img-wrapper">
                    <div class="offer-badge">-25%</div>
                    <img src="./img/necklace.jpg" alt="Necklace" onclick="openProductPopup('Necklace', 525, './img/necklace.jpg', 'Offer! Elegant rose gold necklace for any occasion.')">
                </div>
                <h4>Necklace</h4>
                <div class="card-price">
                    <span class="old-price">Rs. 700</span>
                    <span class="new-price">Rs. 525</span>
                </div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Necklace', 525, './img/necklace.jpg', 'necklace_offer_1')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Necklace', 525, './img/necklace.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- OFFER 4: Blush (10% OFF) -->
            <div class="category-card visible offer-card" data-name="blush" data-id="blush_offer_1">
                <div class="card-img-wrapper">
                    <div class="offer-badge">-10%</div>
                    <img src="./img/Blush.jpg" alt="Blush" onclick="openProductPopup('Blush', 360, './img/Blush.jpg', 'Offer! Soft pink powder blush for a natural rosy glow.')">
                </div>
                <h4>Blush</h4>
                <div class="card-price">
                    <span class="old-price">Rs. 400</span>
                    <span class="new-price">Rs. 360</span>
                </div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Blush', 360, './img/Blush.jpg', 'blush_offer_1')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Blush', 360, './img/Blush.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- OFFER 5: Earrings (30% OFF) -->
            <div class="category-card visible offer-card" data-name="earing" data-id="earring_offer_1">
                <div class="card-img-wrapper">
                    <div class="offer-badge">-30%</div>
                    <img src="./img/earing.jpg" alt="Earrings" onclick="openProductPopup('Earrings', 350, './img/earing.jpg', 'Offer! Stylish crystal earrings for any occasion.')">
                </div>
                <h4>Earrings</h4>
                <div class="card-price">
                    <span class="old-price">Rs. 500</span>
                    <span class="new-price">Rs. 350</span>
                </div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Earrings', 350, './img/earing.jpg', 'earring_offer_1')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Earrings', 350, './img/earing.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- OFFER 6: Mascara (15% OFF) -->
            <div class="category-card visible offer-card" data-name="Mascara" data-id="mascara_offer_1">
                <div class="card-img-wrapper">
                    <div class="offer-badge">-15%</div>
                    <img src="./img/Eyelashes Mascara.jpg" alt="Mascara" onclick="openProductPopup('Mascara', 467, './img/Eyelashes Mascara.jpg', 'Offer! Volumizing mascara for thick and dramatic lashes.')">
                </div>
                <h4>Mascara</h4>
                <div class="card-price">
                    <span class="old-price">Rs. 550</span>
                    <span class="new-price">Rs. 467</span>
                </div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Mascara', 467, './img/Eyelashes Mascara.jpg', 'mascara_offer_1')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Mascara', 467, './img/Eyelashes Mascara.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- OFFER 7: Ring (20% OFF) -->
            <div class="category-card visible offer-card" data-name="ring" data-id="ring_offer_1">
                <div class="card-img-wrapper">
                    <div class="offer-badge">-20%</div>
                    <img src="./img/ring.jpg" alt="Ring" onclick="openProductPopup('Ring', 240, './img/ring.jpg', 'Offer! Elegant silver ring for any occasion.')">
                </div>
                <h4>Ring</h4>
                <div class="card-price">
                    <span class="old-price">Rs. 300</span>
                    <span class="new-price">Rs. 240</span>
                </div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Ring', 240, './img/ring.jpg', 'ring_offer_1')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Ring', 240, './img/ring.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- OFFER 8: Bracelet (10% OFF) -->
            <div class="category-card visible offer-card" data-name="bracelet" data-id="bracelet_offer_1">
                <div class="card-img-wrapper">
                    <div class="offer-badge">-10%</div>
                    <img src="./img/bracelet.jpg" alt="Bracelet" onclick="openProductPopup('Bracelet', 405, './img/bracelet.jpg', 'Offer! Elegant gold bracelet for any occasion.')">
                </div>
                <h4>Bracelet</h4>
                <div class="card-price">
                    <span class="old-price">Rs. 450</span>
                    <span class="new-price">Rs. 405</span>
                </div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Bracelet', 405, './img/bracelet.jpg', 'bracelet_offer_1')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Bracelet', 405, './img/bracelet.jpg')">Buy Now</button>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<?php require 'includes/popup.php'; ?>

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
        <button class="checkout-btn" onclick="alert('Proceeding to Checkout Page!')">Proceed to Checkout</button>
    </div>
</div>

<!-- Footer -->
<?php require 'includes/footer.php'; ?>

<!-- JS LINK -->
<script src="includes/js/script.js"></script>
</body>
</html>