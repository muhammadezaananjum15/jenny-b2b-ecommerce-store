<?php session_start(); ?>

<?php require 'includes/header.php'; ?>


<!-- CSS LINK -->
<link rel="stylesheet" href="./css/style.css">

<?php require 'includes/navbar.php'; ?>

<!-- ============================================ -->
<!-- HERO BANNER (COSMETICS) -->
<!-- ============================================ -->
<section class="cosmetics-hero">
    <div class="cosmetics-hero-content">
        <h1>Premium <span>Cosmetics</span> Collection</h1>
        <p>Explore our wide range of high-quality makeup products. Cruelty-free and 100% authentic.</p>
        <div class="cosmetics-hero-buttons">
                <button class="btn-primary" onclick="location.href='#categoryGrid'">Shop Now</button>
                <button class="btn-outline" onclick="location.href='products.php'">Explore Collection</button>
        </div>
    </div>
</section>

    <!-- ============================================ -->
    <!-- CATEGORY SECTION WITH SIDEBAR & SEARCH -->
    <!-- ============================================ -->
    <section class="category-page-wrapper">
        
        <!-- LEFT SIDEBAR -->
        <div class="filter-sidebar">
            <h3>Filter Products</h3>
            
            <div class="filter-group">
                <h4>Category</h4>
                <ul class="filter-list">
                    <li><label><input type="radio" name="categoryFilter" value="all" checked onchange="applyFilters()"> All Products</label></li>
                    <li><label><input type="radio" name="categoryFilter" value="Foundation" onchange="applyFilters()"> Foundation</label></li>
                    <li><label><input type="radio" name="categoryFilter" value="Base Stick" onchange="applyFilters()"> Base Stick</label></li>
                    <li><label><input type="radio" name="categoryFilter" value="blush" onchange="applyFilters()"> Blush</label></li>
                    <li><label><input type="radio" name="categoryFilter" value="compact-powder" onchange="applyFilters()"> Compact Powder</label></li>
                    <li><label><input type="radio" name="categoryFilter" value="concealer" onchange="applyFilters()"> Concealer</label></li>
                    <li><label><input type="radio" name="categoryFilter" value="eye liner" onchange="applyFilters()"> Eye Liner</label></li>
                    <li><label><input type="radio" name="categoryFilter" value="Eye Shadow" onchange="applyFilters()"> Eye Shadow</label></li>
                    <li><label><input type="radio" name="categoryFilter" value="Mascara" onchange="applyFilters()"> Mascara</label></li>
                    <li><label><input type="radio" name="categoryFilter" value="White Kajal" onchange="applyFilters()"> White Kajal</label></li>
                    <li><label><input type="radio" name="categoryFilter" value="Kajal" onchange="applyFilters()"> Kajal</label></li>
                    <li><label><input type="radio" name="categoryFilter" value="Lip Gloss" onchange="applyFilters()"> Lip Gloss</label></li>
                    <li><label><input type="radio" name="categoryFilter" value="Lip Stick" onchange="applyFilters()"> Lip Stick</label></li>
                    <li><label><input type="radio" name="categoryFilter" value="lip tint" onchange="applyFilters()"> Lip Tint</label></li>
                    <li><label><input type="radio" name="categoryFilter" value="make up fixer" onchange="applyFilters()"> Make Up Fixer</label></li>
                    <li><label><input type="radio" name="categoryFilter" value="primer" onchange="applyFilters()"> Primer</label></li>
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
        <h2 class="section-title" style="text-align: left;">All Cosmetics</h2>
        
            <div class="category-search-wrapper" style="margin: 0 0 30px 0;">
                <input type="text" id="categorySearchInput" placeholder="Search product here..." onkeyup="filterCategoryProducts()">
                <!-- Cancel Button Link Fixed -->
                <button type="button" class="clear-search-btn" id="clearSearchBtn" onclick="clearAllFilters()"><i class="fas fa-times-circle"></i></button>
                <button><i class="fas fa-search"></i></button>
            </div>

             <!-- PRODUCTS GRID (SIRF COSMETICS CARDS) -->
             <div class="category-grid" id="categoryGrid" data-aos="fade-up">
                <?php 
                require_once 'includes/products_helper.php';
                $dbProducts = getAllProducts('Cosmetics');
                if (!empty($dbProducts)):
                    foreach ($dbProducts as $p):
                        echo renderSingleProductCard($p);
                    endforeach;
                else:
                ?>
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
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!-- PROMO BANNER (BUY 2 GET 1 FREE) -->
<!-- ============================================ -->
<section class="cosmetics-promo">
    <div class="cosmetics-promo-content">
        <span class="promo-tag">Limited Time Offer</span>
        <h2>Buy 2 Get <span style="color: var(--primary-gold);">1 Free</span></h2>
        <p>On all Lipsticks, Lip Gloss, and Lip Tints. Use code: <strong style="color: var(--primary-gold);">LIP10</strong></p>
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

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<!-- Custom JS -->
<script src="./includes/js/script.js"></script>
</body>
</html>