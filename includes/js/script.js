// ============================================
// COMPLETE JAVASCRIPT LOGIC (CART + AUTH + FILTERS + POPUPS)
// ============================================

// --- 1. GLOBAL CART ARRAY ---
let cart = JSON.parse(localStorage.getItem('jennyCart')) || [];

// ============================================= //
// --- CART & SIDEBAR FUNCTIONS --- //
// ============================================= //

// --- 2. ADD TO CART FUNCTION (Standard) ---
function addToCart(name, price, image, id) {
    if (!id) id = name + '_' + Date.now();
    const numPrice = typeof price === 'number' ? price : parseFloat(price.toString().replace(/[^0-9.]/g, '')) || 0;
    
    const existingItem = cart.find(item => item.id === id || item.name === name);
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({ id: id, name: name, price: numPrice, image: image, quantity: 1 });
    }
    localStorage.setItem('jennyCart', JSON.stringify(cart));
    updateCartUI();
    showNotification(`${name} added to cart!`);
    
    if (document.getElementById('cartItemsWrapper')) {
        loadCartPage();
    }
}

// --- 3. UPDATE CART UI (Sidebar + Badge) ---
function updateCartUI() {
    const container = document.getElementById('cartItemsContainer');
    const emptyMsg = document.getElementById('emptyCartMsg');
    const totalPriceEl = document.getElementById('cartTotalPrice');
    const badge = document.getElementById('cart-badge');
    
    if (container) {
        container.innerHTML = '';
        let total = 0; let totalItems = 0;

        if (cart.length === 0) {
            if (emptyMsg) container.appendChild(emptyMsg);
            if (badge) badge.style.display = 'none';
            if (totalPriceEl) totalPriceEl.innerText = 'Rs. 0';
        } else {
            if (emptyMsg) emptyMsg.style.display = 'none';
            if (badge) badge.style.display = 'block';
            cart.forEach((item, index) => {
                total += item.price * item.quantity;
                totalItems += item.quantity;
                const itemDiv = document.createElement('div');
                itemDiv.className = 'cart-item';
                itemDiv.innerHTML = `
                    <img src="${item.image}" class="cart-item-img" alt="${item.name}">
                    <div class="cart-item-info">
                        <div class="cart-item-title">${item.name}</div>
                        <div class="cart-item-price">Rs. ${item.price}</div>
                        <div class="cart-item-qty">
                            <button class="qty-btn" onclick="changeQty(${index}, -1)">-</button>
                            <span>${item.quantity}</span>
                            <button class="qty-btn" onclick="changeQty(${index}, 1)">+</button>
                        </div>
                    </div>
                    <div class="cart-item-remove" onclick="removeFromCart(${index})">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                `;
                if (container) container.appendChild(itemDiv);
            });
            if (badge) badge.innerText = totalItems;
            if (totalPriceEl) totalPriceEl.innerText = `Rs. ${total}`;
        }
    }
}

// --- 4. CHANGE QUANTITY & REMOVE (Inside Sidebar) ---
function changeQty(index, change) {
    if (cart[index].quantity + change <= 0) removeFromCart(index);
    else {
        cart[index].quantity += change;
        localStorage.setItem('jennyCart', JSON.stringify(cart));
        updateCartUI();
    }
}

// --- 5. REMOVE FROM CART ---
function removeFromCart(index) {
    cart.splice(index, 1);
    localStorage.setItem('jennyCart', JSON.stringify(cart));
    updateCartUI();
    showNotification('Item removed from cart.');
    // If we are on cart.php, reload the cart page
    if (document.getElementById('cartItemsWrapper')) {
        loadCartPage();
    }
}

// --- 6. OPEN / CLOSE CART SIDEBAR ---
function openCart() {
    document.getElementById('cartSidebar').classList.add('active');
    document.getElementById('cartOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
    updateCartUI();
}
function closeCart() {
    document.getElementById('cartSidebar').classList.remove('active');
    document.getElementById('cartOverlay').classList.remove('active');
    document.body.style.overflow = 'auto';
}

// --- 7. NOTIFICATION POPUP ---
function showNotification(message) {
    const notification = document.createElement('div');
    notification.innerHTML = `<i class="fas fa-check-circle" style="color: green; margin-right: 10px;"></i> ${message}`;
    notification.style.cssText = `
        position: fixed; top: 20px; right: 20px; background: #fff; padding: 15px 25px;
        border-radius: 8px; box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        border-left: 5px solid var(--primary-gold); z-index: 99999;
        font-weight: 500; animation: slideIn 0.5s forwards; display: flex; align-items: center;
    `;
    document.body.appendChild(notification);
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.5s forwards';
        setTimeout(() => notification.remove(), 500);
    }, 3000);
}
const styleSheet = document.createElement("style");
styleSheet.textContent = `@keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } } @keyframes slideOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }`;
document.head.appendChild(styleSheet);


// ============================================= //
// --- PRODUCT POPUP (QUICK VIEW) --- //
// ============================================= //

let popupProduct = { 
    name: '', 
    price: 0, 
    image: '', 
    qty: 1 
};

