<?php
// admin/products.php — Premium Full CRUD with DB backend
require_once 'includes/auth_check.php';
require_once '../config/db.php';

$msg = ''; $msgType = '';

// ── DELETE ──
if (isset($_POST['confirm_delete']) && is_numeric($_POST['confirm_delete'])) {
    try {
        $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([(int)$_POST['confirm_delete']]);
        $msg = 'Product deleted successfully.'; $msgType = 'success';
    } catch (PDOException $e) {
        $msg = 'Error deleting product.'; $msgType = 'error';
    }
}

// ── TOGGLE ACTIVE ──
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    try {
        $pdo->prepare("UPDATE products SET is_active = 1 - is_active WHERE id = ?")->execute([(int)$_GET['toggle']]);
        header("Location: products.php?msg=toggled&type=success"); exit();
    } catch (PDOException $e) {}
}

// ── ADD / EDIT (POST) ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['confirm_delete'])) {
    $id            = (int)($_POST['product_id'] ?? 0);
    $name          = trim($_POST['name'] ?? '');
    $category      = trim($_POST['category'] ?? '');
    $sub_category  = trim($_POST['sub_category'] ?? '');
    $brand         = trim($_POST['brand'] ?? 'Jenny Luxe');
    $price         = (float)($_POST['price'] ?? 0);
    $old_price     = (float)($_POST['old_price'] ?? 0);
    $description   = trim($_POST['description'] ?? '');
    $stock         = (int)($_POST['stock'] ?? 100);
    $rating        = min(5.0, max(0, (float)($_POST['rating'] ?? 4.5)));
    $is_featured   = isset($_POST['is_featured'])   ? 1 : 0;
    $is_new        = isset($_POST['is_new'])        ? 1 : 0;
    $is_bestseller = isset($_POST['is_bestseller']) ? 1 : 0;
    $is_active     = isset($_POST['is_active'])     ? 1 : 0;
    $tags          = trim($_POST['tags'] ?? '');
    $image         = trim($_POST['existing_image'] ?? '');

    if (empty($name) || empty($category) || $price <= 0) {
        $msg = 'Name, category and a valid price are required.'; $msgType = 'error';
    } else {
        // Image upload
        if (!empty($_FILES['image']['name'])) {
            $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','webp','gif'];
            if (in_array($ext, $allowed) && $_FILES['image']['error'] === 0) {
                $newName   = 'prod_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                $uploadDir = '../img/';
                if (!is_dir($uploadDir)) @mkdir($uploadDir, 0777, true);
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $newName)) {
                    $image = $newName;
                }
            }
        }

        $discount = ($old_price > 0 && $old_price > $price)
            ? round((($old_price - $price) / $old_price) * 100)
            : 0;

        try {
            if ($id > 0) {
                $pdo->prepare("UPDATE products SET
                    name=?,category=?,sub_category=?,brand=?,price=?,old_price=?,description=?,image=?,
                    stock=?,rating=?,is_featured=?,is_new=?,is_bestseller=?,is_active=?,
                    discount_percent=?,tags=? WHERE id=?")
                ->execute([$name,$category,$sub_category,$brand,$price,$old_price,$description,$image,
                           $stock,$rating,$is_featured,$is_new,$is_bestseller,$is_active,$discount,$tags,$id]);
                $msg = 'Product updated successfully!'; $msgType = 'success';
            } else {
                $pdo->prepare("INSERT INTO products
                    (name,category,sub_category,brand,price,old_price,description,image,
                     stock,rating,is_featured,is_new,is_bestseller,is_active,discount_percent,tags)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")
                ->execute([$name,$category,$sub_category,$brand,$price,$old_price,$description,$image,
                           $stock,$rating,$is_featured,$is_new,$is_bestseller,$is_active,$discount,$tags]);
                $msg = 'Product added to catalog!'; $msgType = 'success';
            }
        } catch (PDOException $e) {
            $msg = 'Database error: ' . $e->getMessage(); $msgType = 'error';
        }
    }
}

// ── URL message passthrough ──
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'] === 'toggled' ? 'Product visibility toggled.' : htmlspecialchars($_GET['msg']);
    $msgType = $_GET['type'] ?? 'info';
}

