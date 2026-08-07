<?php session_start(); ?>
<?php require 'includes/header.php'; ?>

<!-- CSS LINK -->
<link rel="stylesheet" href="css/style.css">

<?php require 'includes/navbar.php'; ?>

<!-- ============================================ -->
<!-- HERO BANNER (NEW ARRIVALS) -->
<!-- ============================================ -->
<section class="newarrivals-hero">
    <div class="newarrivals-hero-content">
        <h1>New <span>Arrivals</span></h1>
        <p>Discover the latest additions to our collection. Fresh styles, new favorites.</p>
        <div class="newarrivals-hero-buttons">
                <button class="btn-primary" onclick="location.href='#categoryGrid'">Shop Now</button>
                <button class="btn-outline" onclick="location.href='products.php'">Explore Collection</button>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- CATEGORY SECTION WITH SIDEBAR & SEARCH -->
<!-- ============================================ -->
<section class="category-page-wrapper">
    
    <!-- LEFT SIDEBAR (ALL CATEGORIES) -->
    <div class="filter-sidebar">
        <h3>Filter New Arrivals</h3>
        
        <div class="filter-group">
            <h4>Category</h4>
            <ul class="filter-list">
                <li><label><input type="radio" name="categoryFilter" value="all" checked onchange="applyFilters()"> All Products</label></li>
                <li><label><input type="radio" name="categoryFilter" value="Foundation" onchange="applyFilters()"> Foundation</label></li>
                <li><label><input type="radio" name="categoryFilter" value="blush" onchange="applyFilters()"> Blush</label></li>
                <li><label><input type="radio" name="categoryFilter" value="Eye Shadow" onchange="applyFilters()"> Eye Shadow</label></li>
                <li><label><input type="radio" name="categoryFilter" value="Lip Stick" onchange="applyFilters()"> Lip Stick</label></li>
                <li><label><input type="radio" name="categoryFilter" value="primer" onchange="applyFilters()"> Primer</label></li>
                <li><label><input type="radio" name="categoryFilter" value="necklace" onchange="applyFilters()"> Necklace</label></li>
                <li><label><input type="radio" name="categoryFilter" value="earing" onchange="applyFilters()"> Earrings</label></li>
                <li><label><input type="radio" name="categoryFilter" value="ring" onchange="applyFilters()"> Rings</label></li>
                <li><label><input type="radio" name="categoryFilter" value="bracelet" onchange="applyFilters()"> Bracelets</label></li>
            </ul>
        </div>

        <div class="filter-group">
            <h4>Price Range (Rs.)</h4>
            <div class="price-range-wrapper">
                <input type="number" id="minPriceInput" placeholder="Min Price (e.g. 300)" min="0">
                <input type="number" id="maxPriceInput" placeholder="Max Price (e.g. 1000)" min="0">
                <button class="btn-apply-price" onclick="applyFilters()">Apply Price</button>
            </div>
        </div>

        <button class="btn-clear-filters" onclick="clearAllFilters()">Clear All Filters</button>
    </div>

    <!-- RIGHT CONTENT -->
    <div class="category-content">
        <h2 class="section-title" style="text-align: left;">New Arrivals</h2>
        
        <div class="category-search-wrapper" style="margin: 0 0 30px 0;">
            <input type="text" id="categorySearchInput" placeholder="Search new arrivals..." onkeyup="filterCategoryProducts()">
            <button type="button" class="clear-search-btn" id="clearSearchBtn" onclick="clearAllFilters()"><i class="fas fa-times-circle"></i></button>
            <button><i class="fas fa-search"></i></button>
        </div>

        <!-- PRODUCTS GRID (SIRF NEW ARRIVALS) -->
        <div class="category-grid" id="categoryGrid" data-aos="fade-up">
            <?php 
            require_once 'includes/products_helper.php';
            $dbProducts = getAllProducts(null, 'new');
            if (!empty($dbProducts)):
                foreach ($dbProducts as $p):
                    echo renderSingleProductCard($p);
                endforeach;
            else:
            ?>
            <!-- NEW ARRIVAL 1: Foundation (New) -->
            <div class="category-card visible" data-name="Foundation" data-id="foundation_new_1">
                <div class="card-img-wrapper">
                    <img src="./img/new-arrival 1.jpg" alt="Foundation" onclick="openProductPopup('Foundation (New)', 750, './img/new-arrival 1.jpg', 'New arrival! A lightweight, full-coverage foundation for a flawless glow.')">
                </div>
                <h4>Foundation (New)</h4>
                <div class="card-price">Rs. 750</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Foundation (New)', 750, './img/new-arrival 1.jpg', 'foundation_new_1')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Foundation (New)', 750, './img/new-arrival 1.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- NEW ARRIVAL 2: Lipstick (New) -->
            <div class="category-card visible" data-name="Lip Stick" data-id="lipstick_new_1">
                <div class="card-img-wrapper">
                    <img src="./img/new-arrival 2.jpg" alt="Lipstick" onclick="openProductPopup('Lipstick (New)', 400, './img/new-arrival 2.jpg', 'New arrival! A bold red lipstick with a creamy finish.')">
                </div>
                <h4>Lipstick (New)</h4>
                <div class="card-price">Rs. 400</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Lipstick (New)', 400, './img/new-arrival 2.jpg', 'lipstick_new_1')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Lipstick (New)', 400, './img/new-arrival 2.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- NEW ARRIVAL 3: Necklace (New) -->
            <div class="category-card visible" data-name="necklace" data-id="necklace_new_1">
                <div class="card-img-wrapper">
                    <img src="./img/new-arrival 3.jpg" alt="Necklace" onclick="openProductPopup('Necklace (New)', 950, './img/new-arrival 3.jpg', 'New arrival! Elegant rose gold necklace with sparkling crystals.')">
                </div>
                <h4>Necklace (New)</h4>
                <div class="card-price">Rs. 950</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Necklace (New)', 950, './img/new-arrival 3.jpg', 'necklace_new_1')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Necklace (New)', 950, './img/new-arrival 3.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- NEW ARRIVAL 4: Earrings (New) -->
            <div class="category-card visible" data-name="earing" data-id="earring_new_1">
                <div class="card-img-wrapper">
                    <img src="./img/new-arrival 4.jpg" alt="Earrings" onclick="openProductPopup('Earrings (New)', 550, './img/new-arrival 4.jpg', 'New arrival! Stylish gold-plated earrings for any occasion.')">
                </div>
                <h4>Earrings (New)</h4>
                <div class="card-price">Rs. 550</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Earrings (New)', 550, './img/new-arrival 4.jpg', 'earring_new_1')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Earrings (New)', 550, './img/new-arrival 4.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- NEW ARRIVAL 5: Ring (New) -->
            <div class="category-card visible" data-name="ring" data-id="ring_new_1">
                <div class="card-img-wrapper">
                    <img src="./img/new-arrival 5.jpg" alt="Ring" onclick="openProductPopup('Ring (New)', 450, './img/new-arrival 5.jpg', 'New arrival! A beautifully crafted silver ring with a sapphire stone.')">
                </div>
                <h4>Ring (New)</h4>
                <div class="card-price">Rs. 450</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Ring (New)', 450, './img/new-arrival 5.jpg', 'ring_new_1')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Ring (New)', 450, './img/new-arrival 5.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- NEW ARRIVAL 6: Bracelet (New) -->
            <div class="category-card visible" data-name="bracelet" data-id="bracelet_new_1">
                <div class="card-img-wrapper">
                    <img src="./img/new-arrival 6.jpg" alt="Bracelet" onclick="openProductPopup('Bracelet (New)', 400, './img/new-arrival 6.jpg', 'New arrival! A trendy leather bracelet with gold accents.')">
                </div>
                <h4>Bracelet (New)</h4>
                <div class="card-price">Rs. 400</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Bracelet (New)', 400, './img/new-arrival 6.jpg', 'bracelet_new_1')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Bracelet (New)', 400, './img/new-arrival 6.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- NEW ARRIVAL 7: Blush (New) -->
            <div class="category-card visible" data-name="blush" data-id="blush_new_1">
                <div class="card-img-wrapper">
                    <img src="./img/new-arrival 7.jpg" alt="Blush" onclick="openProductPopup('Blush (New)', 320, './img/new-arrival 7.jpg', 'New arrival! A rosy pink blush for a natural glow.')">
                </div>
                <h4>Blush (New)</h4>
                <div class="card-price">Rs. 320</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Blush (New)', 320, './img/new-arrival 7.jpg', 'blush_new_1')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Blush (New)', 320, './img/new-arrival 7.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- NEW ARRIVAL 8: Eyeshadow (New) -->
            <div class="category-card visible" data-name="Eye Shadow" data-id="eyeshadow_new_1">
                <div class="card-img-wrapper">
                    <img src="./img/new-arrival 8.jpg" alt="Eyeshadow" onclick="openProductPopup('Eyeshadow (New)', 600, './img/new-arrival 8.jpg', 'New arrival! A 4-color shimmer eyeshadow palette.')">
                </div>
                <h4>Eyeshadow (New)</h4>
                <div class="card-price">Rs. 600</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Eyeshadow (New)', 600, './img/new-arrival 8.jpg', 'eyeshadow_new_1')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Eyeshadow (New)', 600, './img/new-arrival 8.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- NEW ARRIVAL 9: Primer (New) -->
            <div class="category-card visible" data-name="primer" data-id="primer_new_1">
                <div class="card-img-wrapper">
                    <img src="./img/new-arrival 9.jpg" alt="Primer" onclick="openProductPopup('Primer (New)', 280, './img/new-arrival 9.jpg', 'New arrival! A hydrating primer with a smooth finish.')">
                </div>
                <h4>Primer (New)</h4>
                <div class="card-price">Rs. 280</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Primer (New)', 280, './img/new-arrival 9.jpg', 'primer_new_1')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Primer (New)', 280, './img/new-arrival 9.jpg')">Buy Now</button>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<?php require 'includes/popup.php'; ?>

<!-- ============================================ -->
<!-- CART SIDEBAR (DRAWER) -->
<!-- ============================================ -->
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