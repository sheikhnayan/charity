@extends('layouts.main')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container my-5">
    <div class="row">
        <div class="col-lg-8">
            <h1 class="mb-4">🛒 Shopping Cart</h1>
            
            <!-- Cart Items Container -->
            <div id="cartItemsContainer" class="card shadow-sm">
                <div id="cartEmpty" class="card-body p-5 text-center">
                    <i class="fas fa-shopping-cart" style="font-size: 48px; color: #999; margin-bottom: 20px;"></i>
                    <h4 class="text-muted">Your cart is empty</h4>
                    <p class="text-muted mb-0">Add some items to get started!</p>
                </div>
                
                <div id="cartItems" class="card-body" style="display: none;">
                    <!-- Cart items will be rendered here -->
                </div>
            </div>
        </div>

        <!-- Cart Summary Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow-sm position-sticky" style="top: 20px;">
                <div class="card-body">
                    <h5 class="card-title mb-4">Order Summary</h5>
                    
                    <!-- Subtotal -->
                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal:</span>
                        <span id="summarySubtotal">$0.00</span>
                    </div>
                    
                    <!-- Tax (if applicable) -->
                    <div class="d-flex justify-content-between mb-3" id="taxRow" style="display: none;">
                        <span>Tax:</span>
                        <span id="summaryTax">$0.00</span>
                    </div>
                    
                    <!-- Shipping (if applicable) -->
                    <div class="d-flex justify-content-between mb-3" id="shippingRow" style="display: none;">
                        <span>Shipping:</span>
                        <span id="summaryShipping">$0.00</span>
                    </div>
                    
                    <!-- Divider -->
                    <hr>
                    
                    <!-- Total -->
                    <div class="d-flex justify-content-between mb-4">
                        <strong>Total:</strong>
                        <strong id="summaryTotal" style="color: #667eea; font-size: 18px;">$0.00</strong>
                    </div>
                    
                    <!-- Checkout Button -->
                    <button class="btn btn-primary w-100 mb-2" id="checkoutBtn">
                        <i class="fas fa-lock me-2"></i> Proceed to Checkout
                    </button>
                    
                    <!-- Continue Shopping Button -->
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cart Item Template (Hidden) -->
<template id="cartItemTemplate">
    <div class="cart-item border-bottom pb-4 mb-4">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h6 class="mb-1 fw-bold item-name"></h6>
                <small class="text-muted">Item ID: <span class="item-id"></span></small>
            </div>
            <button type="button" class="btn-remove btn btn-sm btn-outline-danger">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        
        <div class="mb-3">
            <small class="text-muted d-block mb-2">Price: <strong class="item-price text-dark"></strong></small>
        </div>
        
        <!-- Quantity Controls -->
        <div class="d-flex align-items-center gap-2 mb-3">
            <span class="text-muted">Quantity:</span>
            <button type="button" class="btn-qty btn btn-sm btn-outline-secondary" data-action="decrease">−</button>
            <input type="number" class="form-control" style="width: 60px; text-align: center;" class="item-quantity" min="1" value="1">
            <button type="button" class="btn-qty btn btn-sm btn-outline-secondary" data-action="increase">+</button>
        </div>
        
        <!-- Item Total -->
        <div class="text-end">
            <span class="text-muted">Subtotal: </span>
            <strong class="item-total text-primary"></strong>
        </div>
    </div>
</template>

<style>
    .cart-item {
        animation: slideIn 0.3s ease;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .btn-qty {
        padding: 4px 10px;
        font-size: 14px;
    }
    
    .btn-remove {
        padding: 4px 8px;
    }
    
    .btn-remove:hover {
        background-color: #f8d7da !important;
        color: #d32f2f !important;
    }
    
    #checkoutBtn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    #checkoutBtn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
    
    .form-control {
        border: 1px solid #ddd;
        padding: 6px;
        font-size: 14px;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🛒 [CART PAGE] Initializing cart page...');
    
    // Load cart data immediately from API
    loadCartDataFromAPI();
});

function loadCartDataFromAPI() {
    console.log('🛒 [CART PAGE] Loading cart data from API...');
    
    fetch('/api/cart')
        .then(response => response.json())
        .then(data => {
            console.log('🛒 [CART PAGE] Cart API response:', data);
            
            if (data.success && data.cart) {
                renderCart(data.cart);
            } else {
                console.error('❌ [CART PAGE] Failed to load cart:', data);
            }
        })
        .catch(error => {
            console.error('❌ [CART PAGE] Error loading cart:', error);
        });
}

function renderCart(cartData) {
    console.log('🛒 [CART PAGE] Rendering cart...');
    console.log('🛒 [CART PAGE] Cart data:', cartData);
    
    const cartItemsContainer = document.getElementById('cartItems');
    const cartEmptyContainer = document.getElementById('cartEmpty');
    
    // Convert items object to array (API returns items as object)
    let itemsArray = [];
    if (cartData.items && typeof cartData.items === 'object' && !Array.isArray(cartData.items)) {
        // Items is an object with keys like 'student_1', 'ticket_2', etc.
        itemsArray = Object.entries(cartData.items).map(([key, item]) => {
            return {
                ...item,
                key: key
            };
        });
        console.log('🛒 [CART PAGE] Converted items object to array:', itemsArray);
    } else if (Array.isArray(cartData.items)) {
        itemsArray = cartData.items;
    }
    
    // Check if cart has items
    if (!itemsArray || itemsArray.length === 0) {
        console.log('🛒 [CART PAGE] Cart is empty');
        cartEmptyContainer.style.display = 'block';
        cartItemsContainer.style.display = 'none';
        updateSummary([]);
        return;
    }
    
    // Clear existing items
    cartItemsContainer.innerHTML = '';
    cartEmptyContainer.style.display = 'none';
    cartItemsContainer.style.display = 'block';
    
    // Render each item
    itemsArray.forEach((item, index) => {
        const itemElement = createCartItemElement(item, index);
        cartItemsContainer.appendChild(itemElement);
    });
    
    // Update summary
    updateSummary(itemsArray);
    
    console.log('✅ [CART PAGE] Cart rendered with', itemsArray.length, 'items');
}

