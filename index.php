<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/db.php';

// Auto-sync high-res AI generated hero images if available in artifacts
$aiImg1 = 'C:/Users/HP/.gemini/antigravity-ide/brain/e6a7993b-3db8-46ad-a00c-5699c7072618/hero_luxury_beauty_banner_1786384333201.png';
$aiImg2 = 'C:/Users/HP/.gemini/antigravity-ide/brain/e6a7993b-3db8-46ad-a00c-5699c7072618/hero_luxury_cosmetics_banner_1786384352031.png';
$aiImg3 = 'C:/Users/HP/.gemini/antigravity-ide/brain/e6a7993b-3db8-46ad-a00c-5699c7072618/hero_luxury_jewelry_banner_1786384366469.png';
if (file_exists($aiImg1)) { @copy($aiImg1, __DIR__ . '/img/hero-ai-beauty.png'); }
if (file_exists($aiImg2)) { @copy($aiImg2, __DIR__ . '/img/hero-ai-cosmetics.png'); }
if (file_exists($aiImg3)) { @copy($aiImg3, __DIR__ . '/img/hero-ai-jewelry.png'); }

require 'includes/header.php';
?>

<!-- Page-specific styles loaded via header.php -->

<?php require 'includes/navbar.php'; ?>

<?php
// Fetch active hero slides from DB
$dbSlides = [];
if (isset($pdo)) {
    try {
        $dbSlides = $pdo->query("SELECT * FROM hero_slides WHERE is_active = 1 ORDER BY sort_order ASC")->fetchAll();
    } catch (Exception $e) {}
}
?>
<section class="hero-carousel" id="heroCarousel">
    <canvas id="heroParticleCanvas" class="hero-particle-canvas"></canvas>

    <div class="carousel-slides-wrapper" id="carouselWrapper">
        <?php if (!empty($dbSlides)): ?>
            <?php foreach ($dbSlides as $idx => $slide): ?>
            <div class="carousel-slide <?= $idx === 0 ? 'active' : '' ?>" data-slide="<?= $idx ?>">
                <div class="slide-bg" style="background-image: url('img/<?= htmlspecialchars($slide['image']) ?>');"></div>
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <h3 <?= $idx === 0 ? 'data-anim="sub"' : '' ?>><?= htmlspecialchars($slide['subtitle']) ?></h3>
                    <h1 <?= $idx === 0 ? 'data-anim="title"' : '' ?>><?= htmlspecialchars($slide['title']) ?> <span><?= htmlspecialchars($slide['title_highlight']) ?></span></h1>
                    <p <?= $idx === 0 ? 'data-anim="desc"' : '' ?>><?= htmlspecialchars($slide['description']) ?></p>
                    <div class="slide-buttons" <?= $idx === 0 ? 'data-anim="btns"' : '' ?>>
                        <?php if (!empty($slide['btn1_text'])): ?>
                        <button class="btn-primary" onclick="location.href='<?= htmlspecialchars($slide['btn1_link']) ?>'"><?= htmlspecialchars($slide['btn1_text']) ?></button>
                        <?php endif; ?>
                        <?php if (!empty($slide['btn2_text'])): ?>
                        <button class="btn-outline" onclick="location.href='<?= htmlspecialchars($slide['btn2_link']) ?>'"><?= htmlspecialchars($slide['btn2_text']) ?></button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Fallback Slide 1: Beauty -->
            <div class="carousel-slide active" data-slide="0">
                <div class="slide-bg" style="background-image: url('img/hero-ai-beauty.png');"></div>
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <h3 data-anim="sub">Elevate Your</h3>
                    <h1 data-anim="title">Beauty &amp; <span>Shine</span></h1>
                    <p data-anim="desc">Premium cosmetics &amp; imitation jewelry crafted for the modern woman. Discover high-pigment formulas &amp; royal elegance.</p>
                    <div class="slide-buttons" data-anim="btns">
                        <button class="btn-primary" onclick="location.href='products.php'">Shop Now</button>
                        <button class="btn-outline" onclick="location.href='cosmetics.php'">Explore</button>
                    </div>
                </div>
            </div>
            <!-- Fallback Slide 2: Cosmetics -->
            <div class="carousel-slide" data-slide="1">
                <div class="slide-bg" style="background-image: url('img/hero-ai-cosmetics.png');"></div>
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <h3>Luxury Makeup</h3>
                    <h1>Flawless <span>Glamour</span></h1>
                    <p>Long-lasting matte lipsticks, silk foundations, and highlighters for a luminous complexion.</p>
                    <div class="slide-buttons">
                        <button class="btn-primary" onclick="location.href='cosmetics.php'">Shop Cosmetics</button>
                        <button class="btn-outline" onclick="location.href='offers.php'">View Offers</button>
                    </div>
                </div>
            </div>
            <!-- Fallback Slide 3: Jewelry -->
            <div class="carousel-slide" data-slide="2">
                <div class="slide-bg" style="background-image: url('img/hero-ai-jewelry.png');"></div>
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <h3>Royal Craftsmanship</h3>
                    <h1>Shine Like <span>Gold</span></h1>
                    <p>Exquisite bridal sets, gold-plated necklaces, rings, and earrings designed for royalty.</p>
                    <div class="slide-buttons">
                        <button class="btn-primary" onclick="location.href='imitation-jewelry.php'">Shop Jewelry</button>
                        <button class="btn-outline" onclick="location.href='best-sellers.php'">Bestsellers</button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div><!-- /carousel-slides-wrapper -->

    <!-- Controls -->
    <button class="carousel-arrow carousel-prev" id="carouselPrev" aria-label="Previous">
        <i class="fas fa-chevron-left"></i>
    </button>
    <button class="carousel-arrow carousel-next" id="carouselNext" aria-label="Next">
        <i class="fas fa-chevron-right"></i>
    </button>

    <!-- Dots -->
    <div class="carousel-dots" id="carouselDots">
        <?php 
        $dotCount = !empty($dbSlides) ? count($dbSlides) : 3;
        for ($d = 0; $d < $dotCount; $d++): 
        ?>
        <button class="dot <?= $d === 0 ? 'active' : '' ?>" data-dot="<?= $d ?>"></button>
        <?php endfor; ?>
    </div>
