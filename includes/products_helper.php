<?php
// includes/products_helper.php | Dynamic Product Utilities

if (!function_exists('getAllProducts')) {
    function getAllProducts($category = null, $type = null) {
        global $pdo;
        if (!isset($pdo) || !$pdo) return [];

        try {
            $sql = "SELECT * FROM products WHERE is_active = 1";
            $params = [];

            if ($category) {
                $sql .= " AND (category = ? OR sub_category = ?)";
                $params[] = $category;
                $params[] = $category;
            }

            if ($type === 'bestsellers') {
                $sql .= " AND is_bestseller = 1";
            } elseif ($type === 'new') {
                $sql .= " AND is_new = 1";
            } elseif ($type === 'offers') {
                $sql .= " AND (discount_percent > 0 OR old_price > price)";
            }

            $sql .= " ORDER BY id DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
}

if (!function_exists('renderSingleProductCard')) {
    function renderSingleProductCard($p) {
        $id = 'prod_' . $p['id'];
        $name = htmlspecialchars($p['name']);
        $cat = htmlspecialchars($p['category']);
        $subcat = htmlspecialchars($p['sub_category'] ?? '');
        $img = htmlspecialchars($p['image']);
        $desc = htmlspecialchars($p['description'] ?? '');
        $price = (float)$p['price'];
        $oldPrice = (float)($p['old_price'] ?? 0);
        $discount = (int)($p['discount_percent'] ?? 0);
        $rating = (float)($p['rating'] ?? 4.8);
        $reviews = (int)($p['review_count'] ?? 50);
        $stock = (int)($p['stock'] ?? 100);
        $imgPath = './img/' . $img;

        ob_start();
        ?>
        <div class="category-card visible" data-name="<?= $cat ?> <?= $subcat ?> <?= $name ?>" data-price="<?= $price ?>" data-rating="<?= $rating ?>" data-id="<?= $id ?>">
            <div class="card-img-wrapper" style="position:relative; overflow:hidden;">
                <img src="<?= $imgPath ?>" alt="<?= $name ?>" loading="lazy" onclick="openProductPopup(<?= htmlspecialchars(json_encode($name), ENT_QUOTES, 'UTF-8') ?>, <?= $price ?>, <?= htmlspecialchars(json_encode($imgPath), ENT_QUOTES, 'UTF-8') ?>, <?= htmlspecialchars(json_encode($desc), ENT_QUOTES, 'UTF-8') ?>)" style="cursor:pointer; width:100%; height:100%; object-fit:cover; transition:transform 0.5s ease;">
                
                <!-- BADGES (Sleek single discount tag if applicable) -->
                <?php if ($discount > 0): ?>
                <div class="card-badges" style="position:absolute; top:10px; left:10px; z-index:2; pointer-events:none;">
                    <span style="background:rgba(0,0,0,0.75); color:var(--primary-gold); font-size:0.65rem; font-weight:700; padding:4px 10px; border-radius:20px; border:1px solid var(--primary-gold); letter-spacing:0.5px; text-transform:uppercase;">-<?= $discount ?>%</span>
                </div>
                <?php endif; ?>

                <!-- QUICK ACTIONS OVERLAY -->
                <div class="card-quick-actions" style="position:absolute; top:10px; right:10px; display:flex; flex-direction:column; gap:6px; z-index:2;">
                    <button type="button" class="btn-wishlist" onclick="toggleWishlist(this, <?= htmlspecialchars(json_encode($name), ENT_QUOTES, 'UTF-8') ?>)" aria-label="Add to Wishlist" style="width:32px; height:32px; border-radius:50%; background:rgba(255,255,255,0.9); border:none; color:#e74c3c; cursor:pointer; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 8px rgba(0,0,0,0.15); transition:0.3s;">
                        <i class="far fa-heart"></i>
                    </button>
                    <button type="button" class="btn-quickview" onclick="openProductPopup(<?= htmlspecialchars(json_encode($name), ENT_QUOTES, 'UTF-8') ?>, <?= $price ?>, <?= htmlspecialchars(json_encode($imgPath), ENT_QUOTES, 'UTF-8') ?>, <?= htmlspecialchars(json_encode($desc), ENT_QUOTES, 'UTF-8') ?>)" aria-label="Quick View" style="width:32px; height:32px; border-radius:50%; background:rgba(255,255,255,0.9); border:none; color:var(--dark-black); cursor:pointer; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 8px rgba(0,0,0,0.15); transition:0.3s;">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- CARD BODY -->
            <div class="card-body-content" style="width:100%; text-align:left; margin-top:10px;">
                <div class="card-category-tag" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.5px; color:var(--primary-gold); font-weight:600; margin-bottom:4px;">
                    <?= $cat ?>
                </div>
                <h4 style="font-size:0.95rem; font-weight:600; color:var(--dark-black); margin-bottom:6px; line-height:1.3; height:2.6em; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;"><?= $name ?></h4>
                
                <!-- RATING & STOCK -->
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px; font-size:0.78rem;">
                    <div class="card-stars" style="color:#f39c12; display:flex; align-items:center; gap:3px;">
                        <i class="fas fa-star"></i>
                        <span style="font-weight:600; color:var(--dark-black);"><?= number_format($rating, 1) ?></span>
                        <span style="color:#888;">(<?= $reviews ?>)</span>
                    </div>
                    <?php if ($stock > 0): ?>
                        <span style="color:#27ae60; font-weight:500; font-size:0.72rem;"><i class="fas fa-check-circle"></i> In Stock</span>
                    <?php else: ?>
                        <span style="color:#e74c3c; font-weight:500; font-size:0.72rem;">Out of Stock</span>
                    <?php endif; ?>
                </div>

                <!-- PRICE -->
                <div class="card-price" style="font-size:1.1rem; font-weight:700; color:var(--dark-black); margin-bottom:12px;">
                    Rs. <?= number_format($price) ?>
                    <?php if ($oldPrice > $price): ?>
                        <span style="text-decoration:line-through; color:#999; font-size:0.85rem; margin-left:6px; font-weight:400;">Rs. <?= number_format($oldPrice) ?></span>
                    <?php endif; ?>
                </div>

                <!-- QTY STEPPER -->
                <div class="card-qty-wrapper" style="margin-bottom:12px;">
                    <div class="card-qty-box" style="display:inline-flex; border:1px solid #e0e0e0; border-radius:20px; overflow:hidden; background:#fdfdfd;">
                        <button type="button" onclick="changeCardQty(this, -1)" style="border:none; padding:4px 12px; background:none; cursor:pointer; font-weight:600;">-</button>
                        <span style="padding:4px 10px; font-size:0.85rem; font-weight:600; display:flex; align-items:center;">1</span>
                        <button type="button" onclick="changeCardQty(this, 1)" style="border:none; padding:4px 12px; background:none; cursor:pointer; font-weight:600;">+</button>
                    </div>
                </div>

                <!-- ACTIONS -->
                <div class="card-actions" style="display:flex; gap:8px;">
                    <button class="btn-add-cart" onclick="addToCartFromCard(this, <?= htmlspecialchars(json_encode($name), ENT_QUOTES, 'UTF-8') ?>, <?= $price ?>, <?= htmlspecialchars(json_encode($imgPath), ENT_QUOTES, 'UTF-8') ?>)" style="flex:1; padding:10px; border-radius:20px; font-weight:600; font-size:0.82rem; cursor:pointer;">Add to Cart</button>
                    <button class="btn-buy-now" onclick="buyNowFromCard(this, <?= htmlspecialchars(json_encode($name), ENT_QUOTES, 'UTF-8') ?>, <?= $price ?>, <?= htmlspecialchars(json_encode($imgPath), ENT_QUOTES, 'UTF-8') ?>)" style="flex:1; padding:10px; border-radius:20px; font-weight:600; font-size:0.82rem; cursor:pointer;">Buy Now</button>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}
?>
