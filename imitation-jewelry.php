<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<?php require 'includes/header.php'; ?>

<!-- CSS LINK -->
<link rel="stylesheet" href="css/style.css">

<?php require 'includes/navbar.php'; ?>

<!-- ============================================ -->
<!-- HERO BANNER (JEWELRY) -->
<!-- ============================================ -->
<section class="jewelry-hero">
    <div class="jewelry-hero-content">
        <h1>Premium <span>Jewelry</span> Collection</h1>
        <p>Discover our exclusive collection of imitation jewelry. Perfect for every occasion.</p>
        <div class="jewelry-hero-buttons">
                <button class="btn-primary" onclick="location.href='#categoryGrid'">Shop Now</button>
                <button class="btn-outline" onclick="location.href='products.php'">Explore Collection</button>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- CATEGORY SECTION WITH SIDEBAR & SEARCH -->
<!-- ============================================ -->
<section class="category-page-wrapper">
    
    <!-- LEFT SIDEBAR (SIRF JEWELRY) -->
    <div class="filter-sidebar">
        <h3>Filter Jewelry</h3>
        
        <div class="filter-group">
            <h4>Category</h4>
            <ul class="filter-list">
                <li><label><input type="radio" name="categoryFilter" value="all" checked onchange="applyFilters()"> All Products</label></li>
                <li><label><input type="radio" name="categoryFilter" value="necklace" onchange="applyFilters()"> Necklaces</label></li>
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
                <h2 class="section-title" style="text-align: left; margin:0; font-size:1.8rem; font-family:var(--font-heading);">Imitation <span style="color:var(--primary-gold)">Jewelry</span></h2>
                <p style="color:var(--text-grey); font-size:0.88rem; margin-top:4px;" id="productsCount">Loading jewelry...</p>
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
            <input type="text" id="categorySearchInput" placeholder="Search jewelry (e.g., necklace, earrings, ring, bracelet)..." oninput="applyFilters()" onkeyup="applyFilters()">
            <button type="button" class="clear-search-btn" id="clearSearchBtn" onclick="clearAllFilters()" title="Clear search"><i class="fas fa-times-circle"></i></button>
            <button type="button" onclick="applyFilters()"><i class="fas fa-search"></i></button>
        </div>

        <div id="activeFilterChips" style="display:flex; flex-wrap:wrap; gap:8px; margin-bottom:20px;"></div>

        <!-- PRODUCTS GRID (SIRF JEWELRY CARDS) -->
        <div class="category-grid" id="categoryGrid" data-aos="fade-up">
            <?php 
            require_once 'includes/products_helper.php';
            $dbProducts = getAllProducts('Jewelry');
            if (!empty($dbProducts)):
                foreach ($dbProducts as $p):
                    echo renderSingleProductCard($p);
                endforeach;
            else:
            ?>
            <!-- Necklace -->
            <div class="category-card visible" data-name="necklace" data-id="necklace_1">
                <div class="card-img-wrapper">
                    <img src="./img/necklace.jpg" alt="Necklace" onclick="openProductPopup('Necklace', 700, './img/necklace.jpg', 'Elegant necklace for any occasion.')">
                </div>
                <h4>Necklace</h4>
                <div class="card-price">Rs. 700</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Necklace', 700, './img/necklace.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Necklace', 700, './img/necklace.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="necklace" data-id="necklace_2">
                <div class="card-img-wrapper">
                    <img src="./img/necklace2.jpg" alt="Necklace" onclick="openProductPopup('Necklace', 800, './img/necklace2.jpg', 'Elegant necklace for any occasion.')">
                </div>
                <h4>Necklace</h4>
                <div class="card-price">Rs. 800</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Necklace', 800, './img/necklace2.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Necklace', 800, './img/necklace2.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="necklace" data-id="necklace_3">
                <div class="card-img-wrapper">
                    <img src="./img/necklace3.jpg" alt="Necklace" onclick="openProductPopup('Necklace', 700, './img/necklace3.jpg', 'Elegant necklace for any occasion.')">
                </div>
                <h4>Necklace</h4>
                <div class="card-price">Rs. 700</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Necklace', 700, './img/necklace3.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Necklace', 700, './img/necklace3.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="necklace" data-id="necklace_4">
                <div class="card-img-wrapper">
                    <img src="./img/necklace4.jpg" alt="Necklace" onclick="openProductPopup('Necklace', 850, './img/necklace4.jpg', 'Elegant necklace for any occasion.')">
                </div>
                <h4>Necklace</h4>
                <div class="card-price">Rs. 850</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Necklace', 850, './img/necklace4.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Necklace', 850, './img/necklace4.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="necklace" data-id="necklace_5">
                <div class="card-img-wrapper">
                    <img src="./img/necklace5.jpg" alt="Necklace" onclick="openProductPopup('Necklace', 850, './img/necklace5.jpg', 'Elegant necklace for any occasion.')">
                </div>
                <h4>Necklace</h4>
                <div class="card-price">Rs. 850</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Necklace', 850, './img/necklace5.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Necklace', 850, './img/necklace5.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="necklace" data-id="necklace_6">
                <div class="card-img-wrapper">
                    <img src="./img/necklace6.jpg" alt="Necklace" onclick="openProductPopup('Necklace', 850, './img/necklace6.jpg', 'Elegant necklace for any occasion.')">
                </div>
                <h4>Necklace</h4>
                <div class="card-price">Rs. 850</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Necklace', 850, './img/necklace6.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Necklace', 850, './img/necklace6.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="necklace" data-id="necklace_7">
                <div class="card-img-wrapper">
                    <img src="./img/necklace7.jpg" alt="Necklace" onclick="openProductPopup('Necklace', 850, './img/necklace7.jpg', 'Elegant necklace for any occasion.')">
                </div>
                <h4>Necklace</h4>
                <div class="card-price">Rs. 850</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Necklace', 850, './img/necklace7.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Necklace', 850, './img/necklace7.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="necklace" data-id="necklace_8">
                <div class="card-img-wrapper">
                    <img src="./img/necklace8.jpg" alt="Necklace" onclick="openProductPopup('Necklace', 800, './img/necklace8.jpg', 'Elegant necklace for any occasion.')">
                </div>
                <h4>Necklace</h4>
                <div class="card-price">Rs. 800</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Necklace', 800, './img/necklace8.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Necklace', 800, './img/necklace8.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- Earrings -->
            <div class="category-card visible" data-name="earing" data-id="earing_1">
                <div class="card-img-wrapper">
                    <img src="./img/earing.jpg" alt="Earring" onclick="openProductPopup('Earring', 500, './img/earing.jpg', 'Stylish earring for any occasion.')">
                </div>
                <h4>Earring</h4>
                <div class="card-price">Rs. 500</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Earring', 500, './img/earing.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Earring', 500, './img/earing.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="earing" data-id="earing_2">
                <div class="card-img-wrapper">
                    <img src="./img/earing2.jpg" alt="Earring" onclick="openProductPopup('Earring', 450, './img/earing2.jpg', 'Stylish earring for any occasion.')">
                </div>
                <h4>Earring</h4>
                <div class="card-price">Rs. 450</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Earring', 450, './img/earing2.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Earring', 450, './img/earing2.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="earing" data-id="earing_3">
                <div class="card-img-wrapper">
                    <img src="./img/earing3.jpg" alt="Earring" onclick="openProductPopup('Earring', 500, './img/earing3.jpg', 'Stylish earring for any occasion.')">
                </div>
                <h4>Earring</h4>
                <div class="card-price">Rs. 500</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Earring', 500, './img/earing3.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Earring', 500, './img/earing3.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="earing" data-id="earing_4">
                <div class="card-img-wrapper">
                    <img src="./img/earing4.jpg" alt="Earring" onclick="openProductPopup('Earring', 390, './img/earing4.jpg', 'Stylish earring for any occasion.')">
                </div>
                <h4>Earring</h4>
                <div class="card-price">Rs. 390</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Earring', 390, './img/earing4.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Earring', 390, './img/earing4.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="earing" data-id="earing_5">
                <div class="card-img-wrapper">
                    <img src="./img/earing5.jpg" alt="Earring" onclick="openProductPopup('Earring', 500, './img/earing5.jpg', 'Stylish earring for any occasion.')">
                </div>
                <h4>Earring</h4>
                <div class="card-price">Rs. 500</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Earring', 500, './img/earing5.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Earring', 500, './img/earing5.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="earing" data-id="earing_6">
                <div class="card-img-wrapper">
                    <img src="./img/earing6.jpg" alt="Earring" onclick="openProductPopup('Earring', 400, './img/earing6.jpg', 'Stylish earring for any occasion.')">
                </div>
                <h4>Earring</h4>
                <div class="card-price">Rs. 400</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Earring', 400, './img/earing6.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Earring', 400, './img/earing6.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="earing" data-id="earing_7">
                <div class="card-img-wrapper">
                    <img src="./img/earing7.jpg" alt="Earring" onclick="openProductPopup('Earring', 450, './img/earing7.jpg', 'Stylish earring for any occasion.')">
                </div>
                <h4>Earring</h4>
                <div class="card-price">Rs. 450</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Earring', 450, './img/earing7.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Earring', 450, './img/earing7.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- Rings -->
            <div class="category-card visible" data-name="ring" data-id="ring_1">
                <div class="card-img-wrapper">
                    <img src="./img/ring.jpg" alt="Ring" onclick="openProductPopup('Ring', 300, './img/ring.jpg', 'Elegant ring for any occasion.')">
                </div>
                <h4>Ring</h4>
                <div class="card-price">Rs. 300</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Ring', 300, './img/ring.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Ring', 300, './img/ring.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="ring" data-id="ring_2">
                <div class="card-img-wrapper">
                    <img src="./img/ring2.jpg" alt="Ring" onclick="openProductPopup('Ring', 350, './img/ring2.jpg', 'Elegant ring for any occasion.')">
                </div>
                <h4>Ring</h4>
                <div class="card-price">Rs. 350</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Ring', 350, './img/ring2.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Ring', 350, './img/ring2.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="ring" data-id="ring_3">
                <div class="card-img-wrapper">
                    <img src="./img/ring3.jpg" alt="Ring" onclick="openProductPopup('Ring', 300, './img/ring3.jpg', 'Elegant ring for any occasion.')">
                </div>
                <h4>Ring</h4>
                <div class="card-price">Rs. 300</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Ring', 300, './img/ring3.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Ring', 300, './img/ring3.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="ring" data-id="ring_4">
                <div class="card-img-wrapper">
                    <img src="./img/ring4.jpg" alt="Ring" onclick="openProductPopup('Ring', 350, './img/ring4.jpg', 'Elegant ring for any occasion.')">
                </div>
                <h4>Ring</h4>
                <div class="card-price">Rs. 350</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Ring', 350, './img/ring4.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Ring', 350, './img/ring4.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="ring" data-id="ring_5">
                <div class="card-img-wrapper">
                    <img src="./img/ring5.jpg" alt="Ring" onclick="openProductPopup('Ring', 300, './img/ring5.jpg', 'Elegant ring for any occasion.')">
                </div>
                <h4>Ring</h4>
                <div class="card-price">Rs. 300</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Ring', 300, './img/ring5.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Ring', 300, './img/ring5.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="ring" data-id="ring_6">
                <div class="card-img-wrapper">
                    <img src="./img/ring6.jpg" alt="Ring" onclick="openProductPopup('Ring', 400, './img/ring6.jpg', 'Elegant ring for any occasion.')">
                </div>
                <h4>Ring</h4>
                <div class="card-price">Rs. 400</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Ring', 400, './img/ring6.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Ring', 400, './img/ring6.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="ring" data-id="ring_7">
                <div class="card-img-wrapper">
                    <img src="./img/ring7.jpg" alt="Ring" onclick="openProductPopup('Ring', 350, './img/ring7.jpg', 'Elegant ring for any occasion.')">
                </div>
                <h4>Ring</h4>
                <div class="card-price">Rs. 350</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Ring', 350, './img/ring7.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Ring', 350, './img/ring7.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="ring" data-id="ring_8">
                <div class="card-img-wrapper">
                    <img src="./img/ring8.jpg" alt="Ring" onclick="openProductPopup('Ring', 300, './img/ring8.jpg', 'Elegant ring for any occasion.')">
                </div>
                <h4>Ring</h4>
                <div class="card-price">Rs. 300</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Ring', 300, './img/ring8.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Ring', 300, './img/ring8.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="ring" data-id="ring_9">
                <div class="card-img-wrapper">
                    <img src="./img/ring9.jpg" alt="Ring" onclick="openProductPopup('Ring', 350, './img/ring9.jpg', 'Elegant ring for any occasion.')">
                </div>
                <h4>Ring</h4>
                <div class="card-price">Rs. 350</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Ring', 350, './img/ring9.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Ring', 350, './img/ring9.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="ring" data-id="ring_10">
                <div class="card-img-wrapper">
                    <img src="./img/ring10.jpg" alt="Ring" onclick="openProductPopup('Ring', 300, './img/ring10.jpg', 'Elegant ring for any occasion.')">
                </div>
                <h4>Ring</h4>
                <div class="card-price">Rs. 300</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Ring', 300, './img/ring10.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Ring', 300, './img/ring10.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="ring" data-id="ring_11">
                <div class="card-img-wrapper">
                    <img src="./img/ring11.jpg" alt="Ring" onclick="openProductPopup('Ring', 400, './img/ring11.jpg', 'Elegant ring for any occasion.')">
                </div>
                <h4>Ring</h4>
                <div class="card-price">Rs. 400</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Ring', 400, './img/ring11.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Ring', 400, './img/ring11.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- Bracelets -->
            <div class="category-card visible" data-name="bracelet" data-id="bracelet_1">
                <div class="card-img-wrapper">
                    <img src="./img/bracelet.jpg" alt="Bracelet" onclick="openProductPopup('Bracelet', 450, './img/bracelet.jpg', 'Elegant bracelet for any occasion.')">
                </div>
                <h4>Bracelet</h4>
                <div class="card-price">Rs. 450</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Bracelet', 450, './img/bracelet.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Bracelet', 450, './img/bracelet.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="bracelet" data-id="bracelet_2">
                <div class="card-img-wrapper">
                    <img src="./img/bracelet2.jpg" alt="Bracelet" onclick="openProductPopup('Bracelet', 500, './img/bracelet2.jpg', 'Elegant bracelet for any occasion.')">
                </div>
                <h4>Bracelet</h4>
                <div class="card-price">Rs. 500</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Bracelet', 500, './img/bracelet2.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Bracelet', 500, './img/bracelet2.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="bracelet" data-id="bracelet_3">
                <div class="card-img-wrapper">
                    <img src="./img/bracelet3.jpg" alt="Bracelet" onclick="openProductPopup('Bracelet', 450, './img/bracelet3.jpg', 'Elegant bracelet for any occasion.')">
                </div>
                <h4>Bracelet</h4>
                <div class="card-price">Rs. 450</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Bracelet', 450, './img/bracelet3.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Bracelet', 450, './img/bracelet3.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="bracelet" data-id="bracelet_4">
                <div class="card-img-wrapper">
                    <img src="./img/bracelet4.jpg" alt="Bracelet" onclick="openProductPopup('Bracelet', 350, './img/bracelet4.jpg', 'Elegant bracelet for any occasion.')">
                </div>
                <h4>Bracelet</h4>
                <div class="card-price">Rs. 350</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Bracelet', 350, './img/bracelet4.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Bracelet', 350, './img/bracelet4.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="bracelet" data-id="bracelet_5">
                <div class="card-img-wrapper">
                    <img src="./img/bracelet5.jpg" alt="Bracelet" onclick="openProductPopup('Bracelet', 450, './img/bracelet5.jpg', 'Elegant bracelet for any occasion.')">
                </div>
                <h4>Bracelet</h4>
                <div class="card-price">Rs. 450</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Bracelet', 450, './img/bracelet5.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Bracelet', 450, './img/bracelet5.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="bracelet" data-id="bracelet_6">
                <div class="card-img-wrapper">
                    <img src="./img/bracelet6.jpg" alt="Bracelet" onclick="openProductPopup('Bracelet', 390, './img/bracelet6.jpg', 'Elegant bracelet for any occasion.')">
                </div>
                <h4>Bracelet</h4>
                <div class="card-price">Rs. 390</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Bracelet', 390, './img/bracelet6.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Bracelet', 390, './img/bracelet6.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="bracelet" data-id="bracelet_7">
                <div class="card-img-wrapper">
                    <img src="./img/bracelet7.jpg" alt="Bracelet" onclick="openProductPopup('Bracelet', 450, './img/bracelet7.jpg', 'Elegant bracelet for any occasion.')">
                </div>
                <h4>Bracelet</h4>
                <div class="card-price">Rs. 450</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Bracelet', 450, './img/bracelet7.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Bracelet', 450, './img/bracelet7.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="bracelet" data-id="bracelet_8">
                <div class="card-img-wrapper">
                    <img src="./img/bracelet8.jpg" alt="Bracelet" onclick="openProductPopup('Bracelet', 490, './img/bracelet8.jpg', 'Elegant bracelet for any occasion.')">
                </div>
                <h4>Bracelet</h4>
                <div class="card-price">Rs. 490</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Bracelet', 490, './img/bracelet8.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Bracelet', 490, './img/bracelet8.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="bracelet" data-id="bracelet_9">
                <div class="card-img-wrapper">
                    <img src="./img/bracelet9.jpg" alt="Bracelet" onclick="openProductPopup('Bracelet', 350, './img/bracelet9.jpg', 'Elegant bracelet for any occasion.')">
                </div>
                <h4>Bracelet</h4>
                <div class="card-price">Rs. 350</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Bracelet', 350, './img/bracelet9.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Bracelet', 350, './img/bracelet9.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="bracelet" data-id="bracelet_10">
                <div class="card-img-wrapper">
                    <img src="./img/bracelet10.jpg" alt="Bracelet" onclick="openProductPopup('Bracelet', 350, './img/bracelet10.jpg', 'Elegant bracelet for any occasion.')">
                </div>
                <h4>Bracelet</h4>
                <div class="card-price">Rs. 350</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Bracelet', 350, './img/bracelet10.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Bracelet', 350, './img/bracelet10.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="bracelet" data-id="bracelet_11">
                <div class="card-img-wrapper">
                    <img src="./img/bracelet11.jpg" alt="Bracelet" onclick="openProductPopup('Bracelet', 350, './img/bracelet11.jpg', 'Elegant bracelet for any occasion.')">
                </div>
                <h4>Bracelet</h4>
                <div class="card-price">Rs. 350</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Bracelet', 350, './img/bracelet11.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Bracelet', 350, './img/bracelet11.jpg')">Buy Now</button>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- PROMO BANNER (BUY 2 GET 1 FREE ON JEWELRY) -->