</section>

<section class="features">
    <div class="feature-item" data-aos="fade-up" data-aos-delay="0">
        <i class="fas fa-gem"></i>
        <div><h4>Premium Quality</h4><p>Carefully curated products</p></div>
    </div>
    <div class="feature-item" data-aos="fade-up" data-aos-delay="100">
        <i class="fas fa-lock"></i>
        <div><h4>Secure Payments</h4><p>100% safe &amp; secure</p></div>
    </div>
    <div class="feature-item" data-aos="fade-up" data-aos-delay="200">
        <i class="fas fa-truck-fast"></i>
        <div><h4>Fast Delivery</h4><p>Quick &amp; reliable shipping</p></div>
    </div>
    <div class="feature-item" data-aos="fade-up" data-aos-delay="300">
        <i class="fas fa-undo-alt"></i>
        <div><h4>Easy Returns</h4><p>Hassle-free return policy</p></div>
    </div>
</section>

<section class="featured-categories" id="featuredCategories">
    <div class="section-header" data-aos="fade-up">
        <h2 class="section-title">Shop by <span style="color:var(--primary-gold)">Category</span></h2>
        <a href="products.php" class="view-all-btn">View All <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="featured-grid">
        <div class="featured-card" data-aos="zoom-in" data-aos-delay="0">
            <div class="featured-img-wrapper">
                <img src="./img/foundation.jpg" alt="Foundation">
                <div class="featured-card-overlay"><span>Explore</span></div>
            </div>
            <h4>Foundation</h4>
            <p>Explore Our Range</p>
            <a href="products.php?category=Foundation" class="featured-btn">Shop Now</a>
        </div>
        <div class="featured-card" data-aos="zoom-in" data-aos-delay="100">
            <div class="featured-img-wrapper">
                <img src="./img/Lip stick.jpg" alt="Lipstick">
                <div class="featured-card-overlay"><span>Explore</span></div>
            </div>
            <h4>Lipstick</h4>
            <p>Explore Our Range</p>
            <a href="products.php?category=Lip%20Stick" class="featured-btn">Shop Now</a>
        </div>
        <div class="featured-card" data-aos="zoom-in" data-aos-delay="200">
            <div class="featured-img-wrapper">
                <img src="./img/necklace.jpg" alt="Necklace">
                <div class="featured-card-overlay"><span>Explore</span></div>
            </div>
            <h4>Necklace</h4>
            <p>Explore Our Range</p>
            <a href="products.php?category=necklace" class="featured-btn">Shop Now</a>
        </div>
        <div class="featured-card" data-aos="zoom-in" data-aos-delay="300">
            <div class="featured-img-wrapper">
                <img src="./img/ring.jpg" alt="Rings">
                <div class="featured-card-overlay"><span>Explore</span></div>
            </div>
            <h4>Rings</h4>
            <p>Explore Our Range</p>
            <a href="products.php?category=ring" class="featured-btn">Shop Now</a>
        </div>
        <div class="featured-card" data-aos="zoom-in" data-aos-delay="400">
            <div class="featured-img-wrapper">
                <img src="./img/bracelet.jpg" alt="Bracelets">
                <div class="featured-card-overlay"><span>Explore</span></div>
            </div>
            <h4>Bracelets</h4>
            <p>Explore Our Range</p>
            <a href="products.php?category=bracelet" class="featured-btn">Shop Now</a>
        </div>
        <div class="featured-card" data-aos="zoom-in" data-aos-delay="500">
            <div class="featured-img-wrapper">
                <img src="./img/Eye Shadow.jpg" alt="Eye Shadow">
                <div class="featured-card-overlay"><span>Explore</span></div>
            </div>
            <h4>Eye Shadow</h4>
            <p>Explore Our Range</p>
            <a href="products.php?category=Eye%20Shadow" class="featured-btn">Shop Now</a>
        </div>
    </div>
</section>

<section class="stats-section">
    <div class="stats-container">
        <div class="stat-item" data-aos="fade-up" data-aos-delay="0">
            <div class="stat-number" data-count="500">0</div>
            <div class="stat-plus">+</div>
            <div class="stat-label">Products</div>
        </div>
        <div class="stat-item" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-number" data-count="15000">0</div>
            <div class="stat-plus">+</div>
            <div class="stat-label">Happy Customers</div>
        </div>
        <div class="stat-item" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-number" data-count="12000">0</div>
            <div class="stat-plus">+</div>
            <div class="stat-label">Orders Delivered</div>
        </div>
        <div class="stat-item" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-number" data-count="50">0</div>
            <div class="stat-plus">+</div>
            <div class="stat-label">Cities Served</div>
        </div>
    </div>