// --- 8. OPEN POPUP ---
function openProductPopup(name, price, image, description) {
    popupProduct.name = name;
    popupProduct.price = price;
    popupProduct.image = image;
    popupProduct.qty = 1;

    const nameEl = document.getElementById('popupProductName');
    const priceEl = document.getElementById('popupProductPrice');
    const imgEl = document.getElementById('popupProductImage');
    const descEl = document.getElementById('popupProductDesc');
    const qtyEl = document.getElementById('popupQty');
    const overlay = document.getElementById('productPopupOverlay');
    const modal = document.getElementById('productPopupModal');

    if (nameEl) nameEl.innerText = name;
    if (priceEl) priceEl.innerText = 'Rs. ' + price;
    if (imgEl) imgEl.src = image;
    if (descEl) descEl.innerText = description || 'A premium quality product for your daily routine.';
    if (qtyEl) qtyEl.innerText = 1;

    if (overlay) overlay.classList.add('active');
    if (modal) modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

// --- 9. CLOSE POPUP ---
function closeProductPopup() {
    const overlay = document.getElementById('productPopupOverlay');
    const modal = document.getElementById('productPopupModal');
    if (overlay) overlay.classList.remove('active');
    if (modal) modal.classList.remove('active');
    document.body.style.overflow = 'auto';
}

// --- 10. CHANGE POPUP QUANTITY ---
function changePopupQty(change) {
    let qty = parseInt(document.getElementById('popupQty').innerText);
    qty += change;
    if (qty < 1) qty = 1;
    document.getElementById('popupQty').innerText = qty;
    popupProduct.qty = qty;
}

// --- 11. ADD TO CART FROM POPUP ---
function addToCartFromPopup() {
    const name = popupProduct.name;
    const price = popupProduct.price;
    const image = popupProduct.image;
    const qty = popupProduct.qty;
    const numPrice = typeof price === 'number' ? price : parseFloat(price.toString().replace(/[^0-9.]/g, '')) || 0;
    const itemId = name + '_' + image;

    const existingItem = cart.find(item => item.id === itemId || (item.name === name && item.image === image));
    if (existingItem) {
        existingItem.quantity += qty;
    } else {
        cart.push({ id: itemId, name: name, price: numPrice, image: image, quantity: qty });
    }
    localStorage.setItem('jennyCart', JSON.stringify(cart));
    updateCartUI();
    closeProductPopup();
    openCart();
    showNotification(`${qty} x ${name} added to cart!`);
    
    if (document.getElementById('cartItemsWrapper')) {
        loadCartPage();
    }
}


// ============================================= //
// --- LIVE QUANTITY ON CATEGORY CARDS --- //
// ============================================= //

// --- 12. CHANGE CARD QUANTITY ---
function changeCardQty(btn, change) {
    const qtyBox = btn.closest('.card-qty-box');
    const qtySpan = qtyBox.querySelector('span');
    let qty = parseInt(qtySpan.innerText);
    qty += change;
    if (qty < 1) qty = 1;
    qtySpan.innerText = qty;
}

// --- 13. ADD TO CART FROM CARD ---
function addToCartFromCard(btn, name, price, image, id) {
    const card = btn ? btn.closest('.category-card') : null;
    const cardId = id || (card ? card.getAttribute('data-id') : null) || (name + '_' + image);
    const qtySpan = card ? card.querySelector('.card-qty-box span') : null;
    const qty = qtySpan ? (parseInt(qtySpan.innerText) || 1) : 1;
    const numPrice = typeof price === 'number' ? price : parseFloat(price.toString().replace(/[^0-9.]/g, '')) || 0;
    
    const existingItem = cart.find(item => item.id === cardId || (item.name === name && item.image === image));
    if (existingItem) {
        existingItem.quantity += qty;
    } else {
        cart.push({ id: cardId, name: name, price: numPrice, image: image, quantity: qty });
    }
    localStorage.setItem('jennyCart', JSON.stringify(cart));
    if (qtySpan) qtySpan.innerText = 1;
    updateCartUI();
    openCart();
    showNotification(`${qty} x ${name} added to cart!`);
    
    if (document.getElementById('cartItemsWrapper')) {
        loadCartPage();
    }
}


// ============================================= //
// --- CART PAGE LOGIC --- //
// ============================================= //

// --- 14. LOAD CART ON CART PAGE ---
function loadCartPage() {
    const container = document.getElementById('cartItemsWrapper');
    const emptyMsg = document.getElementById('emptyCartMsg');
    const subtotalEl = document.getElementById('cartSubtotal');
    const totalEl = document.getElementById('cartTotal');
    
    if (!container) return;

    if (cart.length === 0) {
        container.innerHTML = '';
        if (emptyMsg) container.appendChild(emptyMsg);
        const summary = document.getElementById('cartSummaryWrapper');
        if (summary) summary.style.display = 'none';
        return;
    }

    const summary = document.getElementById('cartSummaryWrapper');
    if (summary) summary.style.display = 'block';
    if (emptyMsg) emptyMsg.style.display = 'none';
    
    container.innerHTML = '';
    let subtotal = 0;
    
    cart.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;

        const itemDiv = document.createElement('div');
        itemDiv.className = 'cart-item';
        itemDiv.innerHTML = `
            <img src="${item.image}" class="cart-item-img" alt="${item.name}">
            <div class="cart-item-details">
                <div class="cart-item-name">${item.name}</div>
                <div class="cart-item-price">Rs. ${item.price}</div>
                <div class="cart-item-qty">
                    <button onclick="changeCartQty(${index}, -1)">-</button>
                    <span>${item.quantity}</span>
                    <button onclick="changeCartQty(${index}, 1)">+</button>
                </div>
            </div>
            <div class="cart-item-actions">
                <div class="cart-item-total">Rs. ${itemTotal}</div>
                <div style="display: flex; gap: 10px; margin-top: 5px;">
                    <button class="btn-remove-item" onclick="removeFromCart(${index})">
                        <i class="fas fa-trash-alt"></i> Remove
                    </button>
                    <button class="btn-buy-now-item" onclick="buyNowFromCart(${index})">
                        <i class="fas fa-bolt"></i> Buy Now
                    </button>
                </div>
            </div>
        `;
        container.appendChild(itemDiv);
    });

    if (subtotalEl) subtotalEl.innerText = 'Rs. ' + subtotal;
    if (totalEl) totalEl.innerText = 'Rs. ' + subtotal;
}