// ── FETCH + FILTER + SORT ──
$search     = trim($_GET['search']   ?? '');
$catFilter  = trim($_GET['cat']      ?? '');
$statusFilt = trim($_GET['status']   ?? '');
$sortBy     = in_array($_GET['sort'] ?? '', ['name','price_asc','price_desc','stock','rating','newest'])
              ? $_GET['sort'] : 'newest';
$page       = max(1, (int)($_GET['page'] ?? 1));
$perPage    = 15;
$offset     = ($page - 1) * $perPage;

$sortMap = [
    'newest'     => 'created_at DESC',
    'name'       => 'name ASC',
    'price_asc'  => 'price ASC',
    'price_desc' => 'price DESC',
    'stock'      => 'stock ASC',
    'rating'     => 'rating DESC',
];
$orderSQL = $sortMap[$sortBy];

try {
    $where  = "WHERE 1=1";
    $params = [];
    if ($search)     { $where .= " AND (name LIKE ? OR description LIKE ? OR tags LIKE ?)"; $params[] = "%$search%"; $params[] = "%$search%"; $params[] = "%$search%"; }
    if ($catFilter)  { $where .= " AND category = ?";  $params[] = $catFilter; }
    if ($statusFilt === 'active')   { $where .= " AND is_active = 1"; }
    if ($statusFilt === 'inactive') { $where .= " AND is_active = 0"; }
    if ($statusFilt === 'low')      { $where .= " AND stock <= 20 AND stock > 0"; }
    if ($statusFilt === 'oos')      { $where .= " AND stock = 0"; }

    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM products $where");
    $countStmt->execute($params);
    $totalCount = (int)$countStmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT * FROM products $where ORDER BY $orderSQL LIMIT $perPage OFFSET $offset");
    $stmt->execute($params);
    $products = $stmt->fetchAll();

    // Stats
    $stats = $pdo->query("SELECT
        COUNT(*) as total,
        SUM(is_active) as active,
        SUM(stock = 0) as out_of_stock,
        SUM(stock <= 20 AND stock > 0) as low_stock,
        SUM(is_featured) as featured,
        ROUND(AVG(price),0) as avg_price
        FROM products")->fetch();

    $editProduct = null;
    if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
        $ep = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $ep->execute([(int)$_GET['edit']]);
        $editProduct = $ep->fetch();
    }
} catch (PDOException $e) {
    $products = []; $totalCount = 0; $editProduct = null;
    $stats = ['total'=>0,'active'=>0,'out_of_stock'=>0,'low_stock'=>0,'featured'=>0,'avg_price'=>0];
}

$totalPages = max(1, ceil($totalCount / $perPage));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Products — Jenny's Admin Panel</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="css/admin.css">
<style>
/* ── PRODUCT PAGE EXTRAS ── */
.products-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px; margin-bottom: 24px; }
.pstat { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 16px 18px; display: flex; align-items: center; gap: 14px; cursor: pointer; transition: var(--transition); }
.pstat:hover { border-color: rgba(244,180,0,0.3); transform: translateY(-1px); }
.pstat.active-filter { border-color: var(--gold); background: rgba(244,180,0,0.06); }
.pstat-icon { width: 38px; height: 38px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.95rem; flex-shrink: 0; }
.pstat-num { font-size: 1.45rem; font-weight: 700; line-height: 1; color: var(--text-primary); }
.pstat-lbl { font-size: 0.7rem; color: var(--text-muted); font-weight: 500; margin-top: 2px; }

/* Table image + badges */
.prod-thumb-wrap { position: relative; flex-shrink: 0; }
.prod-thumb-wrap .badge-overlay {
    position: absolute; top: -4px; right: -4px;
    width: 16px; height: 16px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.55rem; font-weight: 700;
}
.status-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.status-dot.active { background: var(--green); }
.status-dot.inactive { background: var(--text-dim); }

/* Filter chips */
.filter-chips { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 16px; align-items: center; }
.chip { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 14px; font-size: 0.76rem; font-weight: 600; background: rgba(244,180,0,0.1); border: 1px solid rgba(244,180,0,0.25); color: var(--gold); }
.chip a { color: inherit; margin-left: 4px; opacity: 0.7; }
.chip a:hover { opacity: 1; }

