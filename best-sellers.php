<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<?php require 'includes/header.php'; ?>

<!-- CSS LINK -->
<link rel="stylesheet" href="css/style.css">

<?php require 'includes/navbar.php'; ?>

<section class="bestsellers-hero">
    <div class="bestsellers-hero-content">
        <h1>Best <span>Sellers</span></h1>
        <p>Discover the most loved products by our customers. Top-rated and trending.</p>
        <div class="bestsellers-hero-buttons">
            <button class="btn-primary" onclick="location.href='#categoryGrid'">Shop Now</button>
                <button class="btn-outline" onclick="location.href='products.php'">Explore Collection</button>
        </div>
    </div>
</section>

<section class="category-page-wrapper">
    
    <!-- LEFT SIDEBAR (ALL CATEGORIES) -->
    <div class="filter-sidebar">
        <h3>Filter Best Sellers</h3>
        
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
                <h2 class="section-title" style="text-align: left; margin:0; font-size:1.8rem; font-family:var(--font-heading);">Best <span style="color:var(--primary-gold)">Sellers</span></h2>
                <p style="color:var(--text-grey); font-size:0.88rem; margin-top:4px;" id="productsCount">Loading best sellers...</p>
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
            <input type="text" id="categorySearchInput" placeholder="Search best sellers..." oninput="applyFilters()" onkeyup="applyFilters()">
            <button type="button" class="clear-search-btn" id="clearSearchBtn" onclick="clearAllFilters()" title="Clear search"><i class="fas fa-times-circle"></i></button>
            <button type="button" onclick="applyFilters()"><i class="fas fa-search"></i></button>
        </div>

        <div id="activeFilterChips" style="display:flex; flex-wrap:wrap; gap:8px; margin-bottom:20px;"></div>

        <!-- PRODUCTS GRID (SIRF BEST SELLERS) -->
        <div class="category-grid" id="categoryGrid" data-aos="fade-up">
            <?php 
            require_once 'includes/products_helper.php';
            $dbProducts = getAllProducts(null, 'bestsellers');
            if (!empty($dbProducts)):
                foreach ($dbProducts as $p):
                    echo renderSingleProductCard($p);
                endforeach;
            else:
            ?>
            <!-- BEST SELLER 1: Foundation (Classic) -->
            <div class="category-card visible" data-name="Foundation" data-id="foundation_best_1">
            <!-- Bestseller Badge -->
                <div class="bestseller-badge">Best Seller</div>
                <div class="card-img-wrapper">
                    <img src="./img/foundation.jpg" alt="Foundation" onclick="openProductPopup('Foundation', 600, './img/foundation.jpg', 'Best Seller! A lightweight, full-coverage foundation for a flawless glow.')">
                </div>
                <h4>Foundation</h4>
                <!-- Rating Stars -->
                <div class="card-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <span class="rating-count">(4.9 | 120+ reviews)</span>
                </div>
                <div class="card-price">Rs. 600</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Foundation', 600, './img/foundation.jpg', 'foundation_best_1')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Foundation', 600, './img/foundation.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- BEST SELLER 2: Lipstick (Red) -->
            <div class="category-card visible" data-name="Lip Stick" data-id="lipstick_best_1">
            <!-- Bestseller Badge -->
                <div class="bestseller-badge">Best Seller</div>
                <div class="card-img-wrapper">
                    <img src="./img/Lip stick.jpg" alt="Lipstick" onclick="openProductPopup('Lipstick', 350, './img/Lip stick.jpg', 'Best Seller! Classic matte lipstick in a bold red shade.')">
                </div>
                <h4>Lipstick</h4>
                <!-- Rating Stars -->
                <div class="card-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <span class="rating-count">(4.9 | 120+ reviews)</span>
                </div>
                <div class="card-price">Rs. 350</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Lipstick', 350, './img/Lip stick.jpg', 'lipstick_best_1')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Lipstick', 350, './img/Lip stick.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- BEST SELLER 3: Necklace (Rose Gold) -->
            <div class="category-card visible" data-name="necklace" data-id="necklace_best_1">
                <div class="bestseller-badge">Best Seller</div>
                <div class="card-img-wrapper">
                    <img src="./img/necklace.jpg" alt="Necklace" onclick="openProductPopup('Necklace', 700, './img/necklace.jpg', 'Best Seller! Elegant rose gold necklace for any occasion.')">
                </div>
                <h4>Necklace</h4>
                <!-- Rating Stars -->
                <div class="card-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <span class="rating-count">(4.9 | 120+ reviews)</span>
                </div>
                <div class="card-price">Rs. 700</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Necklace', 700, './img/necklace.jpg', 'necklace_best_1')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Necklace', 700, './img/necklace.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- BEST SELLER 4: Blush (Pink) -->
            <div class="category-card visible" data-name="blush" data-id="blush_best_1">
            <!-- Bestseller Badge -->
                <div class="bestseller-badge">Best Seller</div>
                <div class="card-img-wrapper">
                    <img src="./img/Blush.jpg" alt="Blush" onclick="openProductPopup('Blush', 400, './img/Blush.jpg', 'Best Seller! Soft pink powder blush for a natural rosy glow.')">
                </div>
                <h4>Blush</h4>
                <!-- Rating Stars -->
                <div class="card-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <span class="rating-count">(4.9 | 120+ reviews)</span>
                </div>
                <div class="card-price">Rs. 400</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Blush', 400, './img/Blush.jpg', 'blush_best_1')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Blush', 400, './img/Blush.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- BEST SELLER 5: Earrings (Crystal) -->
            <div class="category-card visible" data-name="earing" data-id="earring_best_1">
                <div class="bestseller-badge">Best Seller</div>
                <div class="card-img-wrapper">
                    <img src="./img/earing.jpg" alt="Earrings" onclick="openProductPopup('Earrings', 500, './img/earing.jpg', 'Best Seller! Stylish crystal earrings for any occasion.')">
                </div>
                <h4>Earrings</h4>
                <!-- Rating Stars -->
                <div class="card-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <span class="rating-count">(4.9 | 120+ reviews)</span>
                </div>
                <div class="card-price">Rs. 500</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Earrings', 500, './img/earing.jpg', 'earring_best_1')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Earrings', 500, './img/earing.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- BEST SELLER 6: Mascara (Black) -->
            <div class="category-card visible" data-name="Mascara" data-id="mascara_best_1">
            <!-- Bestseller Badge -->
                <div class="bestseller-badge">Best Seller</div>
                <div class="card-img-wrapper">
                    <img src="./img/Eyelashes Mascara.jpg" alt="Mascara" onclick="openProductPopup('Mascara', 550, './img/Eyelashes Mascara.jpg', 'Best Seller! Volumizing mascara for thick and dramatic lashes.')">
                </div>
                <h4>Mascara</h4>
                <!-- Rating Stars -->
                <div class="card-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <span class="rating-count">(4.9 | 120+ reviews)</span>
                </div>
                <div class="card-price">Rs. 550</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Mascara', 550, './img/Eyelashes Mascara.jpg', 'mascara_best_1')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Mascara', 550, './img/Eyelashes Mascara.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- BEST SELLER 7: Ring (Silver) -->
            <div class="category-card visible" data-name="ring" data-id="ring_best_1">
                <div class="bestseller-badge">Best Seller</div>
                <div class="card-img-wrapper">
                    <img src="./img/ring.jpg" alt="Ring" onclick="openProductPopup('Ring', 300, './img/ring.jpg', 'Best Seller! Elegant silver ring for any occasion.')">
                </div>
                <h4>Ring</h4>
                <!-- Rating Stars -->
                <div class="card-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <span class="rating-count">(4.9 | 120+ reviews)</span>
                </div>
                <div class="card-price">Rs. 300</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Ring', 300, './img/ring.jpg', 'ring_best_1')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Ring', 300, './img/ring.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- BEST SELLER 8: Bracelet (Gold) -->
            <div class="category-card visible" data-name="bracelet" data-id="bracelet_best_1">
                <div class="bestseller-badge">Best Seller</div>
                <div class="card-img-wrapper">
                    <img src="./img/bracelet.jpg" alt="Bracelet" onclick="openProductPopup('Bracelet', 450, './img/bracelet.jpg', 'Best Seller! Elegant gold bracelet for any occasion.')">
                </div>
                <h4>Bracelet</h4>
                <!-- Rating Stars -->
                <div class="card-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <span class="rating-count">(4.9 | 120+ reviews)</span>
                </div>
                <div class="card-price">Rs. 450</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Bracelet', 450, './img/bracelet.jpg', 'bracelet_best_1')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Bracelet', 450, './img/bracelet.jpg')">Buy Now</button>
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