// --- 15. CHANGE CART QUANTITY (CART PAGE) ---
function changeCartQty(index, change) {
    if (cart[index].quantity + change <= 0) {
        removeFromCart(index);
    } else {
        cart[index].quantity += change;
        localStorage.setItem('jennyCart', JSON.stringify(cart));
        updateCartUI();
        loadCartPage();
    }
}

// --- 16. CLEAR CART ---
function clearCart() {
    if (confirm('Are you sure you want to clear your cart?')) {
        cart = [];
        localStorage.setItem('jennyCart', JSON.stringify(cart));
        updateCartUI();
        loadCartPage();
        showNotification('Cart cleared.');
    }
}

// --- 17. PROCEED TO CHECKOUT ---
function proceedToCheckout() {
    if (cart.length === 0) {
        showNotification('Your cart is empty!');
        return;
    }
    window.location.href = 'checkout.php';
}


// --- 18. BUY NOW (SINGLE PRODUCT FROM CART PAGE) ---
function buyNowFromCart(index) {
    const item = cart[index];
    if (!item) return;
    
    // Save current cart temporarily
    const tempCart = [...cart];
    
    // Create new cart with only this item
    cart = [{
        id: item.id,
        name: item.name,
        price: item.price,
        image: item.image,
        quantity: item.quantity
    }];
    
    localStorage.setItem('jennyCart', JSON.stringify(cart));
    updateCartUI();
    loadCartPage();
    
    showNotification('Redirecting to checkout...');
    window.location.href = 'checkout.php';
}
// ============================================= //
// --- FILTER FUNCTIONS (SEARCH + CATEGORY + PRICE) --- //
// ============================================= //

let currentSearchTerm = "";
let currentCategory = "all";

function loadFiltersFromURL() {
    const params = new URLSearchParams(window.location.search);

    const searchVal = params.get('search') || params.get('q');
    if (searchVal) {
        const searchInput = document.getElementById('categorySearchInput');
        if (searchInput) searchInput.value = searchVal;
    }

    const catVal = params.get('category') || params.get('sub') || params.get('cat');
    if (catVal) {
        const radio = document.querySelector(`input[name="categoryFilter"][value="${catVal}"]`);
        if (radio) radio.checked = true;
    }

    const minP = params.get('minPrice');
    if (minP && document.getElementById('minPriceInput')) {
        document.getElementById('minPriceInput').value = minP;
    }

    const maxP = params.get('maxPrice');
    if (maxP && document.getElementById('maxPriceInput')) {
        document.getElementById('maxPriceInput').value = maxP;
    }

    const rating = params.get('rating');
    if (rating) {
        const rRadio = document.querySelector(`input[name="ratingFilter"][value="${rating}"]`);
        if (rRadio) rRadio.checked = true;
    }

    const sort = params.get('sort');
    if (sort && document.getElementById('sortSelect')) {
        document.getElementById('sortSelect').value = sort;
    }
}

function syncFiltersWithURL(search, category, minP, maxP, minR, sortVal) {
    if (!window.history || !window.history.replaceState) return;
    const url = new URL(window.location.href);

    if (search) url.searchParams.set('search', search); else url.searchParams.delete('search');
    if (category && category !== 'all') url.searchParams.set('category', category); else url.searchParams.delete('category');
    if (minP > 0) url.searchParams.set('minPrice', minP); else url.searchParams.delete('minPrice');
    if (maxP < 999999) url.searchParams.set('maxPrice', maxP); else url.searchParams.delete('maxPrice');
    if (minR > 0) url.searchParams.set('rating', minR); else url.searchParams.delete('rating');
    if (sortVal && sortVal !== 'default') url.searchParams.set('sort', sortVal); else url.searchParams.delete('sort');

    window.history.replaceState({}, '', url.toString());
}