</section>

<section class="brand-story">
    <div class="story-img-col" data-aos="fade-right">
        <div class="story-img-stack">
            <img src="img/our story.png" alt="Our Story" class="story-main-img" loading="lazy">
            <div class="story-badge">
                <i class="fas fa-gem"></i>
                <span>Est. 2020</span>
            </div>
        </div>
    </div>
    <div class="story-content-col" data-aos="fade-left">
        <div class="story-tag">Our Story</div>
        <h2>Crafted with <span>Passion</span>,<br>Built on <span>Trust</span></h2>
        <p class="story-lead">Jenny's Cosmetics &amp; Jewelry was born from a simple dream | to make every woman feel beautiful without breaking the bank.</p>
        <p>We believe that premium quality doesn't have to come with a premium price tag. Our carefully curated collection of cosmetics and imitation jewelry is sourced from the finest suppliers to bring you products that look, feel, and perform like luxury.</p>
        <div class="story-values">
            <div class="value-item"><i class="fas fa-leaf"></i><span>Cruelty Free</span></div>
            <div class="value-item"><i class="fas fa-award"></i><span>Award Winning</span></div>
            <div class="value-item"><i class="fas fa-heart"></i><span>Made with Love</span></div>
        </div>
        <a href="about.php" class="btn-primary" style="display:inline-block;margin-top:24px;">Read Our Story</a>
    </div>
</section>

<section class="why-us-section">
    <div class="section-header" data-aos="fade-up">
        <h2 class="section-title">Why Choose <span style="color:var(--primary-gold)">Jenny's</span></h2>
        <p class="section-subtitle">Everything you need for a beautiful, confident you.</p>
    </div>
    <div class="why-us-grid">
        <div class="why-card" data-aos="fade-up" data-aos-delay="0">
            <div class="why-icon"><i class="fas fa-medal"></i></div>
            <h4>Premium Quality</h4>
            <p>Every product passes our strict quality check before reaching your hands.</p>
        </div>
        <div class="why-card" data-aos="fade-up" data-aos-delay="80">
            <div class="why-icon"><i class="fas fa-tags"></i></div>
            <h4>Best Prices</h4>
            <p>Luxury looks at affordable prices. No compromises on quality.</p>
        </div>
        <div class="why-card" data-aos="fade-up" data-aos-delay="160">
            <div class="why-icon"><i class="fas fa-shipping-fast"></i></div>
            <h4>Fast Shipping</h4>
            <p>Orders delivered across Pakistan within 2-5 business days.</p>
        </div>
        <div class="why-card" data-aos="fade-up" data-aos-delay="240">
            <div class="why-icon"><i class="fas fa-headset"></i></div>
            <h4>24/7 Support</h4>
            <p>Our customer care team is always here to help you.</p>
        </div>
        <div class="why-card" data-aos="fade-up" data-aos-delay="320">
            <div class="why-icon"><i class="fas fa-shield-alt"></i></div>
            <h4>Secure Payments</h4>
            <p>Your transactions are always safe with our encrypted payment gateway.</p>
        </div>
        <div class="why-card" data-aos="fade-up" data-aos-delay="400">
            <div class="why-icon"><i class="fas fa-undo"></i></div>
            <h4>Easy Returns</h4>
            <p>Not satisfied? Return within 7 days for a full refund or exchange.</p>
        </div>
    </div>
</section>