/* Inline price display */
.price-col { font-weight: 700; color: var(--gold); font-size: 0.9rem; }
.price-old { font-size: 0.75rem; color: var(--text-dim); text-decoration: line-through; margin-top: 2px; }
.discount-tag { background: rgba(231,76,60,0.12); color: #ff7675; padding: 2px 6px; border-radius: 4px; font-size: 0.68rem; font-weight: 700; }

/* Stock indicator */
.stock-bar-wrap { width: 64px; background: var(--border); border-radius: 3px; height: 4px; margin-top: 4px; }
.stock-bar { height: 100%; border-radius: 3px; }

/* Delete confirm modal */
.delete-modal-text { font-size: 0.9rem; color: var(--text-muted); line-height: 1.7; margin-bottom: 24px; }
.delete-modal-text strong { color: var(--text-primary); }

/* Empty state */
.empty-products { text-align: center; padding: 80px 20px; }
.empty-products i { font-size: 3rem; color: var(--text-dim); display: block; margin-bottom: 16px; }
.empty-products h3 { font-family: 'Playfair Display', serif; font-size: 1.2rem; color: var(--text-primary); margin-bottom: 8px; }

/* Table row actions hidden until hover */
.action-btns .btn-action { opacity: 0.5; }
.admin-table tr:hover .btn-action { opacity: 1; }

/* Sub category badge */
.subcat-tag { font-size: 0.7rem; color: var(--text-dim); background: var(--bg-card2); border-radius: 4px; padding: 1px 6px; }
</style>
</head>
<body>
<?php include 'includes/sidebar.php'; ?>

<div class="admin-main">
    <!-- ── TOP BAR ── -->
    <div class="admin-topbar">
        <div style="display:flex; align-items:center; gap:14px;">
            <div class="topbar-title">Products</div>
            <span style="color:var(--text-dim); font-size:0.8rem;"><?= number_format($totalCount) ?> of <?= number_format($stats['total'] ?? 0) ?> results</span>
        </div>
        <div class="topbar-right">
            <a href="../products.php" target="_blank" class="topbar-btn" title="View Storefront"><i class="fas fa-external-link-alt"></i></a>
            <button class="btn-gold" onclick="openModal('addModal')">
                <i class="fas fa-plus"></i> Add Product
            </button>
        </div>
    </div>

    <div class="admin-content">

        <!-- ── TOAST (PHP) ── -->
        <?php if ($msg): ?>
        <div class="toast <?= $msgType ?>" style="position:relative;margin-bottom:16px;animation:none;display:flex;">
            <i class="fas <?= $msgType==='success' ? 'fa-circle-check' : ($msgType==='error' ? 'fa-circle-exclamation' : 'fa-info-circle') ?>"></i>
            <?= htmlspecialchars($msg) ?>
        </div>
        <?php endif; ?>

        <!-- ── QUICK STATS ── -->
        <div class="products-stats">
            <div class="pstat" onclick="filterByStatus('')" title="All products">
                <div class="pstat-icon" style="background:rgba(244,180,0,0.1);color:var(--gold);"><i class="fas fa-boxes-stacked"></i></div>
                <div><div class="pstat-num"><?= $stats['total'] ?></div><div class="pstat-lbl">Total Products</div></div>
            </div>
            <div class="pstat" onclick="filterByStatus('active')" title="Active products">
                <div class="pstat-icon" style="background:rgba(46,204,113,0.1);color:var(--green);"><i class="fas fa-eye"></i></div>
                <div><div class="pstat-num"><?= $stats['active'] ?></div><div class="pstat-lbl">Active / Visible</div></div>
            </div>
            <div class="pstat" onclick="filterByStatus('oos')" title="Out of stock">
                <div class="pstat-icon" style="background:rgba(231,76,60,0.1);color:var(--red);"><i class="fas fa-ban"></i></div>
                <div><div class="pstat-num"><?= $stats['out_of_stock'] ?></div><div class="pstat-lbl">Out of Stock</div></div>
            </div>
            <div class="pstat" onclick="filterByStatus('low')" title="Low stock">
                <div class="pstat-icon" style="background:rgba(243,156,18,0.1);color:var(--orange);"><i class="fas fa-triangle-exclamation"></i></div>
                <div><div class="pstat-num"><?= $stats['low_stock'] ?></div><div class="pstat-lbl">Low Stock (&le;20)</div></div>
            </div>
            <div class="pstat">
                <div class="pstat-icon" style="background:rgba(155,89,182,0.1);color:var(--purple);"><i class="fas fa-star"></i></div>
                <div><div class="pstat-num"><?= $stats['featured'] ?></div><div class="pstat-lbl">Featured</div></div>
            </div>
            <div class="pstat">
                <div class="pstat-icon" style="background:rgba(52,152,219,0.1);color:var(--blue);"><i class="fas fa-tag"></i></div>
                <div><div class="pstat-num">Rs.<?= number_format($stats['avg_price']) ?></div><div class="pstat-lbl">Avg. Price</div></div>
            </div>
        </div>

        <!-- ── FILTER TOOLBAR ── -->
        <div class="table-card">
            <div class="table-header">
                <div class="table-title" style="font-size:1rem;">
                    Product Catalog
                    <?php if ($search || $catFilter || $statusFilt): ?>
                    <span style="font-size:0.78rem;font-weight:400;color:var(--text-muted);font-family:'Poppins',sans-serif;margin-left:8px;">— Filtered</span>
                    <?php endif; ?>
                </div>
                <div class="table-actions">
                    <form method="GET" id="filterForm" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
                        <input type="hidden" name="status" id="statusFilterInput" value="<?= htmlspecialchars($statusFilt) ?>">
                        <div class="search-bar">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" placeholder="Search products..." value="<?= htmlspecialchars($search) ?>" oninput="debounceSearch(this)">
                        </div>
                        <select name="cat" class="form-control" style="width:140px;" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            <option value="Cosmetics" <?= $catFilter==='Cosmetics'?'selected':'' ?>>Cosmetics</option>
                            <option value="Jewelry"   <?= $catFilter==='Jewelry'?'selected':'' ?>>Jewelry</option>
                        </select>
                        <select name="sort" class="form-control" style="width:160px;" onchange="this.form.submit()">
                            <option value="newest"     <?= $sortBy==='newest'?'selected':'' ?>>Newest First</option>
                            <option value="name"       <?= $sortBy==='name'?'selected':'' ?>>Name A–Z</option>
                            <option value="price_asc"  <?= $sortBy==='price_asc'?'selected':'' ?>>Price: Low–High</option>
                            <option value="price_desc" <?= $sortBy==='price_desc'?'selected':'' ?>>Price: High–Low</option>
                            <option value="stock"      <?= $sortBy==='stock'?'selected':'' ?>>Stock: Low–High</option>
                            <option value="rating"     <?= $sortBy==='rating'?'selected':'' ?>>Top Rated</option>
                        </select>
                        <?php if ($search || $catFilter || $statusFilt): ?>
                        <a href="products.php" class="btn-outline-gold" style="padding:8px 14px;font-size:0.8rem;">
                            <i class="fas fa-times"></i> Clear
                        </a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <!-- ACTIVE CHIPS -->
            <?php if ($search || $catFilter || $statusFilt): ?>
            <div class="filter-chips" style="padding: 0 22px 8px;">
                <?php if ($search): ?><div class="chip"><i class="fas fa-search"></i> "<?= htmlspecialchars($search) ?>" <a href="?cat=<?= urlencode($catFilter) ?>&sort=<?= $sortBy ?>&status=<?= urlencode($statusFilt) ?>"><i class="fas fa-times"></i></a></div><?php endif; ?>
                <?php if ($catFilter): ?><div class="chip"><i class="fas fa-folder"></i> <?= htmlspecialchars($catFilter) ?> <a href="?search=<?= urlencode($search) ?>&sort=<?= $sortBy ?>&status=<?= urlencode($statusFilt) ?>"><i class="fas fa-times"></i></a></div><?php endif; ?>
                <?php if ($statusFilt): ?><div class="chip"><i class="fas fa-filter"></i> <?= ucfirst($statusFilt) ?> <a href="?search=<?= urlencode($search) ?>&cat=<?= urlencode($catFilter) ?>&sort=<?= $sortBy ?>"><i class="fas fa-times"></i></a></div><?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- TABLE -->
            <div style="overflow-x:auto;">
                <table class="admin-table">
                    <thead><tr>
                        <th style="width:300px;">Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Rating</th>
                        <th>Badges</th>
                        <th>Status</th>
                        <th style="text-align:right;">Actions</th>
                    </tr></thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                        <tr><td colspan="8">
                            <div class="empty-products">
                                <i class="fas fa-box-open"></i>
                                <h3>No products found</h3>
                                <p style="color:var(--text-muted);font-size:0.875rem;margin-bottom:20px;">
                                    <?= $search || $catFilter || $statusFilt ? 'Try adjusting your filters.' : 'Add your first product to get started.' ?>
                                </p>
                                <?php if (!$search && !$catFilter && !$statusFilt): ?>
                                <button class="btn-gold" onclick="openModal('addModal')"><i class="fas fa-plus"></i> Add Product</button>
                                <?php endif; ?>
                            </div>
                        </td></tr>
                        <?php else: foreach ($products as $p):
                            $stockColor = $p['stock'] === 0 ? 'var(--red)' : ($p['stock'] <= 20 ? 'var(--orange)' : 'var(--green)');
                            $stockWidth = min(100, ($p['stock'] / 200) * 100);
                        ?>
                        <tr>
                            <td>
                                <div class="product-img-cell">
                                    <div class="prod-thumb-wrap">
                                        <img src="../img/<?= htmlspecialchars($p['image'] ?? '') ?>"
                                             onerror="this.src='../img/foundation.jpg'"
                                             class="product-thumb" alt="">
                                    </div>
                                    <div>
                                        <div class="product-name"><?= htmlspecialchars($p['name']) ?></div>
                                        <div style="display:flex;gap:4px;margin-top:3px;flex-wrap:wrap;">
                                            <?php if (!empty($p['sub_category'])): ?>
                                            <span class="subcat-tag"><?= htmlspecialchars($p['sub_category']) ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($p['brand']) && $p['brand'] !== 'Jenny Luxe'): ?>
                                            <span class="subcat-tag" style="color:var(--gold);"><?= htmlspecialchars($p['brand']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-status badge-<?= strtolower($p['category']) ?>"><?= $p['category'] ?></span>
                            </td>
                            <td>
                                <div class="price-col">Rs.<?= number_format($p['price']) ?></div>
                                <?php if ($p['old_price'] > $p['price']): ?>
                                <div class="price-old">Rs.<?= number_format($p['old_price']) ?></div>
                                <?php endif; ?>
                                <?php if ($p['discount_percent'] > 0): ?>
                                <span class="discount-tag">-<?= $p['discount_percent'] ?>%</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="font-weight:600;color:<?= $stockColor ?>;"><?= $p['stock'] ?></div>
                                <div class="stock-bar-wrap">
                                    <div class="stock-bar" style="width:<?= $stockWidth ?>%;background:<?= $stockColor ?>;"></div>
                                </div>
                                <?php if ($p['stock'] === 0): ?>
                                <div style="font-size:0.68rem;color:var(--red);margin-top:2px;">Out of Stock</div>
                                <?php elseif ($p['stock'] <= 20): ?>
                                <div style="font-size:0.68rem;color:var(--orange);margin-top:2px;">Low Stock</div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span style="color:var(--gold);font-weight:600;">★ <?= number_format($p['rating'],1) ?></span>
                                <div style="font-size:0.72rem;color:var(--text-dim);"><?= $p['review_count'] ?? 0 ?> reviews</div>
                            </td>
                            <td>
                                <?php if ($p['is_featured']):  ?><span class="badge-status badge-jewelry" style="margin:2px 2px 2px 0;">Featured</span><?php endif; ?>
                                <?php if ($p['is_new']):       ?><span class="badge-status badge-processing" style="margin:2px 2px 2px 0;">New</span><?php endif; ?>
                                <?php if ($p['is_bestseller']): ?><span class="badge-status badge-delivered" style="margin:2px 2px 2px 0;">Best</span><?php endif; ?>
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <div class="status-dot <?= $p['is_active'] ? 'active' : 'inactive' ?>"></div>
                                    <span style="font-size:0.78rem;color:<?= $p['is_active'] ? 'var(--green)' : 'var(--text-dim)' ?>;">
                                        <?= $p['is_active'] ? 'Active' : 'Hidden' ?>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="action-btns" style="justify-content:flex-end;">
                                    <button class="btn-action btn-edit"
                                            onclick="editProduct(<?= htmlspecialchars(json_encode($p), ENT_QUOTES) ?>)"
                                            title="Edit product">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <a href="?toggle=<?= $p['id'] ?>&search=<?= urlencode($search) ?>&cat=<?= urlencode($catFilter) ?>&sort=<?= $sortBy ?>&status=<?= urlencode($statusFilt) ?>&page=<?= $page ?>"
                                       class="btn-action <?= $p['is_active'] ? 'btn-flag' : 'btn-approve' ?>"
                                       title="<?= $p['is_active'] ? 'Hide' : 'Show' ?> product">
                                        <i class="fas <?= $p['is_active'] ? 'fa-eye-slash' : 'fa-eye' ?>"></i>
                                    </a>
                                    <button class="btn-action btn-delete"
                                            onclick="confirmDelete(<?= $p['id'] ?>, <?= json_encode($p['name']) ?>)"
                                            title="Delete product">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <div class="pagination-info">Showing <?= $offset+1 ?>–<?= min($offset+$perPage, $totalCount) ?> of <?= $totalCount ?></div>
                <div class="pagination-buttons">
                    <?php if ($page > 1): ?>
                    <a href="?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>&cat=<?= urlencode($catFilter) ?>&sort=<?= $sortBy ?>&status=<?= urlencode($statusFilt) ?>" class="page-btn"><i class="fas fa-chevron-left"></i></a>
                    <?php endif; ?>
                    <?php
                    $start = max(1, $page - 2); $end = min($totalPages, $page + 2);
                    for ($i = $start; $i <= $end; $i++):
                    ?>
                    <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&cat=<?= urlencode($catFilter) ?>&sort=<?= $sortBy ?>&status=<?= urlencode($statusFilt) ?>" class="page-btn <?= $i==$page?'active':'' ?>"><?= $i ?></a>
                    <?php endfor; ?>
                    <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>&cat=<?= urlencode($catFilter) ?>&sort=<?= $sortBy ?>&status=<?= urlencode($statusFilt) ?>" class="page-btn"><i class="fas fa-chevron-right"></i></a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ════════════════════════════════════════
     ADD PRODUCT MODAL
════════════════════════════════════════ -->
<div class="modal-overlay" id="addModal">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title"><i class="fas fa-plus" style="color:var(--gold);margin-right:8px;"></i> Add New Product</div>
            <button class="modal-close" onclick="closeModal('addModal')" aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" enctype="multipart/form-data" id="addForm" novalidate>
            <input type="hidden" name="product_id" value="0">
            <div class="modal-body">
                <?= renderProductFormFields() ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline-gold" onclick="closeModal('addModal')">Cancel</button>
                <button type="submit" class="btn-gold" id="addSubmitBtn">
                    <i class="fas fa-save"></i> Save Product
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ════════════════════════════════════════
     EDIT PRODUCT MODAL
════════════════════════════════════════ -->
<div class="modal-overlay" id="editModal">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title"><i class="fas fa-pen" style="color:var(--gold);margin-right:8px;"></i> Edit Product</div>
            <button class="modal-close" onclick="closeModal('editModal')" aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" enctype="multipart/form-data" id="editForm" novalidate>
            <input type="hidden" name="product_id"    id="editId">
            <input type="hidden" name="existing_image" id="editExistingImg">
            <div class="modal-body">
                <?= renderProductFormFields('edit') ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline-gold" onclick="closeModal('editModal')">Cancel</button>
                <button type="submit" class="btn-gold">
                    <i class="fas fa-save"></i> Update Product
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ════════════════════════════════════════
     DELETE CONFIRM MODAL
════════════════════════════════════════ -->
<div class="modal-overlay modal-sm" id="deleteModal">
    <div class="modal-box modal-sm">
        <div class="modal-header">
            <div class="modal-title"><i class="fas fa-trash" style="color:var(--red);margin-right:8px;"></i> Confirm Delete</div>
            <button class="modal-close" onclick="closeModal('deleteModal')" aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" id="deleteForm">
            <input type="hidden" name="confirm_delete" id="deleteProductId">
            <div class="modal-body">
                <div style="text-align:center;margin-bottom:20px;">
                    <div style="width:56px;height:56px;background:rgba(231,76,60,0.12);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:1.4rem;color:var(--red);">
                        <i class="fas fa-triangle-exclamation"></i>
                    </div>
                    <p class="delete-modal-text">
                        You are about to permanently delete<br>
                        <strong id="deleteProductName">"Product Name"</strong>.<br>
                        This action <strong>cannot be undone</strong>.
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline-gold" onclick="closeModal('deleteModal')">Cancel</button>
                <button type="submit" class="btn-danger"><i class="fas fa-trash"></i> Delete Permanently</button>
            </div>
        </form>
    </div>
</div>

<!-- ── TOAST CONTAINER ── -->
<div class="toast-container" id="toastContainer"></div>

<?php
function renderProductFormFields($prefix = 'add') {
    $pre = $prefix === 'edit' ? 'edit' : 'add';
    ob_start(); ?>
    <div class="form-grid">
        <div class="form-group form-full">
            <label class="form-label">Product Name *</label>
            <input type="text" name="name" id="<?= $pre ?>Name" class="form-control" placeholder="e.g. Velvet Matte Lipstick" required>
        </div>
        <div class="form-group">
            <label class="form-label">Main Category *</label>
            <select name="category" id="<?= $pre ?>Category" class="form-control" required>
                <option value="">Select category...</option>
                <option value="Cosmetics">Cosmetics</option>
                <option value="Jewelry">Jewelry</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Sub-Category</label>
            <input type="text" name="sub_category" id="<?= $pre ?>SubCat" class="form-control" placeholder="e.g. Foundation, Necklace">
        </div>
        <div class="form-group">
            <label class="form-label">Brand</label>
            <input type="text" name="brand" id="<?= $pre ?>Brand" class="form-control" placeholder="Jenny Luxe" value="Jenny Luxe">
        </div>
        <div class="form-group">
            <label class="form-label">Stock Quantity</label>
            <input type="number" name="stock" id="<?= $pre ?>Stock" class="form-control" value="100" min="0">
        </div>
        <div class="form-group">
            <label class="form-label">Sale Price (Rs.) *</label>
            <input type="number" name="price" id="<?= $pre ?>Price" class="form-control" placeholder="699" step="0.01" min="1" required>
        </div>
        <div class="form-group">
            <label class="form-label">Original Price (Rs.)</label>
            <input type="number" name="old_price" id="<?= $pre ?>OldPrice" class="form-control" placeholder="999" step="0.01">
        </div>
        <div class="form-group">
            <label class="form-label">Rating (0–5)</label>
            <input type="number" name="rating" id="<?= $pre ?>Rating" class="form-control" value="4.5" min="0" max="5" step="0.1">
        </div>
        <div class="form-group form-full">
            <label class="form-label">Description</label>
            <textarea name="description" id="<?= $pre ?>Description" class="form-control" rows="3" placeholder="Product description for customers..."></textarea>
        </div>
        <div class="form-group form-full">
            <label class="form-label">Tags (comma separated)</label>
            <input type="text" name="tags" id="<?= $pre ?>Tags" class="form-control" placeholder="gold, festive, bridal, matte">
        </div>
        <div class="form-group form-full">
            <label class="form-label">Product Image</label>
            <div class="upload-zone" onclick="document.getElementById('<?= $pre ?>Img').click()">
                <i class="fas fa-cloud-arrow-up"></i>
                <p>Drop or <span>browse</span> to upload image</p>
                <p style="font-size:0.75rem;margin-top:4px;color:var(--text-dim);">JPG, PNG, WebP — max 5MB</p>
            </div>
            <input type="file" id="<?= $pre ?>Img" name="image" accept="image/*" style="display:none;" onchange="previewImg(this,'<?= $pre ?>Preview')">
            <img id="<?= $pre ?>Preview" class="img-preview" alt="">
        </div>
        <div class="form-group" style="flex-direction:row;align-items:center;gap:14px;flex-wrap:wrap;">
            <label style="display:flex;align-items:center;gap:7px;cursor:pointer;color:var(--text-muted);font-size:0.875rem;">
                <input type="checkbox" name="is_featured" id="<?= $pre ?>Featured" value="1" style="accent-color:var(--gold);width:16px;height:16px;"> Featured
            </label>
            <label style="display:flex;align-items:center;gap:7px;cursor:pointer;color:var(--text-muted);font-size:0.875rem;">
                <input type="checkbox" name="is_new" id="<?= $pre ?>New" value="1" style="accent-color:var(--gold);width:16px;height:16px;"> New Arrival
            </label>
            <label style="display:flex;align-items:center;gap:7px;cursor:pointer;color:var(--text-muted);font-size:0.875rem;">
                <input type="checkbox" name="is_bestseller" id="<?= $pre ?>Best" value="1" style="accent-color:var(--gold);width:16px;height:16px;"> Best Seller
            </label>
            <label style="display:flex;align-items:center;gap:7px;cursor:pointer;color:var(--text-muted);font-size:0.875rem;">
                <input type="checkbox" name="is_active" id="<?= $pre ?>Active" value="1" checked style="accent-color:var(--green);width:16px;height:16px;"> Active / Visible
            </label>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
?>

<script>
// ── MODAL OPEN / CLOSE ──
function openModal(id) {
    document.getElementById(id).classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById(id).classList.remove('active');
    document.body.style.overflow = '';
}

// Close on overlay click
document.querySelectorAll('.modal-overlay').forEach(el => {
    el.addEventListener('click', function(e) {
        if (e.target === this) closeModal(this.id);
    });
});

// ── EDIT PRODUCT ──
function editProduct(p) {
    const s = (id, val) => { const el = document.getElementById(id); if (el) el.value = val || ''; };
    const c = (id, val) => { const el = document.getElementById(id); if (el) el.checked = val == 1; };

    document.getElementById('editId').value           = p.id;
    document.getElementById('editExistingImg').value  = p.image || '';
    s('editName',        p.name);
    s('editCategory',    p.category);
    s('editSubCat',      p.sub_category);
    s('editBrand',       p.brand || 'Jenny Luxe');
    s('editStock',       p.stock);
    s('editPrice',       p.price);
    s('editOldPrice',    p.old_price);
    s('editRating',      p.rating);
    s('editDescription', p.description);
    s('editTags',        p.tags);
    c('editFeatured',    p.is_featured);
    c('editNew',         p.is_new);
    c('editBest',        p.is_bestseller);
    c('editActive',      p.is_active !== undefined ? p.is_active : 1);

    const prev = document.getElementById('editPreview');
    if (p.image) { prev.src = '../img/' + p.image; prev.style.display = 'block'; }
    else { prev.style.display = 'none'; }

    openModal('editModal');
}

// ── DELETE CONFIRM ──
function confirmDelete(id, name) {
    document.getElementById('deleteProductId').value = id;
    document.getElementById('deleteProductName').textContent = name;
    openModal('deleteModal');
}

// ── IMAGE PREVIEW ──
function previewImg(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById(previewId);
            img.src = e.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// ── FILTER BY STATUS (from stat cards) ──
function filterByStatus(status) {
    document.getElementById('statusFilterInput').value = status;
    document.getElementById('filterForm').submit();
}

// ── DEBOUNCED SEARCH ──
let searchTimer;
function debounceSearch(input) {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => input.form.submit(), 500);
}

// ── FORM VALIDATION ──
['addForm','editForm'].forEach(formId => {
    const form = document.getElementById(formId);
    if (!form) return;
    form.addEventListener('submit', function(e) {
        const nameInput     = this.querySelector('[name="name"]');
        const categoryInput = this.querySelector('[name="category"]');
        const priceInput    = this.querySelector('[name="price"]');
        let valid = true;

        [nameInput, categoryInput, priceInput].forEach(el => {
            if (!el || !el.value.trim()) {
                if (el) el.style.borderColor = 'var(--red)';
                valid = false;
            } else {
                if (el) el.style.borderColor = '';
            }
        });

        if (!valid) { e.preventDefault(); showToast('Please fill in all required fields.', 'error'); }
    });
});

// ── TOAST ──
function showToast(msg, type = 'success') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = 'toast ' + type;
    const icon = type === 'success' ? 'fa-circle-check' : type === 'error' ? 'fa-circle-exclamation' : 'fa-info-circle';
    toast.innerHTML = `<i class="fas ${icon}"></i><span>${msg}</span>`;
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(40px)';
        toast.style.transition = 'all 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

// ── Auto-open edit modal from GET ──
<?php if ($editProduct): ?>
editProduct(<?= json_encode($editProduct) ?>);
<?php endif; ?>

<?php if ($msg): ?>
showToast(<?= json_encode($msg) ?>, <?= json_encode($msgType) ?>);
<?php endif; ?>
</script>
</body>
</html>