// --- NORMALIZATION HELPER FOR ROBUST CATEGORY & SEARCH MATCHING ---
function normalizeStr(s) {
    if (!s) return '';
    let str = String(s).toLowerCase().replace(/[-_]/g, ' ').replace(/\s+/g, ' ').trim();
    str = str.replace(/\bearings\b|\bearings\b|\bearing\b|\bearring\b/g, 'earring');
    str = str.replace(/\bnecklaces\b/g, 'necklace');
    str = str.replace(/\brings\b/g, 'ring');
    str = str.replace(/\bbracelets\b/g, 'bracelet');
    str = str.replace(/\blipsticks\b|\blip stick\b|\blipstick\b/g, 'lipstick');
    str = str.replace(/\blip gloss\b|\blipgloss\b/g, 'lipgloss');
    str = str.replace(/\blip tint\b|\bliptint\b/g, 'liptint');
    str = str.replace(/\bcompact powder\b|\bcompactpowder\b/g, 'compact powder');
    str = str.replace(/\beye liner\b|\beyeliner\b/g, 'eyeliner');
    str = str.replace(/\beye shadow\b|\beyeshadow\b/g, 'eyeshadow');
    str = str.replace(/\bbase stick\b|\bbasestick\b/g, 'basestick');
    str = str.replace(/\bmake up fixer\b|\bmakeup fixer\b/g, 'makeup fixer');
    return str;
}

// --- HELPER TO EXTRACT SELLING PRICE ACCURATELY ---
function getCardSellingPrice(card) {
    let p = parseFloat(card.getAttribute('data-price'));
    if (!isNaN(p) && p > 0) return p;

    const newPriceEl = card.querySelector('.new-price');
    if (newPriceEl) {
        const num = parseFloat(newPriceEl.textContent.replace(/[^0-9.]/g, ''));
        if (!isNaN(num) && num > 0) return num;
    }

    const priceEl = card.querySelector('.card-price, .fp-price');
    if (priceEl) {
        const clone = priceEl.cloneNode(true);
        clone.querySelectorAll('.old-price').forEach(s => s.remove());
        const num = parseFloat(clone.textContent.replace(/[^0-9.]/g, ''));
        if (!isNaN(num) && num > 0) return num;
    }
    return 0;
}