<section class="featured-products-section">
    <div class="section-header" data-aos="fade-up">
        <h2 class="section-title">Featured <span style="color:var(--primary-gold)">Products</span></h2>
        <a href="products.php" class="view-all-btn">View All <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="featured-products-grid">
        <?php
        $featured = [];
        if (isset($pdo) && $pdo !== null) {
            try {
                $featured = $pdo->query("SELECT * FROM products WHERE is_featured=1 LIMIT 8")->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                $featured = [];
            }
        }
        
        if (!empty($featured)):
            foreach ($featured as $p):
        ?>
        <div class="fp-card" data-aos="fade-up">
            <?php if (!empty($p['discount_percent']) && $p['discount_percent'] > 0): ?><span class="fp-badge fp-off">-<?= $p['discount_percent'] ?>%</span><?php endif; ?>
            <div class="fp-img-wrap">
                <img src="img/<?= htmlspecialchars($p['image'] ?? 'foundation.jpg') ?>" onerror="this.src='img/foundation.jpg'" alt="<?= htmlspecialchars($p['name'] ?? 'Product') ?>" loading="lazy">
                <div class="fp-overlay">
                    <button class="fp-quick-add" onclick="openProductPopup('<?= htmlspecialchars(addslashes($p['name'])) ?>', <?= $p['price'] ?>, 'img/<?= htmlspecialchars($p['image']) ?>', '<?= htmlspecialchars(addslashes($p['description'] ?? 'Premium quality cosmetic & jewelry item by Jenny\'s.')) ?>')" style="background:var(--white);color:var(--dark-black);margin-bottom:6px;">
                        <i class="fas fa-eye"></i> Quick Preview
                    </button>
                    <button class="fp-quick-add" onclick="addToCart('<?= htmlspecialchars(addslashes($p['name'])) ?>', <?= $p['price'] ?>, 'img/<?= htmlspecialchars($p['image']) ?>', '<?= $p['id'] ?>')">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                </div>
            </div>
            <div class="fp-info">
                <div class="fp-cat"><?= htmlspecialchars($p['category'] ?? 'Cosmetics') ?></div>
                <div class="fp-name" onclick="openProductPopup('<?= htmlspecialchars(addslashes($p['name'])) ?>', <?= $p['price'] ?>, 'img/<?= htmlspecialchars($p['image']) ?>', '<?= htmlspecialchars(addslashes($p['description'] ?? 'Premium quality cosmetic & jewelry item by Jenny\'s.')) ?>')" style="cursor:pointer;"><?= htmlspecialchars($p['name']) ?></div>
                <div class="fp-stars">
                    <?php for ($s = 1; $s <= 5; $s++): ?>
                    <i class="fa<?= $s <= round($p['rating'] ?? 5) ? 's' : 'r' ?> fa-star"></i>
                    <?php endfor; ?>
                    <span>(<?= $p['review_count'] ?? 0 ?>)</span>
                </div>
                <div class="fp-price">
                    <span class="fp-current">Rs.<?= number_format($p['price']) ?></span>
                    <?php if (!empty($p['old_price'])): ?><span class="fp-old">Rs.<?= number_format($p['old_price']) ?></span><?php endif; ?>
                </div>
            </div>
        </div>
        <?php
            endforeach;
        else:
            // Static fallback cards if DB is initializing
            $fallbackProducts = [
                ['id'=>1, 'name'=>'Velvet Matte Lipstick', 'category'=>'Cosmetics', 'price'=>699, 'old_price'=>999, 'image'=>'Lip stick.jpg', 'rating'=>5, 'description'=>'Long-lasting velvet matte lipstick with rich pigmentation.'],
                ['id'=>2, 'name'=>'HD Foundation SPF 30', 'category'=>'Cosmetics', 'price'=>1299, 'old_price'=>1799, 'image'=>'foundation.jpg', 'rating'=>5, 'description'=>'Lightweight HD foundation with SPF 30 for flawless full coverage all day.'],
                ['id'=>3, 'name'=>'Vintage Gold Necklace Set', 'category'=>'Jewelry', 'price'=>1499, 'old_price'=>2199, 'image'=>'necklace.jpg', 'rating'=>5, 'description'=>'Elegant vintage-inspired gold-plated necklace with matching earrings.'],
                ['id'=>4, 'name'=>'Statement Chandelier Earrings', 'category'=>'Jewelry', 'price'=>899, 'old_price'=>1299, 'image'=>'earing.jpg', 'rating'=>5, 'description'=>'Stunning gold chandelier earrings with intricate filigree work.'],
            ];
            foreach ($fallbackProducts as $p):
        ?>
        <div class="fp-card" data-aos="fade-up">
            <span class="fp-badge fp-best">Best</span>
            <div class="fp-img-wrap">
                <img src="img/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy">
                <div class="fp-overlay">
                    <button class="fp-quick-add" onclick="openProductPopup('<?= htmlspecialchars(addslashes($p['name'])) ?>', <?= $p['price'] ?>, 'img/<?= htmlspecialchars($p['image']) ?>', '<?= htmlspecialchars(addslashes($p['description'])) ?>')" style="background:var(--white);color:var(--dark-black);margin-bottom:6px;">
                        <i class="fas fa-eye"></i> Quick Preview
                    </button>
                    <button class="fp-quick-add" onclick="addToCart('<?= htmlspecialchars(addslashes($p['name'])) ?>', <?= $p['price'] ?>, 'img/<?= htmlspecialchars($p['image']) ?>', '<?= $p['id'] ?>')">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                </div>
            </div>
            <div class="fp-info">
                <div class="fp-cat"><?= htmlspecialchars($p['category']) ?></div>
                <div class="fp-name" onclick="openProductPopup('<?= htmlspecialchars(addslashes($p['name'])) ?>', <?= $p['price'] ?>, 'img/<?= htmlspecialchars($p['image']) ?>', '<?= htmlspecialchars(addslashes($p['description'])) ?>')" style="cursor:pointer;"><?= htmlspecialchars($p['name']) ?></div>
                <div class="fp-stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i> <span>(12)</span></div>
                <div class="fp-price">
                    <span class="fp-current">Rs.<?= number_format($p['price']) ?></span>
                    <span class="fp-old">Rs.<?= number_format($p['old_price']) ?></span>
                </div>
            </div>
        </div>
        <?php endforeach; endif; ?>
    </div>
</section>

