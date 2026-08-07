<!-- ============================================ -->
<!-- PRODUCT QUICK VIEW POPUP (MODAL) -->
<!-- ============================================ -->
<div class="product-popup-overlay" id="productPopupOverlay" onclick="closeProductPopup()"></div>

<div class="product-popup-modal" id="productPopupModal">
    <button class="popup-close" onclick="closeProductPopup()"><i class="fas fa-times"></i></button>
    
    <div class="popup-content">
        <div class="popup-image">
            <img id="popupProductImage" src="" alt="Product">
        </div>
        <div class="popup-details">
            <h2 id="popupProductName">Product Name</h2>
            <div class="popup-price" id="popupProductPrice">Rs. 0</div>
            
            <div class="popup-description">
                <h4>Description</h4>
                <p id="popupProductDesc">A premium quality product for your daily routine.</p>
            </div>

            <div class="popup-qty">
                <label>Quantity:</label>
                <div class="qty-box">
                    <button onclick="changePopupQty(-1)">-</button>
                    <span id="popupQty">1</span>
                    <button onclick="changePopupQty(1)">+</button>
                </div>
            </div>

            <div class="popup-actions">
                <button class="btn-primary" onclick="addToCartFromPopup()" style="width: 100%; padding: 15px;">Add to Cart</button>
            </div>
        </div>
    </div>
</div>