function applyFilters() {
    const grid = document.getElementById('categoryGrid');
    if (!grid) return;

    const searchInput = document.getElementById('categorySearchInput');
    const cards = grid.querySelectorAll('.category-card');
    if (!searchInput && cards.length === 0) return;

    // Read all filter values
    const categoryRadio = document.querySelector('input[name="categoryFilter"]:checked');
    currentSearchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
    currentCategory   = categoryRadio ? categoryRadio.value.trim() : 'all';

    const normCategory  = normalizeStr(currentCategory);
    const normSearch    = normalizeStr(currentSearchTerm);

    let minPriceRaw = document.getElementById('minPriceInput') ? document.getElementById('minPriceInput').value.trim() : '';
    let maxPriceRaw = document.getElementById('maxPriceInput') ? document.getElementById('maxPriceInput').value.trim() : '';
    const minPrice = minPriceRaw === '' ? 0 : parseFloat(minPriceRaw);
    const maxPrice = maxPriceRaw === '' ? 999999 : parseFloat(maxPriceRaw);

    const ratingRadio = document.querySelector('input[name="ratingFilter"]:checked');
    const minRating   = ratingRadio ? parseFloat(ratingRadio.value) : 0;

    const sortSelect = document.getElementById('sortSelect');
    const sortVal    = sortSelect ? sortSelect.value : 'default';

    let cardsArray = Array.from(grid.querySelectorAll('.category-card'));
    let visibleCount = 0;

    cardsArray.forEach(card => {
        const rawName     = card.getAttribute('data-name') || '';
        const cardNameNorm = normalizeStr(rawName);
        const cardCatAttr  = normalizeStr(card.getAttribute('data-category') || '');
        const cardSubAttr  = normalizeStr(card.getAttribute('data-subcategory') || '');
        const cardTagsNorm = normalizeStr(card.getAttribute('data-tags') || '');
        const cardTitle    = normalizeStr(card.querySelector('h4, h3')?.textContent || '');

        const cardPrice = getCardSellingPrice(card);

        const ratingAttr = card.getAttribute('data-rating');
        const cardRating = (ratingAttr !== null && ratingAttr !== '') ? parseFloat(ratingAttr) : null;

        // --- Category match ---
        let categoryMatch = false;
        if (normCategory === 'all' || !normCategory) {
            categoryMatch = true;
        } else {
            categoryMatch = (cardNameNorm === normCategory)
                || cardCatAttr.includes(normCategory)
                || cardSubAttr.includes(normCategory)
                || cardNameNorm.includes(normCategory)
                || normCategory.includes(cardNameNorm);
        }

        // --- Search match ---
        const fullSearchBlob = cardNameNorm + ' ' + cardCatAttr + ' ' + cardSubAttr + ' ' + cardTagsNorm + ' ' + cardTitle;
        let searchMatch = (normSearch === '') || fullSearchBlob.includes(normSearch);

        // --- Price match ---
        let priceMatch = cardPrice >= minPrice && cardPrice <= maxPrice;

        // --- Rating match ---
        let ratingMatch = (minRating === 0) || (cardRating !== null && cardRating >= minRating);

        if (categoryMatch && searchMatch && priceMatch && ratingMatch) {
            card.classList.add('visible');
            card.style.display = '';
            visibleCount++;
        } else {
            card.classList.remove('visible');
            card.style.display = 'none';
        }
    });

    // Sort cards
    if (sortVal !== 'default') {
        cardsArray.sort((a, b) => {
            const priceA = getCardSellingPrice(a);
            const priceB = getCardSellingPrice(b);
            if (sortVal === 'price-low')  return priceA - priceB;
            if (sortVal === 'price-high') return priceB - priceA;
            if (sortVal === 'rating')     return (parseFloat(b.getAttribute('data-rating')) || 0) - (parseFloat(a.getAttribute('data-rating')) || 0);
            if (sortVal === 'name')       return (a.getAttribute('data-name') || '').localeCompare(b.getAttribute('data-name') || '');
            if (sortVal === 'newest')     return (parseInt(b.getAttribute('data-id') || 0)) - (parseInt(a.getAttribute('data-id') || 0));
            return 0;
        });
        cardsArray.forEach(card => grid.appendChild(card));
    }

    // Update result count
    const countEl = document.getElementById('productsCount');
    if (countEl) countEl.textContent = visibleCount + ' product' + (visibleCount !== 1 ? 's' : '') + ' found';

    // No results feedback
    let noResultsMsg = grid.querySelector('.no-results-msg');
    if (visibleCount === 0) {
        if (!noResultsMsg) {
            noResultsMsg = document.createElement('div');
            noResultsMsg.className = 'no-results-msg';
            noResultsMsg.style.cssText = 'grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: var(--text-grey);';
            noResultsMsg.innerHTML = `<i class="fas fa-search" style="font-size:2.5rem; color:#ccc; margin-bottom:15px; display:block;"></i>
                <h3 style="font-family:var(--font-heading); color:var(--dark-black); font-size:1.3rem; margin-bottom:8px;">No matching products found</h3>
                <p style="font-size:0.9rem; margin-bottom:20px;">Try relaxing your filters or search criteria.</p>
                <button class="btn-primary" onclick="clearAllFilters()" style="padding:10px 24px; border-radius:25px; cursor:pointer;">Clear All Filters</button>`;
            grid.appendChild(noResultsMsg);
        }
        noResultsMsg.style.display = 'block';
    } else if (noResultsMsg) {
        noResultsMsg.style.display = 'none';
    }

    renderActiveFilterChips(currentCategory, currentSearchTerm, minPrice, maxPrice, minRating);
    syncFiltersWithURL(currentSearchTerm, currentCategory, minPrice, maxPrice, minRating, sortVal);

    const clearBtn = document.getElementById('clearSearchBtn');
    if (clearBtn) {
        clearBtn.style.display = (currentSearchTerm.length > 0 || currentCategory !== 'all' || minPrice > 0 || maxPrice < 999999 || minRating > 0) ? 'block' : 'none';
    }
}

function renderActiveFilterChips(category, search, minP, maxP, minR) {
    const container = document.getElementById('activeFilterChips');
    if (!container) return;
    container.innerHTML = '';

    const chips = [];
    if (category !== 'all') chips.push({ label: `Category: ${category}`, reset: () => { const r = document.querySelector('input[name="categoryFilter"][value="all"]'); if (r) r.checked = true; applyFilters(); } });
    if (search !== '') chips.push({ label: `Search: "${search}"`, reset: () => { const s = document.getElementById('categorySearchInput'); if (s) s.value = ''; applyFilters(); } });
    if (minP > 0 || maxP < 999999) chips.push({ label: `Price: Rs. ${minP} - ${maxP === 999999 ? 'Max' : maxP}`, reset: () => { const minE = document.getElementById('minPriceInput'); const maxE = document.getElementById('maxPriceInput'); if (minE) minE.value = ''; if (maxE) maxE.value = ''; applyFilters(); } });
    if (minR > 0) chips.push({ label: `Rating: ${minR}★+`, reset: () => { const r = document.querySelector('input[name="ratingFilter"][value="0"]'); if (r) r.checked = true; applyFilters(); } });

    chips.forEach(chip => {
        const chipEl = document.createElement('div');
        chipEl.style.cssText = 'display:inline-flex; align-items:center; gap:6px; padding:4px 12px; background:var(--primary-gold); color:#111; font-size:0.78rem; font-weight:600; border-radius:15px;';
        chipEl.innerHTML = `<span>${chip.label}</span> <i class="fas fa-times" style="cursor:pointer;"></i>`;
        chipEl.querySelector('i').onclick = chip.reset;
        container.appendChild(chipEl);
    });
}

function setPriceRange(min, max) {
    const minEl = document.getElementById('minPriceInput');
    const maxEl = document.getElementById('maxPriceInput');
    if (minEl) minEl.value = min;
    if (maxEl) maxEl.value = max;
    applyFilters();
}