<style>
.promo-banner {
    background: linear-gradient(135deg, #0F0F0F 0%, #1A1A1A 50%, #121212 100%) !important;
    padding: 70px 5% !important;
    text-align: center !important;
    border-top: 2px solid #F4B400 !important;
    border-bottom: 2px solid #F4B400 !important;
    margin: 60px 0 !important;
    position: relative !important;
    overflow: hidden !important;
}
.promo-banner::before {
    content: '' !important;
    position: absolute !important;
    top: 50% !important; left: 50% !important;
    transform: translate(-50%, -50%) !important;
    width: 600px !important; height: 300px !important;
    background: radial-gradient(ellipse at center, rgba(244, 180, 0, 0.12) 0%, transparent 70%) !important;
    pointer-events: none !important;
}
.promo-content {
    max-width: 900px !important;
    margin: 0 auto !important;
    position: relative !important;
    z-index: 2 !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    gap: 28px !important;
}
.promo-text h2 {
    font-family: 'Playfair Display', serif !important;
    font-size: clamp(2rem, 4.5vw, 3.2rem) !important;
    color: #FFFFFF !important;
    line-height: 1.2 !important;
    margin-bottom: 10px !important;
}
.promo-text h2 span {
    color: #F4B400 !important;
    background: linear-gradient(135deg, #F4B400 0%, #FFD740 100%) !important;
    -webkit-background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
}
.promo-text p {
    color: rgba(255, 255, 255, 0.8) !important;
    font-size: 1.1rem !important;
}
.promo-text p strong {
    color: #F4B400 !important;
    font-weight: 700 !important;
    letter-spacing: 1px !important;
}
.promo-action {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 18px !important;
    flex-wrap: wrap !important;
}

/* Luxury Promo Code Box */
.promo-code-wrapper {
    display: inline-flex !important;
    align-items: center !important;
    background: rgba(255, 255, 255, 0.07) !important;
    border: 2px dashed #F4B400 !important;
    border-radius: 50px !important;
    padding: 6px 8px 6px 22px !important;
    gap: 12px !important;
    backdrop-filter: blur(10px) !important;
    cursor: pointer !important;
    transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
    box-shadow: 0 8px 25px rgba(0,0,0,0.4), 0 0 15px rgba(244,180,0,0.15) !important;
}
.promo-code-wrapper:hover {
    background: rgba(244, 180, 0, 0.14) !important;
    border-color: #FFD740 !important;
    box-shadow: 0 12px 35px rgba(244, 180, 0, 0.35) !important;
    transform: translateY(-3px) !important;
}
.promo-icon {
    color: #F4B400 !important;
    font-size: 1.25rem !important;
}
.promo-code-input {
    background: transparent !important;
    border: none !important;
    outline: none !important;
    color: #FFFFFF !important;
    font-family: 'Poppins', sans-serif !important;
    font-size: 1.2rem !important;
    font-weight: 700 !important;
    letter-spacing: 2.5px !important;
    width: 110px !important;
    text-align: center !important;
    cursor: pointer !important;
    user-select: all !important;
    padding: 0 !important;
    margin: 0 !important;
}
.promo-copy-btn {
    background: linear-gradient(135deg, #F4B400 0%, #D19C00 100%) !important;
    color: #0F0F0F !important;
    border: none !important;
    border-radius: 30px !important;
    padding: 10px 24px !important;
    font-weight: 700 !important;
    font-size: 0.85rem !important;
    font-family: 'Poppins', sans-serif !important;
    cursor: pointer !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 8px !important;
    transition: all 0.25s ease !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    box-shadow: 0 4px 15px rgba(244, 180, 0, 0.3) !important;
}
.promo-code-wrapper:hover .promo-copy-btn {
    background: linear-gradient(135deg, #FFD740 0%, #F4B400 100%) !important;
    box-shadow: 0 6px 20px rgba(244, 180, 0, 0.5) !important;
}
.promo-shop-btn {
    padding: 14px 38px !important;
    border-radius: 50px !important;
    font-size: 0.95rem !important;
    font-weight: 700 !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 8px !important;
    background: linear-gradient(135deg, #F4B400 0%, #D19C00 100%) !important;
    color: #0F0F0F !important;
    border: none !important;
    box-shadow: 0 8px 25px rgba(244, 180, 0, 0.35) !important;
    transition: all 0.3s ease !important;
    cursor: pointer !important;
    font-family: 'Poppins', sans-serif !important;
}
.promo-shop-btn:hover {
    transform: translateY(-3px) scale(1.02) !important;
    box-shadow: 0 14px 35px rgba(244, 180, 0, 0.5) !important;
    background: linear-gradient(135deg, #FFD740 0%, #F4B400 100%) !important;
}

@media (max-width: 768px) {
    .promo-banner { padding: 48px 4% !important; margin: 40px 0 !important; }
    .promo-action { flex-direction: column !important; width: 100% !important; }
    .promo-code-wrapper { width: 100% !important; max-width: 340px !important; justify-content: space-between !important; padding: 6px 8px 6px 16px !important; }
    .promo-shop-btn { width: 100% !important; max-width: 340px !important; justify-content: center !important; }
}
@media (max-width: 480px) {
    .promo-code-wrapper { padding: 5px 6px 5px 14px !important; }
    .promo-code-input { font-size: 1rem !important; width: 90px !important; letter-spacing: 1.5px !important; }
    .promo-copy-btn { padding: 8px 18px !important; font-size: 0.78rem !important; }
}
</style>

<section class="promo-banner" data-aos="fade-up">
    <div class="promo-content">
        <div class="promo-text">
            <h2>Get <span>10% OFF</span> Your First Order</h2>
            <p>Use code <strong>JENNY10</strong> at checkout. Limited time offer!</p>
        </div>
        <div class="promo-action">
            <div class="promo-code-wrapper" onclick="copyPromoCode()" title="Click to copy promo code">
                <i class="fas fa-ticket-alt promo-icon"></i>
                <input type="text" value="JENNY10" readonly class="promo-code-input" id="promoCodeInput">
                <button type="button" class="promo-copy-btn" id="promoCopyBtn">
                    <i class="far fa-copy" id="copyBtnIcon"></i> <span id="copyBtnText">Copy</span>
                </button>
            </div>
            <button class="btn-primary promo-shop-btn" onclick="location.href='products.php'">Shop Now <i class="fas fa-arrow-right"></i></button>
        </div>
    </div>
</section>

<section class="testimonials-section">
    <div class="section-header" data-aos="fade-up">
        <h2 class="section-title">What Our <span style="color:var(--primary-gold)">Clients</span> Say</h2>
        <p class="section-subtitle">Real reviews from real people who love Jenny's Cosmetics &amp; Jewelry.</p>
    </div>

    <div class="testimonial-track-wrapper" data-aos="fade-up">
        <div class="testimonial-track" id="testiTrack">
            <?php
            $testis = [];
            if (isset($pdo) && $pdo !== null) {
                try {
                    $testis = $pdo->query("SELECT * FROM testimonials WHERE is_visible=1 ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
                } catch (Exception $e) { $testis = []; }
            }
            if (!empty($testis)):
                foreach ($testis as $t):
            ?>
            <div class="testimonial-card">
                <div class="testi-quote"><i class="fas fa-quote-left"></i></div>
                <p>"<?= htmlspecialchars($t['review']) ?>"</p>
                <div class="stars">
                    <?php for ($s = 0; $s < ($t['rating'] ?? 5); $s++): ?><i class="fas fa-star"></i><?php endfor; ?>
                </div>
                <div class="testi-author">
                    <?php if (!empty($t['client_img'])): ?>
                    <img src="img/<?= htmlspecialchars($t['client_img']) ?>" onerror="this.style.display='none'" alt="" class="client-avatar">
                    <?php endif; ?>
                    <div class="client-avatar-placeholder" style="<?= !empty($t['client_img']) ? 'display:none' : '' ?>">
                        <?= strtoupper(substr($t['client_name'],0,1)) ?>
                    </div>
                    <div>
                        <div class="client-name"><?= htmlspecialchars($t['client_name']) ?></div>
                        <div class="client-title"><?= htmlspecialchars($t['client_title'] ?? 'Verified Customer') ?></div>
                    </div>
                </div>
            </div>
            <?php
                endforeach;
            else:
            ?>
            <div class="testimonial-card">
                <div class="testi-quote"><i class="fas fa-quote-left"></i></div>
                <p>"Jenny's quality is amazing! The lipsticks are so pigmented and the jewelry looks exactly like real gold."</p>
                <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                <div class="testi-author">
                    <div class="client-avatar-placeholder">L</div>
                    <div><div class="client-name">Lina</div><div class="client-title">Verified Customer</div></div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testi-quote"><i class="fas fa-quote-left"></i></div>
                <p>"Ordered a necklace and earrings set for my sister's wedding. Everyone thought it was real gold!"</p>
                <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                <div class="testi-author">
                    <div class="client-avatar-placeholder">O</div>
                    <div><div class="client-name">Olga</div><div class="client-title">Verified Customer</div></div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testi-quote"><i class="fas fa-quote-left"></i></div>
                <p>"The skincare products are gentle and effective. My skin feels soft and glowing!"</p>
                <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                <div class="testi-author">
                    <div class="client-avatar-placeholder">D</div>
                    <div><div class="client-name">Danil</div><div class="client-title">Verified Customer</div></div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Testi Dots -->
    <div class="testi-dots" id="testiDots"></div>
</section>

<section class="insta-grid-section" data-aos="fade-up">
    <div class="section-header">
        <h2 class="section-title">Our <span style="color:var(--primary-gold)">Collection</span></h2>
        <p class="section-subtitle">Follow us @JennysCosmetics for daily beauty inspiration</p>
    </div>
    <div class="insta-grid">
        <div class="insta-item" data-aos="zoom-in" data-aos-delay="0"><img src="img/Eye Shadow.jpg" alt="Jenny's Eye Shadow Palette" loading="lazy"><div class="insta-hover"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item" data-aos="zoom-in" data-aos-delay="50"><img src="img/necklace2.jpg" alt="Jenny's Gold Necklace Set" loading="lazy"><div class="insta-hover"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item" data-aos="zoom-in" data-aos-delay="100"><img src="img/Lip stick.jpg" alt="Jenny's Velvet Lipstick" loading="lazy"><div class="insta-hover"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item" data-aos="zoom-in" data-aos-delay="150"><img src="img/earing.jpg" alt="Jenny's Statement Earrings" loading="lazy"><div class="insta-hover"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item" data-aos="zoom-in" data-aos-delay="200"><img src="img/foundation2.jpg" alt="Jenny's HD Foundation" loading="lazy"><div class="insta-hover"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item" data-aos="zoom-in" data-aos-delay="250"><img src="img/bracelet4.jpg" alt="Jenny's Charm Bracelet" loading="lazy"><div class="insta-hover"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item" data-aos="zoom-in" data-aos-delay="300"><img src="img/ring3.jpg" alt="Jenny's Cocktail Ring" loading="lazy"><div class="insta-hover"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item" data-aos="zoom-in" data-aos-delay="350"><img src="img/Blush.jpg" alt="Jenny's Radiant Blush" loading="lazy"><div class="insta-hover"><i class="fab fa-instagram"></i></div></div>
    </div>
</section>

<?php require 'includes/popup.php'; ?>

<!-- CART SIDEBAR -->
<div class="cart-overlay" id="cartOverlay" onclick="closeCart()"></div>
<div class="cart-sidebar" id="cartSidebar">
    <div class="cart-header">
        <h2>Your Cart</h2>
        <span class="cart-close" onclick="closeCart()"><i class="fas fa-times"></i></span>
    </div>
    <div class="cart-items-container" id="cartItemsContainer">
        <div class="empty-cart-msg" id="emptyCartMsg">
            <i class="fas fa-shopping-bag" style="font-size:3rem;color:#ddd;margin-bottom:10px;display:block;"></i>
            Your cart is empty.
        </div>
    </div>
    <div class="cart-footer">
        <div class="cart-total">
            <span>Total:</span>
            <span id="cartTotalPrice">Rs. 0</span>
        </div>
        <button class="checkout-btn" onclick="location.href='checkout.php'">Proceed to Checkout</button>
    </div>
</div>

<!-- Footer -->
<?php require 'includes/footer.php'; ?>

<!-- GSAP -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<!-- Three.js Particle BG -->
<script src="includes/js/threejs-bg.js"></script>
<!-- Custom JS loaded by footer.php -->

<script>
// ============================================================
// HERO CAROUSEL
// ============================================================
(function() {
    let current = 0;
    let autoTimer;
    let isAnimating = false;

    const slides   = document.querySelectorAll('.carousel-slide');
    const dots     = document.querySelectorAll('.dot');
    const progress = document.getElementById('progressBar');
    const total    = slides.length;

    if (!slides || total === 0) return;

    // Hide navigation when only 1 slide | nothing to cycle through
    if (total <= 1) {
        const nextBtn = document.getElementById('carouselNext');
        const prevBtn = document.getElementById('carouselPrev');
        const dotsEl  = document.getElementById('carouselDots');
        if (nextBtn) nextBtn.style.display = 'none';
        if (prevBtn) prevBtn.style.display = 'none';
        if (dotsEl)  dotsEl.style.display  = 'none';

        // Still run the entry animation for the single slide
        if (slides[0] && slides[0].querySelector('.slide-content') && typeof gsap !== 'undefined') {
            gsap.fromTo(slides[0].querySelector('.slide-content'),
                { y: 40, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, delay: 0.3, ease: 'power2.out' });
        }
        resetProgress();
        return; // No cycling needed
    }

    function goTo(n, dir) {
        if (isAnimating || n === current) return;
        isAnimating = true;

        // Safety: release lock after 1.2s in case GSAP onComplete never fires
        const animLock = setTimeout(() => { isAnimating = false; }, 1200);

        const prevSlide = slides[current];
        const nextSlide = slides[n];
        const direction = dir || (n > current ? 1 : -1);

        if (typeof gsap !== 'undefined') {
            // Animate OUT current
            gsap.to(prevSlide.querySelector('.slide-content'), {
                x: direction * -60, opacity: 0, duration: 0.4, ease: 'power2.in',
                onComplete: () => { prevSlide.classList.remove('active'); }
            });
            gsap.to(prevSlide.querySelector('.slide-bg'), {
                scale: 1.05, duration: 0.8, ease: 'power2.inOut'
            });

            // Animate IN next
            nextSlide.classList.add('active');
            gsap.fromTo(nextSlide.querySelector('.slide-bg'),
                { scale: 1.08 }, { scale: 1, duration: 0.9, ease: 'power2.out' });
            gsap.fromTo(nextSlide.querySelector('.slide-content'),
                { x: direction * 80, opacity: 0 },
                { x: 0, opacity: 1, duration: 0.6, delay: 0.15, ease: 'power2.out',
                  onComplete: () => { clearTimeout(animLock); isAnimating = false; }
                }
            );

            // Stagger content elements
            nextSlide.querySelectorAll('[data-anim]').forEach((el, i) => {
                gsap.fromTo(el, { y: 30, opacity: 0 }, {
                    y: 0, opacity: 1, duration: 0.5, delay: 0.2 + i * 0.1, ease: 'power2.out'
                });
            });
        } else {
            // No GSAP fallback
            prevSlide.classList.remove('active');
            nextSlide.classList.add('active');
            clearTimeout(animLock);
            isAnimating = false;
        }

        // Update dots
        if (dots[current]) dots[current].classList.remove('active');
        if (dots[n]) dots[n].classList.add('active');
        current = n;
        resetProgress();
    }

    function next() { goTo((current + 1) % total, 1); }
    function prev() { goTo((current - 1 + total) % total, -1); }

    function resetProgress() {
        if (progress) {
            progress.style.transition = 'none';
            progress.style.width = '0%';
            setTimeout(() => {
                progress.style.transition = 'width 5s linear';
                progress.style.width = '100%';
            }, 50);
        }
    }

    function startAuto() {
        clearInterval(autoTimer);
        autoTimer = setInterval(next, 5000);
    }

    // Bind controls
    const nextBtn = document.getElementById('carouselNext');
    const prevBtn = document.getElementById('carouselPrev');
    if (nextBtn) nextBtn.addEventListener('click', () => { next(); startAuto(); });
    if (prevBtn) prevBtn.addEventListener('click', () => { prev(); startAuto(); });
    dots.forEach(d => d.addEventListener('click', () => { goTo(parseInt(d.dataset.dot)); startAuto(); }));

    // Touch swipe support for hero carousel
    const heroElem = document.getElementById('heroCarousel');
    if (heroElem) {
        let touchStartX = 0;
        let touchEndX = 0;
        heroElem.addEventListener('touchstart', (e) => {
            touchStartX = e.touches[0].clientX;
        }, { passive: true });
        heroElem.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].clientX;
            const diff = touchStartX - touchEndX;
            if (Math.abs(diff) > 40) {
                if (diff > 0) { next(); } else { prev(); }
                startAuto();
            }
        }, { passive: true });
    }

    // Initial animate in
    if (slides[0] && slides[0].querySelector('.slide-content') && typeof gsap !== 'undefined') {
        gsap.fromTo(slides[0].querySelector('.slide-content'),
            { y: 40, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, delay: 0.3, ease: 'power2.out' });
    }

    startAuto();
    resetProgress();
})();

// ============================================================
// STATS COUNTER
// ============================================================
if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
    gsap.registerPlugin(ScrollTrigger);

    document.querySelectorAll('.stat-number').forEach(el => {
        const target = parseInt(el.dataset.count);
        ScrollTrigger.create({
            trigger: el,
            start: 'top 85%',
            once: true,
            onEnter: () => {
                gsap.to({ val: 0 }, {
                    val: target, duration: 2.5, ease: 'power2.out',
                    onUpdate: function() { el.textContent = Math.floor(this.targets()[0].val).toLocaleString(); }
                });
            }
        });
    });
}

// ============================================================
// TESTIMONIAL SLIDER WITH TOUCH SWIPE & MOBILE RESPONSIVENESS
// ============================================================
(function() {
    const track = document.getElementById('testiTrack');
    if (!track) return;
    const cards = track.querySelectorAll('.testimonial-card');
    const dotsEl = document.getElementById('testiDots');
    let perView = window.innerWidth <= 768 ? 1 : window.innerWidth <= 1024 ? 2 : 3;
    let curr = 0;
    let autoTimer = null;

    function getPerView() {
        return window.innerWidth <= 768 ? 1 : window.innerWidth <= 1024 ? 2 : 3;
    }

    function buildDots() {
        perView = getPerView();
        const totalDots = Math.ceil(cards.length / perView);
        if (dotsEl) {
            dotsEl.innerHTML = '';
            if (cards.length > perView) {
                for (let i = 0; i < totalDots; i++) {
                    const d = document.createElement('button');
                    d.className = 'testi-dot' + (i === curr ? ' active' : '');
                    d.setAttribute('aria-label', `Slide ${i + 1}`);
                    d.onclick = () => slideTo(i);
                    dotsEl.appendChild(d);
                }
            }
        }
    }

    function slideTo(n) {
        perView = getPerView();
        const totalDots = Math.ceil(cards.length / perView);
        if (totalDots === 0) return;
        curr = (n + totalDots) % totalDots;

        const offset = -curr * (100 / perView) * perView;
        if (typeof gsap !== 'undefined') {
            gsap.to(track, { x: offset + '%', duration: 0.5, ease: 'power2.out' });
        } else {
            track.style.transform = `translateX(${offset}%)`;
        }
        document.querySelectorAll('.testi-dot').forEach((d, i) => d.classList.toggle('active', i === curr));
    }

    function startAuto() {
        stopAuto();
        autoTimer = setInterval(() => {
            const totalDots = Math.ceil(cards.length / getPerView());
            if (totalDots > 1) {
                slideTo((curr + 1) % totalDots);
            }
        }, 5500);
    }

    function stopAuto() {
        if (autoTimer) clearInterval(autoTimer);
    }

    // TOUCH & SWIPE SUPPORT FOR MOBILE
    let startX = 0;
    let currentX = 0;
    let isDragging = false;

    track.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
        isDragging = true;
        stopAuto();
    }, { passive: true });

    track.addEventListener('touchmove', (e) => {
        if (!isDragging) return;
        currentX = e.touches[0].clientX;
    }, { passive: true });

    track.addEventListener('touchend', () => {
        if (!isDragging) return;
        isDragging = false;
        const diffX = startX - currentX;
        const threshold = 40;
        if (Math.abs(diffX) > threshold) {
            if (diffX > 0) {
                slideTo(curr + 1);
            } else {
                slideTo(curr - 1);
            }
        }
        startAuto();
    });

    window.addEventListener('resize', () => {
        buildDots();
        slideTo(curr);
    });

    buildDots();
    startAuto();
})();

// ============================================================
// PROMO CODE COPY WITH ANIMATED FEEDBACK
// ============================================================
function copyPromoCode() {
    const input = document.getElementById('promoCodeInput');
    const textEl = document.getElementById('copyBtnText');
    const iconEl = document.getElementById('copyBtnIcon');
    if (!input) return;

    const code = input.value;
    const onSuccess = () => {
        if (textEl) textEl.textContent = 'Copied!';
        if (iconEl) iconEl.className = 'fas fa-check';
        if (typeof showNotification === 'function') {
            showNotification(`Promo code "${code}" copied to clipboard!`);
        }
        setTimeout(() => {
            if (textEl) textEl.textContent = 'Copy';
            if (iconEl) iconEl.className = 'far fa-copy';
        }, 2000);
    };

    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(code).then(onSuccess).catch(() => {
            input.select();
            document.execCommand('copy');
            onSuccess();
        });
    } else {
        input.select();
        document.execCommand('copy');
        onSuccess();
    }
}
</script>
</body>
</html>