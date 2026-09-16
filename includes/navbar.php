<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!-- navbar.php -->
<nav class="nav-bar" id="mainNavBar" role="navigation" aria-label="Main navigation">
    <div class="nav-container">
        <ul class="nav-links" id="navLinksMenu">
            <li class="<?= $currentPage === 'index.php' ? 'active' : '' ?>">
                <a href="index.php">Home</a>
            </li>
            <li class="has-dropdown <?= $currentPage === 'products.php' ? 'active' : '' ?>">
                <a href="products.php">All Products <i class="fas fa-chevron-down" style="font-size:0.65rem;margin-left:3px;"></i></a>
                <ul class="nav-dropdown">
                    <li><a href="products.php"><i class="fas fa-border-all"></i> Full Catalog</a></li>
                    <li><a href="products.php?filter=featured"><i class="fas fa-star"></i> Featured Items</a></li>
                    <li><a href="products.php?sort=price-low"><i class="fas fa-arrow-down-short-wide"></i> Budget Friendly</a></li>
                </ul>
            </li>
            <li class="has-dropdown <?= $currentPage === 'cosmetics.php' ? 'active' : '' ?>">
                <a href="cosmetics.php">Cosmetics <i class="fas fa-chevron-down" style="font-size:0.65rem;margin-left:3px;"></i></a>
                <ul class="nav-dropdown">
                    <li><a href="cosmetics.php"><i class="fas fa-sparkles"></i> All Cosmetics</a></li>
                    <li><a href="cosmetics.php?sub=Foundation">Foundation</a></li>
                    <li><a href="cosmetics.php?sub=Mascara">Mascara</a></li>
                    <li><a href="cosmetics.php?sub=eye liner">Eyeliner</a></li>
                    <li><a href="cosmetics.php?sub=Lip Stick">Lipstick</a></li>
                    <li><a href="cosmetics.php?sub=blush">Blush</a></li>
                    <li><a href="cosmetics.php?sub=Eye Shadow">Eye Shadow</a></li>
                    <li><a href="cosmetics.php?sub=compact-powder">Compact Powder</a></li>
                </ul>
            </li>
            <li class="has-dropdown <?= $currentPage === 'imitation-jewelry.php' ? 'active' : '' ?>">
                <a href="imitation-jewelry.php">Jewelry <i class="fas fa-chevron-down" style="font-size:0.65rem;margin-left:3px;"></i></a>
                <ul class="nav-dropdown">
                    <li><a href="imitation-jewelry.php"><i class="fas fa-gem"></i> All Jewelry</a></li>
                    <li><a href="imitation-jewelry.php?sub=necklace">Necklaces</a></li>
                    <li><a href="imitation-jewelry.php?sub=earing">Earrings</a></li>
                    <li><a href="imitation-jewelry.php?sub=ring">Rings</a></li>
                    <li><a href="imitation-jewelry.php?sub=bracelet">Bracelets</a></li>
                </ul>
            </li>
            <li class="<?= $currentPage === 'new-arrivals.php' ? 'active' : '' ?>">
                <a href="new-arrivals.php">New Arrivals</a>
            </li>
            <li class="<?= $currentPage === 'best-sellers.php' ? 'active' : '' ?>">
                <a href="best-sellers.php">Best Sellers</a>
            </li>
            <li class="<?= $currentPage === 'offers.php' ? 'active' : '' ?>">
                <a href="offers.php">Offers</a>
            </li>
            <li class="<?= $currentPage === 'about.php' ? 'active' : '' ?>">
                <a href="about.php">About Us</a>
            </li>
            <li class="<?= $currentPage === 'contact.php' ? 'active' : '' ?>">
                <a href="contact.php">Contact</a>
            </li>
        </ul>
    </div>
</nav>
</div><!-- /sticky-header-wrapper -->

<style>
.nav-bar {
    background: #ffffff;
    padding: 0 5%;
    border-top: 1px solid #f0f0f0;
    display: flex;
    justify-content: center;
    width: 100%;
}
.nav-container {
    display: flex;
    align-items: center;
    justify-content: center;
    max-width: 1200px;
    width: 100%;
}
.nav-links {
    display: flex;
    gap: 22px;
    font-weight: 500;
    font-size: 0.82rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0; padding: 0;
    list-style: none;
}
.nav-links > li {
    position: relative;
    padding: 10px 0;
}
.nav-links > li > a {
    color: var(--dark-black);
    text-decoration: none;
    transition: color 0.25s ease;
    display: flex;
    align-items: center;
}
.nav-links > li:hover > a,
.nav-links > li.active > a {
    color: var(--primary-gold);
    font-weight: 600;
}
.nav-links > li::after {
    content: '';
    position: absolute;
    width: 0%;
    height: 2px;
    bottom: 0;
    left: 0;
    background-color: var(--primary-gold);
    transition: width 0.3s ease;
}
.nav-links > li:hover::after,
.nav-links > li.active::after {
    width: 100%;
}

.nav-hot-badge {
    background: var(--primary-gold);
    color: #111;
    font-size: 0.6rem;
    font-weight: 700;
    padding: 1px 6px;
    border-radius: 8px;
    margin-left: 4px;
    vertical-align: middle;
}

/* Dropdowns */
.nav-links li.has-dropdown:hover .nav-dropdown {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}
.nav-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    background: var(--white);
    min-width: 190px;
    border-radius: 0 0 10px 10px;
    border: 1px solid #eee;
    border-top: 2px solid var(--primary-gold);
    box-shadow: 0 12px 28px rgba(0,0,0,0.12);
    padding: 8px 0;
    margin: 0;
    list-style: none;
    opacity: 0;
    visibility: hidden;
    transform: translateY(6px);
    transition: all 0.25s ease;
    z-index: 1000;
}
.nav-dropdown li {
    border: none !important;
    padding: 0 !important;
}
.nav-dropdown a {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 9px 18px;
    font-size: 0.82rem;
    color: var(--dark-black);
    text-transform: none;
    letter-spacing: 0;
    transition: all 0.2s ease;
    text-decoration: none;
}
.nav-dropdown a:hover {
    background: var(--light-bg);
    color: var(--primary-gold);
    padding-left: 24px;
}
.nav-dropdown i {
    width: 16px;
    color: var(--primary-gold);
    font-size: 0.78rem;
}

@media (max-width: 992px) {
    .nav-links { gap: 14px; font-size: 0.78rem; }
    .nav-bar { display: none !important; }
}
</style>