function toggleFilterDrawer(show) {
    const sidebar = document.getElementById('filterSidebar');
    if (sidebar) {
        sidebar.classList.toggle('active', show);
    }
}

function filterCategoryProducts() {
    applyFilters();
}

function clearAllFilters() {
    const searchInput = document.getElementById('categorySearchInput');
    if (searchInput) searchInput.value = "";
    const allCatRadio = document.querySelector('input[name="categoryFilter"][value="all"]');
    if (allCatRadio) allCatRadio.checked = true;
    const minP = document.getElementById('minPriceInput');
    if (minP) minP.value = "";
    const maxP = document.getElementById('maxPriceInput');
    if (maxP) maxP.value = "";
    const allRatingRadio = document.querySelector('input[name="ratingFilter"][value="0"]');
    if (allRatingRadio) allRatingRadio.checked = true;
    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect) sortSelect.value = 'default';

    applyFilters();
    if (searchInput) searchInput.focus();
}


// ============================================= //
// --- AUTH LOGIC (LOGIN / REGISTER / LOGOUT) --- //
// ============================================= //

// --- 18. OPEN AUTH POPUP ---
function openAuthPopup(type) {
    closeAllAuthPopups();
    if (type === 'login') {
        document.getElementById('loginOverlay').classList.add('active');
        document.getElementById('loginModal').classList.add('active');
    } else if (type === 'register') {
        document.getElementById('registerOverlay').classList.add('active');
        document.getElementById('registerModal').classList.add('active');
    } else if (type === 'logout') {
        document.getElementById('logoutOverlay').classList.add('active');
        document.getElementById('logoutModal').classList.add('active');
    }
    document.body.style.overflow = 'hidden';
}

// --- 19. CLOSE AUTH POPUP ---
function closeAuthPopup(type) {
    if (type === 'login') {
        document.getElementById('loginOverlay').classList.remove('active');
        document.getElementById('loginModal').classList.remove('active');
    } else if (type === 'register') {
        document.getElementById('registerOverlay').classList.remove('active');
        document.getElementById('registerModal').classList.remove('active');
    } else if (type === 'logout') {
        document.getElementById('logoutOverlay').classList.remove('active');
        document.getElementById('logoutModal').classList.remove('active');
    }
    document.body.style.overflow = 'auto';
}

// --- 20. CLOSE ALL AUTH POPUPS ---
function closeAllAuthPopups() {
    document.getElementById('loginOverlay').classList.remove('active');
    document.getElementById('loginModal').classList.remove('active');
    document.getElementById('registerOverlay').classList.remove('active');
    document.getElementById('registerModal').classList.remove('active');
    document.getElementById('logoutOverlay').classList.remove('active');
    document.getElementById('logoutModal').classList.remove('active');
}

// --- 21. SWITCH BETWEEN LOGIN & REGISTER ---
function switchAuthPopup(type) {
    closeAllAuthPopups();
    openAuthPopup(type);
}

// --- 22. HANDLE LOGIN ---
// The popup now redirects to the dedicated auth page which has
// proper PHP session management. Pre-fills email if provided.
function handleLogin(e) {
    e.preventDefault();
    const email = document.getElementById('loginEmail')?.value || '';
    // Redirect to dedicated login page (pre-fill email via URL param if possible)
    const dest = 'auth/login.php' + (email ? '?hint=' + encodeURIComponent(email) : '');
    closeAllAuthPopups();
    window.location.href = dest;
}

// --- 23. HANDLE REGISTER ---
// The popup now redirects to the dedicated registration page.
function handleRegister(e) {
    e.preventDefault();
    const name = document.getElementById('regName')?.value || '';
    const email = document.getElementById('regEmail')?.value || '';
    const password = document.getElementById('regPassword')?.value || '';
    const confirm = document.getElementById('regConfirmPassword')?.value || '';
    
    if (password && confirm && password !== confirm) {
        showNotification('Passwords do not match!');
        return;
    }
    // Redirect to dedicated register page
    closeAllAuthPopups();
    window.location.href = 'auth/register.php';
}

// --- 24. HANDLE LOGOUT ---
function handleLogout() {
    localStorage.removeItem('isLoggedIn');
    localStorage.removeItem('userName');
    updateUserUI();
    closeAllAuthPopups();
    showNotification('Logged out successfully!');
}

// --- 25. UPDATE USER UI ---
function updateUserUI() {
    const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
    const userName = localStorage.getItem('userName') || 'User';
    const guestActions = document.getElementById('guestActions');
    const userActions = document.getElementById('userActions');
    const userNameDisplay = document.getElementById('userNameDisplay');
    
    if (isLoggedIn) {
        if (guestActions) guestActions.style.display = 'none';
        if (userActions) {
            userActions.style.display = 'flex';
            if (userNameDisplay) userNameDisplay.innerText = userName;
        }
    } else {
        if (guestActions) guestActions.style.display = 'flex';
        if (userActions) userActions.style.display = 'none';
    }
}


