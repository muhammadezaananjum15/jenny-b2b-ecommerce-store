<?php session_start(); ?>


<?php require 'includes/header.php'; ?>

<!-- CSS LINK ADDED HERE -->
<link rel="stylesheet" href="css/style.css">

<?php require 'includes/navbar.php'; ?>

<!-- ============================================ -->
<!-- HERO BANNER (ALL PRODUCTS) -->
<!-- ============================================ -->
<section class="products-hero">
    <div class="products-hero-content">
        <h1>All <span>Products</span></h1>
        <p>Explore our complete collection of premium cosmetics and imitation jewelry.</p>
        <div class="products-hero-buttons">
            <button class="btn-primary" onclick="location.href='#categoryGrid'">Shop Now</button>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- CATEGORY SECTION WITH SIDEBAR & ADVANCED FILTERS -->
<!-- ============================================ -->
<section class="category-page-wrapper">
    
    <!-- LEFT SIDEBAR (ADVANCED FILTERS) -->
    <div class="filter-sidebar" id="filterSidebar">
        <div class="filter-sidebar-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; border-bottom:1px solid #eee; padding-bottom:12px;">
            <h3 style="font-size:1.15rem; font-weight:700; color:var(--dark-black); margin:0;">
                <i class="fas fa-sliders-h" style="color:var(--primary-gold); margin-right:8px;"></i> Filters
            </h3>
            <button type="button" class="close-filter-drawer" onclick="toggleFilterDrawer(false)" aria-label="Close filters" style="display:none; background:none; border:none; font-size:1.2rem; cursor:pointer; color:#888;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Category Filter -->
        <div class="filter-group" style="margin-bottom:24px;">
            <h4 style="font-size:0.9rem; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:12px; color:var(--dark-black);">Category</h4>
            <ul class="filter-list" style="max-height:260px; overflow-y:auto; padding-right:5px;">
                <li><label><input type="radio" name="categoryFilter" value="all" checked onchange="applyFilters()"> All Products</label></li>
                <li><label><input type="radio" name="categoryFilter" value="Foundation" onchange="applyFilters()" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Foundation') ? 'checked' : ''; ?>> Foundation</label></li>
                <li><label><input type="radio" name="categoryFilter" value="Base Stick" onchange="applyFilters()" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Base Stick') ? 'checked' : ''; ?>> Base Stick</label></li>
                <li><label><input type="radio" name="categoryFilter" value="blush" onchange="applyFilters()" <?php echo (isset($_GET['category']) && $_GET['category'] == 'blush') ? 'checked' : ''; ?>> Blush</label></li>
                <li><label><input type="radio" name="categoryFilter" value="compact-powder" onchange="applyFilters()" <?php echo (isset($_GET['category']) && $_GET['category'] == 'compact-powder') ? 'checked' : ''; ?>> Compact Powder</label></li>
                <li><label><input type="radio" name="categoryFilter" value="concealer" onchange="applyFilters()" <?php echo (isset($_GET['category']) && $_GET['category'] == 'concealer') ? 'checked' : ''; ?>> Concealer</label></li>
                <li><label><input type="radio" name="categoryFilter" value="eye liner" onchange="applyFilters()" <?php echo (isset($_GET['category']) && $_GET['category'] == 'eye liner') ? 'checked' : ''; ?>> Eye Liner</label></li>
                <li><label><input type="radio" name="categoryFilter" value="Eye Shadow" onchange="applyFilters()" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Eye Shadow') ? 'checked' : ''; ?>> Eye Shadow</label></li>
                <li><label><input type="radio" name="categoryFilter" value="Mascara" onchange="applyFilters()" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Mascara') ? 'checked' : ''; ?>> Mascara</label></li>
                <li><label><input type="radio" name="categoryFilter" value="White Kajal" onchange="applyFilters()" <?php echo (isset($_GET['category']) && $_GET['category'] == 'White Kajal') ? 'checked' : ''; ?>> White Kajal</label></li>
                <li><label><input type="radio" name="categoryFilter" value="Kajal" onchange="applyFilters()" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Kajal') ? 'checked' : ''; ?>> Kajal</label></li>
                <li><label><input type="radio" name="categoryFilter" value="Lip Gloss" onchange="applyFilters()" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Lip Gloss') ? 'checked' : ''; ?>> Lip Gloss</label></li>
                <li><label><input type="radio" name="categoryFilter" value="Lip Stick" onchange="applyFilters()" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Lip Stick') ? 'checked' : ''; ?>> Lip Stick</label></li>
                <li><label><input type="radio" name="categoryFilter" value="lip tint" onchange="applyFilters()" <?php echo (isset($_GET['category']) && $_GET['category'] == 'lip tint') ? 'checked' : ''; ?>> Lip Tint</label></li>
                <li><label><input type="radio" name="categoryFilter" value="make up fixer" onchange="applyFilters()" <?php echo (isset($_GET['category']) && $_GET['category'] == 'make up fixer') ? 'checked' : ''; ?>> Make Up Fixer</label></li>
                <li><label><input type="radio" name="categoryFilter" value="primer" onchange="applyFilters()" <?php echo (isset($_GET['category']) && $_GET['category'] == 'primer') ? 'checked' : ''; ?>> Primer</label></li>
                <li><label><input type="radio" name="categoryFilter" value="necklace" onchange="applyFilters()" <?php echo (isset($_GET['category']) && $_GET['category'] == 'necklace') ? 'checked' : ''; ?>> Necklace</label></li>
                <li><label><input type="radio" name="categoryFilter" value="earing" onchange="applyFilters()" <?php echo (isset($_GET['category']) && $_GET['category'] == 'earing') ? 'checked' : ''; ?>> Earrings</label></li>
                <li><label><input type="radio" name="categoryFilter" value="ring" onchange="applyFilters()" <?php echo (isset($_GET['category']) && $_GET['category'] == 'ring') ? 'checked' : ''; ?>> Rings</label></li>
                <li><label><input type="radio" name="categoryFilter" value="bracelet" onchange="applyFilters()" <?php echo (isset($_GET['category']) && $_GET['category'] == 'bracelet') ? 'checked' : ''; ?>> Bracelets</label></li>
            </ul>
        </div>

        <!-- Price Range Filter -->
        <div class="filter-group" style="margin-bottom:24px;">
            <h4 style="font-size:0.9rem; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:12px; color:var(--dark-black);">Price Range (Rs.)</h4>
            <div class="price-range-wrapper" style="display:flex; flex-direction:column; gap:10px;">
                <div style="display:flex; gap:10px;">
                    <input type="number" id="minPriceInput" placeholder="Min" min="0" style="width:100%; padding:8px 12px; border:1px solid #ddd; border-radius:8px; font-size:0.85rem;" oninput="applyFilters()">
                    <input type="number" id="maxPriceInput" placeholder="Max" min="0" style="width:100%; padding:8px 12px; border:1px solid #ddd; border-radius:8px; font-size:0.85rem;" oninput="applyFilters()">
                </div>
                <!-- Price Presets -->
                <div class="price-presets" style="display:flex; flex-wrap:wrap; gap:6px;">
                    <button type="button" onclick="setPriceRange(0, 500)" style="font-size:0.72rem; padding:4px 8px; border-radius:12px; border:1px solid #ddd; background:#f9f9f9; cursor:pointer;">Under 500</button>
                    <button type="button" onclick="setPriceRange(500, 1000)" style="font-size:0.72rem; padding:4px 8px; border-radius:12px; border:1px solid #ddd; background:#f9f9f9; cursor:pointer;">500 - 1000</button>
                    <button type="button" onclick="setPriceRange(1000, 3000)" style="font-size:0.72rem; padding:4px 8px; border-radius:12px; border:1px solid #ddd; background:#f9f9f9; cursor:pointer;">1000+</button>
                </div>
            </div>
        </div>

        <!-- Rating Filter -->
        <div class="filter-group" style="margin-bottom:24px;">
            <h4 style="font-size:0.9rem; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:12px; color:var(--dark-black);">Minimum Rating</h4>
            <ul class="filter-list">
                <li><label><input type="radio" name="ratingFilter" value="0" checked onchange="applyFilters()"> All Ratings</label></li>
                <li><label><input type="radio" name="ratingFilter" value="4.5" onchange="applyFilters()"> 4.5 ★ &amp; above</label></li>
                <li><label><input type="radio" name="ratingFilter" value="4.0" onchange="applyFilters()"> 4.0 ★ &amp; above</label></li>
            </ul>
        </div>

        <button class="btn-clear-filters" onclick="clearAllFilters()" style="width:100%; padding:10px; border-radius:20px; background:#f0f0f0; border:1px solid #ddd; font-weight:600; font-size:0.85rem; cursor:pointer; transition:0.3s;">
            <i class="fas fa-redo-alt" style="margin-right:6px;"></i> Clear All Filters
        </button>
    </div>

    <!-- RIGHT CONTENT -->
    <div class="category-content">
        <!-- TOOLBAR & SEARCH -->
        <div class="catalog-toolbar" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px; margin-bottom:20px;">
            <div style="display:flex; align-items:center; gap:12px;">
                <button type="button" class="btn-toggle-filters-mobile" onclick="toggleFilterDrawer(true)" style="display:none; padding:8px 16px; border-radius:20px; background:var(--primary-gold); color:var(--dark-black); border:none; font-weight:600; font-size:0.85rem; cursor:pointer;">
                    <i class="fas fa-filter"></i> Filters
                </button>
                <h2 class="section-title" style="text-align: left; margin:0; font-size:1.6rem;">All Products</h2>
            </div>
            
            <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                <!-- Sort Dropdown -->
                <div class="sort-wrapper" style="display:flex; align-items:center; gap:8px;">
                    <label style="font-size:0.82rem; font-weight:600; color:#666;">Sort By:</label>
                    <select id="sortSelect" onchange="applyFilters()" style="padding:8px 14px; border:1px solid #ddd; border-radius:20px; font-size:0.85rem; background:#fff; outline:none; cursor:pointer;">
                        <option value="default">Featured / Default</option>
                        <option value="price-low">Price: Low to High</option>
                        <option value="price-high">Price: High to Low</option>
                        <option value="rating">Highest Rated</option>
                        <option value="name">Name: A to Z</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="category-search-wrapper" style="margin: 0 0 20px 0;">
            <input type="text" id="categorySearchInput" placeholder="Search products by name, tag, category..." onkeyup="applyFilters()">
            <button type="button" class="clear-search-btn" id="clearSearchBtn" onclick="clearAllFilters()"><i class="fas fa-times-circle"></i></button>
            <button type="button" onclick="applyFilters()"><i class="fas fa-search"></i></button>
        </div>

        <!-- ACTIVE FILTER CHIPS BAR -->
        <div id="activeFilterChips" style="display:flex; flex-wrap:wrap; gap:8px; margin-bottom:20px;"></div>

        <!-- PRODUCTS GRID (ALL PRODUCTS - COSMETICS + JEWELRY) -->
        <div class="category-grid" id="categoryGrid" data-aos="fade-up">
            <?php 
            require_once 'includes/products_helper.php';
            $dbProducts = getAllProducts();
            if (!empty($dbProducts)):
                foreach ($dbProducts as $p):
                    echo renderSingleProductCard($p);
                endforeach;
            else:
            ?>
            <!-- ==================== COSMETICS ==================== -->
            
            <!-- Foundation -->
            <div class="category-card visible" data-name="Foundation" data-id="foundation_1">
                <div class="card-img-wrapper">
                    <img src="./img/foundation.jpg" alt="Foundation" onclick="openProductPopup('Foundation', 600, './img/foundation.jpg', 'A lightweight, full-coverage foundation for a flawless glow.')">
                </div>
                <h4>Foundation</h4>
                <div class="card-price">Rs. 600</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Foundation', 600, './img/foundation.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Foundation', 600, './img/foundation.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="Foundation" data-id="foundation_2">
                <div class="card-img-wrapper">
                    <img src="./img/foundation2.jpg" alt="Foundation" onclick="openProductPopup('Foundation', 650, './img/foundation2.jpg', 'Oil-free matte finish foundation for all skin types.')">
                </div>
                <h4>Foundation</h4>
                <div class="card-price">Rs. 650</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Foundation', 650, './img/foundation2.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Foundation', 650, './img/foundation2.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="Foundation" data-id="foundation_3">
                <div class="card-img-wrapper">
                    <img src="./img/foundation3.jpg" alt="Foundation" onclick="openProductPopup('Foundation', 600, './img/foundation3.jpg', 'Hydrating foundation with SPF 30 for daily protection.')">
                </div>
                <h4>Foundation</h4>
                <div class="card-price">Rs. 600</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Foundation', 600, './img/foundation3.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Foundation', 600, './img/foundation3.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="Foundation" data-id="foundation_4">
                <div class="card-img-wrapper">
                    <img src="./img/foundation4.jpg" alt="Foundation" onclick="openProductPopup('Foundation', 550, './img/foundation4.jpg', 'Creamy foundation for dry skin with a dewy finish.')">
                </div>
                <h4>Foundation</h4>
                <div class="card-price">Rs. 550</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Foundation', 550, './img/foundation4.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Foundation', 550, './img/foundation4.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- Base Stick -->
            <div class="category-card visible" data-name="Base Stick" data-id="base_stick_1">
                <div class="card-img-wrapper">
                    <img src="./img/Base Stick.jpg" alt="Base Stick" onclick="openProductPopup('Base Stick', 500, './img/Base Stick.jpg', 'Easy to apply stick foundation for on-the-go touch-ups.')">
                </div>
                <h4>Base Stick</h4>
                <div class="card-price">Rs. 500</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Base Stick', 500, './img/Base Stick.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Base Stick', 500, './img/Base Stick.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="Base Stick" data-id="base_stick_2">
                <div class="card-img-wrapper">
                    <img src="./img/Base Stick2.jpg" alt="Base Stick" onclick="openProductPopup('Base Stick', 600, './img/Base Stick2.jpg', 'Long-lasting matte stick foundation with high coverage.')">
                </div>
                <h4>Base Stick</h4>
                <div class="card-price">Rs. 600</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Base Stick', 600, './img/Base Stick2.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Base Stick', 600, './img/Base Stick2.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="Base Stick" data-id="base_stick_3">
                <div class="card-img-wrapper">
                    <img src="./img/Base Stick3.jpg" alt="Base Stick" onclick="openProductPopup('Base Stick', 550, './img/Base Stick3.jpg', 'Lightweight stick foundation for a natural skin look.')">
                </div>
                <h4>Base Stick</h4>
                <div class="card-price">Rs. 550</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Base Stick', 550, './img/Base Stick3.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Base Stick', 550, './img/Base Stick3.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- BLUSH -->
            <div class="category-card visible" data-name="blush" data-id="blush_1">
                <div class="card-img-wrapper">
                    <img src="./img/Blush.jpg" alt="Blush" onclick="openProductPopup('Blush', 400, './img/Blush.jpg', 'Soft pink powder blush for a natural rosy glow.')">
                </div>
                <h4>Blush</h4>
                <div class="card-price">Rs. 400</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Blush', 400, './img/Blush.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Blush', 400, './img/Blush.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="blush" data-id="blush_2">
                <div class="card-img-wrapper">
                    <img src="./img/Blush2.jpg" alt="Blush" onclick="openProductPopup('Blush', 450, './img/Blush2.jpg', 'Peachy coral blush for a warm sunset look.')">
                </div>
                <h4>Blush</h4>
                <div class="card-price">Rs. 450</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Blush', 450, './img/Blush2.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Blush', 450, './img/Blush2.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="blush" data-id="blush_3">
                <div class="card-img-wrapper">
                    <img src="./img/Blush3.jpg" alt="Blush" onclick="openProductPopup('Blush', 350, './img/Blush3.jpg', 'Rich berry shade blush for evening wear.')">
                </div>
                <h4>Blush</h4>
                <div class="card-price">Rs. 350</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Blush', 350, './img/Blush3.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Blush', 350, './img/Blush3.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- COMPACT POWDER -->
            <div class="category-card visible" data-name="compact-powder" data-id="compact_powder_1">
                <div class="card-img-wrapper">
                    <img src="./img/COMPACT POWDER.jpg" alt="Compact Powder" onclick="openProductPopup('Compact Powder', 400, './img/COMPACT POWDER.jpg', 'Translucent setting powder for a shine-free finish.')">
                </div>
                <h4>Compact Powder</h4>
                <div class="card-price">Rs. 400</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Compact Powder', 400, './img/COMPACT POWDER.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Compact Powder', 400, './img/COMPACT POWDER.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="compact-powder" data-id="compact_powder_2">
                <div class="card-img-wrapper">
                    <img src="./img/COMPACT POWDER2.jpg" alt="Compact Powder" onclick="openProductPopup('Compact Powder', 390, './img/COMPACT POWDER2.jpg', 'Pressed powder with light coverage and SPF 15.')">
                </div>
                <h4>Compact Powder</h4>
                <div class="card-price">Rs. 390</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Compact Powder', 390, './img/COMPACT POWDER2.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Compact Powder', 390, './img/COMPACT POWDER2.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="compact-powder" data-id="compact_powder_3">
                <div class="card-img-wrapper">
                    <img src="./img/COMPACT POWDER3.jpg" alt="Compact Powder" onclick="openProductPopup('Compact Powder', 450, './img/COMPACT POWDER3.jpg', 'Matte finish compact powder for oily skin.')">
                </div>
                <h4>Compact Powder</h4>
                <div class="card-price">Rs. 450</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Compact Powder', 450, './img/COMPACT POWDER3.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Compact Powder', 450, './img/COMPACT POWDER3.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- Concealer -->
            <div class="category-card visible" data-name="concealer" data-id="concealer_1">
                <div class="card-img-wrapper">
                    <img src="./img/Concealer.jpg" alt="Concealer" onclick="openProductPopup('Concealer', 700, './img/Concealer.jpg', 'Full coverage concealer to hide dark circles and blemishes.')">
                </div>
                <h4>Concealer</h4>
                <div class="card-price">Rs. 700</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Concealer', 700, './img/Concealer.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Concealer', 700, './img/Concealer.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="concealer" data-id="concealer_2">
                <div class="card-img-wrapper">
                    <img src="./img/Concealer2.jpg" alt="Concealer" onclick="openProductPopup('Concealer', 750, './img/Concealer2.jpg', 'Creamy concealer with a hydrating formula.')">
                </div>
                <h4>Concealer</h4>
                <div class="card-price">Rs. 750</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Concealer', 750, './img/Concealer2.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Concealer', 750, './img/Concealer2.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- Eye liner -->
            <div class="category-card visible" data-name="eye liner" data-id="eye_liner_1">
                <div class="card-img-wrapper">
                    <img src="./img/Eye liner.jpg" alt="eye liner" onclick="openProductPopup('Eye liner', 450, './img/Eye liner.jpg', 'Intense black liquid eyeliner for a dramatic look.')">
                </div>
                <h4>Eye liner</h4>
                <div class="card-price">Rs. 450</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Eye liner', 450, './img/Eye liner.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Eye liner', 450, './img/Eye liner.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="eye liner" data-id="eye_liner_2">
                <div class="card-img-wrapper">
                    <img src="./img/Eye liner2.jpg" alt="eye liner" onclick="openProductPopup('Eye liner', 350, './img/Eye liner2.jpg', 'Waterproof eyeliner pen with a fine tip.')">
                </div>
                <h4>Eye liner</h4>
                <div class="card-price">Rs. 350</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Eye liner', 350, './img/Eye liner2.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Eye liner', 350, './img/Eye liner2.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="eye liner" data-id="eye_liner_3">
                <div class="card-img-wrapper">
                    <img src="./img/Eye liner3.jpg" alt="eye liner" onclick="openProductPopup('Eye liner', 450, './img/Eye liner3.jpg', 'Smudge-proof eyeliner for all-day wear.')">
                </div>
                <h4>Eye liner</h4>
                <div class="card-price">Rs. 450</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Eye liner', 450, './img/Eye liner3.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Eye liner', 450, './img/Eye liner3.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- Eye Shadow -->
            <div class="category-card visible" data-name="Eye Shadow" data-id="eye_shadow_1">
                <div class="card-img-wrapper">
                    <img src="./img/Eye Shadow.jpg" alt="Eye Shadow" onclick="openProductPopup('Eye Shadow', 900, './img/Eye Shadow.jpg', '12-color versatile eyeshadow palette for endless looks.')">
                </div>
                <h4>Eye Shadow</h4>
                <div class="card-price">Rs. 900</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Eye Shadow', 900, './img/Eye Shadow.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Eye Shadow', 900, './img/Eye Shadow.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="Eye Shadow" data-id="eye_shadow_2">
                <div class="card-img-wrapper">
                    <img src="./img/Eye Shadow2.jpg" alt="Eye Shadow" onclick="openProductPopup('Eye Shadow', 850, './img/Eye Shadow2.jpg', '6-color shimmer eyeshadow palette for a glam look.')">
                </div>
                <h4>Eye Shadow</h4>
                <div class="card-price">Rs. 850</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Eye Shadow', 850, './img/Eye Shadow2.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Eye Shadow', 850, './img/Eye Shadow2.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="Eye Shadow" data-id="eye_shadow_3">
                <div class="card-img-wrapper">
                    <img src="./img/Eye Shadow3.jpg" alt="Eye Shadow" onclick="openProductPopup('Eye Shadow', 900, './img/Eye Shadow3.jpg', 'Matte neutral eyeshadow palette for everyday wear.')">
                </div>
                <h4>Eye Shadow</h4>
                <div class="card-price">Rs. 900</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Eye Shadow', 900, './img/Eye Shadow3.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Eye Shadow', 900, './img/Eye Shadow3.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- Mascara -->
            <div class="category-card visible" data-name="Mascara" data-id="mascara_1">
                <div class="card-img-wrapper">
                    <img src="./img/Eyelashes Mascara.jpg" alt="Mascara" onclick="openProductPopup('Mascara', 550, './img/Eyelashes Mascara.jpg', 'Volumizing mascara for thick and dramatic lashes.')">
                </div>
                <h4>Mascara</h4>
                <div class="card-price">Rs. 550</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Mascara', 550, './img/Eyelashes Mascara.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Mascara', 550, './img/Eyelashes Mascara.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="Mascara" data-id="mascara_2">
                <div class="card-img-wrapper">
                    <img src="./img/Eyelashes Mascara2.jpg" alt="Mascara" onclick="openProductPopup('Mascara', 500, './img/Eyelashes Mascara2.jpg', 'Waterproof mascara for all-day smudge-free wear.')">
                </div>
                <h4>Mascara</h4>
                <div class="card-price">Rs. 500</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Mascara', 500, './img/Eyelashes Mascara2.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Mascara', 500, './img/Eyelashes Mascara2.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="Mascara" data-id="mascara_3">
                <div class="card-img-wrapper">
                    <img src="./img/Eyelashes Mascara3.jpg" alt="Mascara" onclick="openProductPopup('Mascara', 450, './img/Eyelashes Mascara3.jpg', 'Lengthening mascara with a unique brush for separation.')">
                </div>
                <h4>Mascara</h4>
                <div class="card-price">Rs. 450</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Mascara', 450, './img/Eyelashes Mascara3.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Mascara', 450, './img/Eyelashes Mascara3.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- White kajal -->
            <div class="category-card visible" data-name="White Kajal" data-id="white_kajal_1">
                <div class="card-img-wrapper">
                    <img src="./img/kajal White.jpg" alt="White Kajal" onclick="openProductPopup('White Kajal', 450, './img/kajal White.jpg', 'Brightening white kajal to make your eyes pop.')">
                </div>
                <h4>White Kajal</h4>
                <div class="card-price">Rs. 450</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'White Kajal', 450, './img/kajal White.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'White Kajal', 450, './img/kajal White.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="White Kajal" data-id="white_kajal_2">
                <div class="card-img-wrapper">
                    <img src="./img/kajal White2.jpg" alt="White Kajal" onclick="openProductPopup('White Kajal', 500, './img/kajal White2.jpg', 'Creamy white kajal pencil for waterline application.')">
                </div>
                <h4>White Kajal</h4>
                <div class="card-price">Rs. 500</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'White Kajal', 500, './img/kajal White2.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'White Kajal', 500, './img/kajal White2.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- kajal -->
            <div class="category-card visible" data-name="Kajal" data-id="kajal_1">
                <div class="card-img-wrapper">
                    <img src="./img/kajal.jpg" alt="Kajal" onclick="openProductPopup('Kajal', 350, './img/kajal.jpg', 'Deep black kajal for an intense and classic eye look.')">
                </div>
                <h4>Kajal</h4>
                <div class="card-price">Rs. 350</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Kajal', 350, './img/kajal.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Kajal', 350, './img/kajal.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- lip gloss -->
            <div class="category-card visible" data-name="Lip Gloss" data-id="lip_gloss_1">
                <div class="card-img-wrapper">
                    <img src="./img/Lip Gloss.jpg" alt="Lip Gloss" onclick="openProductPopup('Lip Gloss', 350, './img/Lip Gloss.jpg', 'High-shine lip gloss with a non-sticky formula.')">
                </div>
                <h4>Lip Gloss</h4>
                <div class="card-price">Rs. 350</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Lip Gloss', 350, './img/Lip Gloss.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Lip Gloss', 350, './img/Lip Gloss.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="Lip Gloss" data-id="lip_gloss_2">
                <div class="card-img-wrapper">
                    <img src="./img/Lip Gloss2.jpg" alt="Lip Gloss" onclick="openProductPopup('Lip Gloss', 250, './img/Lip Gloss2.jpg', 'Tinted lip gloss for a hint of color and hydration.')">
                </div>
                <h4>Lip Gloss</h4>
                <div class="card-price">Rs. 250</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Lip Gloss', 250, './img/Lip Gloss2.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Lip Gloss', 250, './img/Lip Gloss2.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="Lip Gloss" data-id="lip_gloss_3">
                <div class="card-img-wrapper">
                    <img src="./img/Lip Gloss3.jpg" alt="Lip Gloss" onclick="openProductPopup('Lip Gloss', 300, './img/Lip Gloss3.jpg', 'Plumping lip gloss for fuller-looking lips.')">
                </div>
                <h4>Lip Gloss</h4>
                <div class="card-price">Rs. 300</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Lip Gloss', 300, './img/Lip Gloss3.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Lip Gloss', 300, './img/Lip Gloss3.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="Lip Gloss" data-id="lip_gloss_4">
                <div class="card-img-wrapper">
                    <img src="./img/Lip Gloss4.jpg" alt="Lip Gloss" onclick="openProductPopup('Lip Gloss', 350, './img/Lip Gloss4.jpg', 'Sparkly lip gloss for a festive and glowing look.')">
                </div>
                <h4>Lip Gloss</h4>
                <div class="card-price">Rs. 350</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Lip Gloss', 350, './img/Lip Gloss4.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Lip Gloss', 350, './img/Lip Gloss4.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- lip stick -->
            <div class="category-card visible" data-name="Lip Stick" data-id="lip_stick_1">
                <div class="card-img-wrapper">
                    <img src="./img/Lip stick.jpg" alt="Lip Stick" onclick="openProductPopup('Lip Stick', 350, './img/Lip stick.jpg', 'Classic matte lipstick in a bold red shade.')">
                </div>
                <h4>Lip Stick</h4>
                <div class="card-price">Rs. 350</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Lip Stick', 350, './img/Lip stick.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Lip Stick', 350, './img/Lip stick.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="Lip Stick" data-id="lip_stick_2">
                <div class="card-img-wrapper">
                    <img src="./img/Lip stick2.jpg" alt="Lip Stick" onclick="openProductPopup('Lip Stick', 300, './img/Lip stick2.jpg', 'Creamy lipstick with a satin finish in a nude shade.')">
                </div>
                <h4>Lip Stick</h4>
                <div class="card-price">Rs. 300</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Lip Stick', 300, './img/Lip stick2.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Lip Stick', 300, './img/Lip stick2.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="Lip Stick " data-id="lip_stick_3">
                <div class="card-img-wrapper">
                    <img src="./img/Lip stick3.jpg" alt="Lip Stick" onclick="openProductPopup('Lip Stick', 350, './img/Lip stick3.jpg', 'Long-wearing lipstick in a trendy mauve shade.')">
                </div>
                <h4>Lip Stick</h4>
                <div class="card-price">Rs. 350</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Lip Stick', 350, './img/Lip stick3.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Lip Stick', 350, './img/Lip stick3.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- lip tint -->
            <div class="category-card visible" data-name="lip tint" data-id="lip_tint_1">
                <div class="card-img-wrapper">
                    <img src="./img/liptint.jpg" alt="Lip Tint" onclick="openProductPopup('Lip Tint', 350, './img/liptint.jpg', 'Watery lip tint for a natural and long-lasting stain.')">
                </div>
                <h4>Lip Tint</h4>
                <div class="card-price">Rs. 350</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Lip Tint', 350, './img/liptint.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Lip Tint', 350, './img/liptint.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="lip tint" data-id="lip_tint_2">
                <div class="card-img-wrapper">
                    <img src="./img/liptint2.jpg" alt="Lip Tint" onclick="openProductPopup('Lip Tint', 300, './img/liptint2.jpg', 'Velvet lip tint with a soft matte finish.')">
                </div>
                <h4>Lip Tint</h4>
                <div class="card-price">Rs. 300</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Lip Tint', 300, './img/liptint2.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Lip Tint', 300, './img/liptint2.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- make up fixer -->
            <div class="category-card visible" data-name="make up fixer" data-id="make_up_fixer_1">
                <div class="card-img-wrapper">
                    <img src="./img/make up fixer.jpg" alt="Make Up Fixer" onclick="openProductPopup('Make Up Fixer', 600, './img/make up fixer.jpg', 'Long-lasting makeup setting spray for a flawless finish.')">
                </div>
                <h4>Make Up Fixer</h4>
                <div class="card-price">Rs. 600</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Make Up Fixer', 600, './img/make up fixer.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Make Up Fixer', 600, './img/make up fixer.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="make up fixer" data-id="make_up_fixer_2">
                <div class="card-img-wrapper">
                    <img src="./img/make up fixer2.jpg" alt="Make Up Fixer" onclick="openProductPopup('Make Up Fixer', 650, './img/make up fixer2.jpg', 'Mattifying makeup fixer for oily and combination skin.')">
                </div>
                <h4>Make Up Fixer</h4>
                <div class="card-price">Rs. 650</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Make Up Fixer', 650, './img/make up fixer2.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Make Up Fixer', 650, './img/make up fixer2.jpg')">Buy Now</button>
                </div>
            </div>
            
            <div class="category-card visible" data-name="make up fixer" data-id="make_up_fixer_3">
                <div class="card-img-wrapper">
                    <img src="./img/make up fixer3.jpg" alt="Make Up Fixer" onclick="openProductPopup('Make Up Fixer', 600, './img/make up fixer3.jpg', 'Hydrating makeup fixer with a dewy glow effect.')">
                </div>
                <h4>Make Up Fixer</h4>
                <div class="card-price">Rs. 600</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Make Up Fixer', 600, './img/make up fixer3.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Make Up Fixer', 600, './img/make up fixer3.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- primer -->
            <div class="category-card visible" data-name="primer" data-id="primer_1">
                <div class="card-img-wrapper">
                    <img src="./img/Primer.jpg" alt="Primer" onclick="openProductPopup('Primer', 350, './img/Primer.jpg', 'Silicone-based primer for a smooth, poreless base.')">
                </div>
                <h4>Primer</h4>
                <div class="card-price">Rs. 350</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Primer', 350, './img/Primer.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Primer', 350, './img/Primer.jpg')">Buy Now</button>
                </div>
            </div>

            <div class="category-card visible" data-name="primer" data-id="primer_2">
                <div class="card-img-wrapper">
                    <img src="./img/Primer2.jpg" alt="Primer" onclick="openProductPopup('Primer', 450, './img/Primer2.jpg', 'Illuminating primer with a subtle glow for radiant skin.')">
                </div>
                <h4>Primer</h4>
                <div class="card-price">Rs. 450</div>
                <div class="card-qty-wrapper">
                    <div class="card-qty-box">
                        <button onclick="changeCardQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeCardQty(this, 1)">+</button>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, 'Primer', 450, './img/Primer2.jpg')">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, 'Primer', 450, './img/Primer2.jpg')">Buy Now</button>
                </div>
            </div>

            <!-- ==================== JEWELRY ==================== -->
            
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