<!-- ============================================ -->
<section class="jewelry-promo">
    <div class="jewelry-promo-content">
        <span class="promo-tag">Limited Time Offer</span>
        <h2>Buy 2 Get <span style="color: var(--primary-gold);">1 Free</span></h2>
        <p>On all Necklaces, Earrings, and Rings. Use code: <strong style="color: var(--primary-gold);">JEWEL10</strong></p>
        <button class="btn-primary" onclick="window.location.href='#categoryGrid'">Shop Now</button>
    </div>
</section>

<!-- ============================================ -->
<!-- WHY CHOOSE US -->
<!-- ============================================ -->
<section class="why-choose-us">
    <h2 class="section-title" style="text-align: center;">Why <span style="color: var(--primary-gold);">Choose Us</span></h2>
    <div class="why-grid">
        <div class="why-item">
            <i class="fas fa-leaf"></i>
            <h4>Cruelty Free</h4>
            <p>We never test on animals. 100% ethical beauty.</p>
        </div>
        <div class="why-item">
            <i class="fas fa-check-circle"></i>
            <h4>100% Authentic</h4>
            <p>All products are sourced directly from trusted manufacturers.</p>
        </div>
        <div class="why-item">
            <i class="fas fa-truck-fast"></i>
            <h4>Free Delivery</h4>
            <p>Free shipping on all orders over Rs. 1999.</p>
        </div>
        <div class="why-item">
            <i class="fas fa-undo-alt"></i>
            <h4>Easy Returns</h4>
            <p>7-day hassle-free return policy.</p>
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