function createCartItemElement(item, index) {
    const template = document.getElementById('cartItemTemplate');
    const clone = template.content.cloneNode(true);
    
    // Get item key (either from item.key or construct it)
    const itemKey = item.key || (item.type + '_' + item.id);
    
    // Set item data
    clone.querySelector('.item-name').textContent = item.name || 'Unknown Item';
    clone.querySelector('.item-id').textContent = item.id || 'N/A';
    clone.querySelector('.item-price').textContent = '$' + (item.price || item.amount || 0).toFixed(2);
    
    const quantityInput = clone.querySelector('.item-quantity');
    quantityInput.value = item.quantity || 1;
    
    // Calculate and set total
    const itemTotal = (item.price || item.amount || 0) * (item.quantity || 1);
    clone.querySelector('.item-total').textContent = '$' + itemTotal.toFixed(2);
    
    // Setup quantity controls
    const decreaseBtn = clone.querySelector('[data-action="decrease"]');
    const increaseBtn = clone.querySelector('[data-action="increase"]');
    const removeBtn = clone.querySelector('.btn-remove');
    
    decreaseBtn.addEventListener('click', function() {
        const currentQty = parseInt(quantityInput.value);
        if (currentQty > 1) {
            quantityInput.value = currentQty - 1;
            updateItemQuantity(itemKey, currentQty - 1);
        }
    });
    
    increaseBtn.addEventListener('click', function() {
        const currentQty = parseInt(quantityInput.value);
        quantityInput.value = currentQty + 1;
        updateItemQuantity(itemKey, currentQty + 1);
    });
    
    quantityInput.addEventListener('change', function() {
        const newQty = parseInt(this.value) || 1;
        if (newQty > 0) {
            updateItemQuantity(itemKey, newQty);
        } else {
            this.value = 1;
        }
    });
    
    removeBtn.addEventListener('click', function() {
        removeCartItem(itemKey);
    });
    
    return clone;
}

function updateItemQuantity(itemKey, quantity) {
    console.log('🛒 [CART PAGE] Updating item', itemKey, 'quantity to', quantity);
    
    fetch('/api/cart/item/' + itemKey, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ quantity: quantity })
    })
    .then(response => response.json())
    .then(data => {
        console.log('✅ [CART PAGE] Item updated:', data);
        loadCartDataFromAPI();
    })
    .catch(error => {
        console.error('❌ [CART PAGE] Error updating item:', error);
        loadCartDataFromAPI();
    });
}

function removeCartItem(itemKey) {
    console.log('🛒 [CART PAGE] Removing item:', itemKey);
    
    if (!confirm('Are you sure you want to remove this item from your cart?')) {
        return;
    }
    
    fetch('/api/cart/item/' + itemKey, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('✅ [CART PAGE] Item removed:', data);
        loadCartDataFromAPI();
    })
    .catch(error => {
        console.error('❌ [CART PAGE] Error removing item:', error);
        loadCartDataFromAPI();
    });
}

function updateSummary(items) {
    let subtotal = 0;
    
    if (items && items.length > 0) {
        items.forEach(item => {
            subtotal += (item.price || item.amount || 0) * (item.quantity || 1);
        });
    }
    
    const tax = 0; // Tax calculation can be added later
    const shipping = 0; // Shipping calculation can be added later
    const total = subtotal + tax + shipping;
    
    // Update display
    document.getElementById('summarySubtotal').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('summaryTotal').textContent = '$' + total.toFixed(2);
    
    // Show/hide tax and shipping rows
    const taxRow = document.getElementById('taxRow');
    const shippingRow = document.getElementById('shippingRow');
    
    if (tax > 0) taxRow.style.display = 'flex';
    else taxRow.style.display = 'none';
    
    if (shipping > 0) shippingRow.style.display = 'flex';
    else shippingRow.style.display = 'none';
    
    if (tax > 0) document.getElementById('summaryTax').textContent = '$' + tax.toFixed(2);
    if (shipping > 0) document.getElementById('summaryShipping').textContent = '$' + shipping.toFixed(2);
}

function handleCheckout() {
    console.log('🛒 [CART PAGE] Checkout clicked');
    
    fetch('/api/cart')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.cart && data.cart.items && data.cart.items.length > 0) {
                console.log('🛒 [CART PAGE] Proceeding with checkout, items:', data.cart.items);
                alert('Checkout feature coming soon! Items in cart: ' + data.cart.items.length);
                // TODO: Implement checkout functionality
            } else {
                alert('Your cart is empty!');
            }
        })
        .catch(error => {
            console.error('❌ [CART PAGE] Error checking out:', error);
            alert('An error occurred while processing checkout.');
        });
}

// Setup checkout button listener
document.addEventListener('DOMContentLoaded', function() {
    const checkoutBtn = document.getElementById('checkoutBtn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', handleCheckout);
    }
});
</script>
@endsection