// ============================================= //
// --- CONTACT FORM SUBMIT HANDLER --- //
// ============================================= //

function handleContactSubmit(e) {
    e.preventDefault();
    const name = document.getElementById('contactName').value;
    const email = document.getElementById('contactEmail').value;
    const subject = document.getElementById('contactSubject').value;
    const message = document.getElementById('contactMessage').value;
    
    showNotification(`Thank you ${name}! Your message has been sent. We'll get back to you soon.`);
    document.querySelector('.contact-form').reset();
}
// NOTE: The main window.onload / DOMContentLoaded handler is
// defined further below (line ~852) and is the canonical one.
// This placeholder is intentionally left blank to avoid conflicts.


// ============================================= //
// --- CHECKOUT PAGE LOGIC --- //
// ============================================= //

// --- 1. LOAD ORDER SUMMARY ON CHECKOUT PAGE ---
function loadOrderSummary() {
    const container = document.getElementById('orderItems');
    const subtotalEl = document.getElementById('orderSubtotal');
    const totalEl = document.getElementById('orderTotal');
    const shippingEl = document.getElementById('orderShipping');
    
    if (!container) return;

    if (cart.length === 0) {
        container.innerHTML = `<p style="text-align: center; color: var(--text-grey); padding: 20px;">Your cart is empty. <a href="products.php">Shop now</a></p>`;
        if (subtotalEl) subtotalEl.innerText = 'Rs. 0';
        if (totalEl) totalEl.innerText = 'Rs. 0';
        return;
    }

    container.innerHTML = '';
    let subtotal = 0;
    
    cart.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;

        const itemDiv = document.createElement('div');
        itemDiv.className = 'order-item';
        itemDiv.innerHTML = `
            <img src="${item.image}" class="order-item-img" alt="${item.name}">
            <div class="order-item-details">
                <div class="order-item-name">${item.name}</div>
                <div class="order-item-price">Rs. ${item.price} x ${item.quantity}</div>
            </div>
            <div class="order-item-total">Rs. ${itemTotal}</div>
        `;
        container.appendChild(itemDiv);
    });

    // Update totals
    if (subtotalEl) subtotalEl.innerText = 'Rs. ' + subtotal;
    if (totalEl) totalEl.innerText = 'Rs. ' + subtotal; // Shipping is free
}

// --- 2. PLACE ORDER | sends data to process-order.php ---
function placeOrder(e) {
    if (e && e.preventDefault) e.preventDefault();
    
    if (cart.length === 0) {
        showNotification('Your cart is empty!');
        return;
    }

    const paymentEl = document.querySelector('input[name="paymentMethod"]:checked');
    const paymentMethod = paymentEl ? paymentEl.value : 'cod';

    const fullName   = (document.getElementById('fullName')    || {}).value || '';
    const email      = (document.getElementById('email')       || {}).value || '';
    const phone      = (document.getElementById('phone')       || {}).value || '';
    const city       = (document.getElementById('city')        || {}).value || '';
    const zip        = (document.getElementById('zip')         || {}).value || '';
    const address    = (document.getElementById('address')     || {}).value || '';
    const orderNotes = (document.getElementById('orderNotes')  || {}).value || '';

    // Basic validation
    const nameRegex = /^[A-Za-z\s]+$/;
    if (!nameRegex.test(fullName)) {
        showNotification('Name can only contain letters and spaces.');
        return;
    }
    if (!fullName || !email || !phone || !city || !address) {
        showNotification('Please fill in all required fields.');
        return;
    }

    // Show loading state on Place Order button
    const placeBtn = document.querySelector('.btn-continue');
    const origBtnText = placeBtn ? placeBtn.innerHTML : '';
    if (placeBtn) { placeBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Placing Order...'; placeBtn.disabled = true; }

    const orderData = {
        name:    fullName,
        email:   email,
        phone:   phone,
        address: address,
        city:    city,
        zip:     zip,
        notes:   orderNotes,
        payment: paymentMethod.toUpperCase(),
        items:   cart
    };

    fetch('process-order.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(orderData)
    })
    .then(res => res.json())
    .then(result => {
        if (result.success) {
            showNotification('Order placed successfully!');
            localStorage.removeItem('jennyCart');
            cart = [];
            setTimeout(() => {
                window.location.href = 'order-confirmation.php';
            }, 1200);
        } else {
            showNotification(result.message || 'Order failed. Please try again.');
            if (placeBtn) { placeBtn.innerHTML = origBtnText; placeBtn.disabled = false; }
        }
    })
    .catch(err => {
        console.error('Order error:', err);
        showNotification('Network error. Please check your connection and try again.');
        if (placeBtn) { placeBtn.innerHTML = origBtnText; placeBtn.disabled = false; }
    });
}

// --- 3. PROCESS ONLINE PAYMENT (simulated) ---
function processOnlinePayment() {
    const payBtn = document.querySelector('.pay-now-btn');
    if (!payBtn) return;
    payBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    payBtn.disabled = true;

    setTimeout(() => {
        // Re-use the same placeOrder logic
        if (typeof closePaymentModal === 'function') closePaymentModal();
        placeOrder({});
    }, 2000);
}

// --- 3. INITIALIZE ON PAGE LOAD ---
window.onload = function() {
    if (document.getElementById('orderItems')) {
        loadOrderSummary();
    }
    if (document.getElementById('cartItemsWrapper')) {
        loadCartPage();
    }
    updateCartUI();
    loadFiltersFromURL();
    applyFilters();
    updateUserUI();
};



// ============================================= //
// --- AVATAR DROPDOWN TOGGLE --- //
// ============================================= //

function toggleUserDropdown() {
    const dropdown = document.getElementById('userDropdown');
    if (dropdown) {
        dropdown.classList.toggle('show');
    }
}

// Click anywhere outside to close dropdown
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('userDropdown');
    const avatar = document.querySelector('.avatar-container');
    if (dropdown && avatar) {
        if (!avatar.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    }
});



// ============================================= //
// --- BUY NOW FROM CARD --- //
// ============================================= //

function buyNowFromCard(btn, name, price, image, id) {
    const card = btn ? btn.closest('.category-card') : null;
    const cardId = id || (card ? card.getAttribute('data-id') : null) || (name + '_' + image);
    const qtySpan = card ? card.querySelector('.card-qty-box span') : null;
    const qty = qtySpan ? (parseInt(qtySpan.innerText) || 1) : 1;
    const numPrice = typeof price === 'number' ? price : parseFloat(price.toString().replace(/[^0-9.]/g, '')) || 0;

    const existingItem = cart.find(item => item.id === cardId || (item.name === name && item.image === image));
    if (existingItem) {
        existingItem.quantity += qty;
    } else {
        cart.push({ id: cardId, name: name, price: numPrice, image: image, quantity: qty });
    }
    localStorage.setItem('jennyCart', JSON.stringify(cart));
    updateCartUI();
    showNotification('Redirecting to checkout...');
    setTimeout(() => {
        window.location.href = 'checkout.php';
    }, 200);
}

// ============================================= //
// --- MOBILE NAV DRAWER TOGGLE --- //
// ============================================= //
function toggleMobileNav(show) {
    var overlay = document.getElementById('mobileNavOverlay');
    var panel   = document.getElementById('mobileNavPanel');

    if (!overlay || !panel) {
        console.warn('[MobileNav] Elements not found in DOM');
        return;
    }

    var isOpen    = panel.classList.contains('open');
    var shouldOpen = (show === undefined) ? !isOpen : Boolean(show);

    if (shouldOpen) {
        panel.classList.add('open');
        overlay.classList.add('open');
        document.body.style.overflow = 'hidden';
    } else {
        panel.classList.remove('open');
        overlay.classList.remove('open');
        document.body.style.overflow = '';
    }
}

// ============================================= //
// --- WISHLIST TOGGLE HANDLER --- //
// ============================================= //
let wishlist = JSON.parse(localStorage.getItem('jennyWishlist')) || [];

function toggleWishlist(btn, productName) {
    const icon = btn.querySelector('i');
    const index = wishlist.indexOf(productName);
    
    if (index > -1) {
        wishlist.splice(index, 1);
        if (icon) {
            icon.classList.remove('fas');
            icon.classList.add('far');
        }
        showNotification(`${productName} removed from wishlist.`);
    } else {
        wishlist.push(productName);
        if (icon) {
            icon.classList.remove('far');
            icon.classList.add('fas');
        }
        showNotification(`${productName} added to wishlist!`);
    }
    localStorage.setItem('jennyWishlist', JSON.stringify(wishlist));
}

// ============================================= //
// --- PAGE INIT: filters + URL params + cart --- //
// ============================================= //
document.addEventListener('DOMContentLoaded', function () {
    // Restore filter state from URL params (if any)
    if (typeof loadFiltersFromURL === 'function') loadFiltersFromURL();

    // Initialize cart badge
    if (typeof updateCartUI === 'function') updateCartUI();

    // Auto-set data-price and data-rating on static cards that lack them
    const grid = document.getElementById('categoryGrid');
    if (grid) {
        const ratingsPool = [3.5, 3.8, 4.0, 4.2, 4.3, 4.4, 4.5, 4.5, 4.6, 4.7, 4.8, 4.9, 5.0];
        let rIdx = 0;
        grid.querySelectorAll('.category-card').forEach(function (card) {
            // Set data-price from first text node of .card-price (avoids old-price child spans)
            if (!card.hasAttribute('data-price')) {
                const priceEl = card.querySelector('.card-price');
                if (priceEl) {
                    const firstNum = (priceEl.childNodes[0]?.textContent || priceEl.textContent || '').replace(/[^0-9.]/g, '');
                    const price = parseFloat(firstNum);
                    if (!isNaN(price) && price > 0) card.setAttribute('data-price', price);
                }
            }
            // Set data-rating with varied distribution
            if (!card.hasAttribute('data-rating')) {
                card.setAttribute('data-rating', ratingsPool[rIdx % ratingsPool.length]);
                rIdx++;
            }
        });

        // Apply filters now that data attributes are set
        if (typeof applyFilters === 'function') applyFilters();
    }
});
