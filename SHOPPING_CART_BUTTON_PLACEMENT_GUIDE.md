# Shopping Cart - Component Integration Instructions

This document provides exact instructions on where and how to add "Add to Cart" buttons in your existing component views.

---

## 1. Student Listing Component

**File:** `resources/views/page-components/render-component.blade.php`  
**Lines:** ~4545-4700 (student-listing component)

### Current Structure
```blade
@case('student-listing')
    @foreach($data['items'] as $student)
        <div class="student-card">
            <!-- Photo, name, progress bar, donation info -->
            <!-- Current: Direct donate button or link -->
        </div>
    @endforeach
```

### Add Cart Button
Find the student card section and add the cart button alongside or instead of existing donation button:

```blade
@case('student-listing')
    @foreach($data['items'] as $student)
        <div class="student-card">
            <!-- Existing content: photo, name, progress bar -->
            
            <div class="student-card-actions">
                <!-- Option A: Add cart button alongside existing donate -->
                @include('components.add-to-cart-btn', [
                    'itemType' => 'student',
                    'itemId' => $student->id,
                    'itemName' => $student->name,
                    'amount' => 100,  // Default amount, user can change in cart
                    'buttonText' => 'Donate',
                    'buttonClass' => 'btn-add-to-cart'
                ])
                
                <!-- Option B: Or replace existing donate button completely -->
                <!-- Remove: <a href="/donate?student={{ $student->id }}" class="btn">Donate</a> -->
            </div>
        </div>
    @endforeach
@break
```

### Style Updates
If needed, add CSS to align the button nicely in student card:
```css
.student-card-actions {
    display: flex;
    gap: 12px;
    margin-top: 12px;
    justify-content: center;
}

.student-card-actions .btn-add-to-cart {
    flex: 1;
}
```

---

## 2. Auction List Component

**File:** `resources/views/page-components/render-component.blade.php`  
**Search for:** `@case('auction-list')` or similar

### Current Structure (example)
```blade
@case('auction-list')
    @foreach($data['auctions'] as $auction)
        <div class="auction-item">
            <!-- Item info, current bid, time remaining -->
            <!-- Current: Bid now button/link -->
        </div>
    @endforeach
```

### Add Cart Button
```blade
<div class="auction-item-actions">
    <!-- Current bid button -->
    <a href="/auction/{{ $auction->id }}" class="btn btn-primary">
        Place Bid
    </a>
    
    <!-- Add to Cart option -->
    @include('components.add-to-cart-btn', [
        'itemType' => 'auction',
        'itemId' => $auction->id,
        'itemName' => $auction->name,
        'currentBid' => $auction->current_bid,
        'price' => $auction->starting_price,
        'buttonText' => 'Add to Cart',
        'buttonClass' => 'btn-add-to-cart btn-secondary'
    ])
</div>
```

---

## 3. Ticket Sales Component

**File:** `resources/views/page-components/render-component.blade.php`  
**Search for:** `@case('sell-tickets')` or similar

### Current Structure
```blade
@case('sell-tickets')
    @foreach($data['tickets'] as $ticket)
        <div class="ticket-item">
            <!-- Price, availability, buy button -->
        </div>
    @endforeach
```

### Add Cart Button
```blade
<div class="ticket-item-actions">
    <!-- Add to Cart button -->
    @include('components.add-to-cart-btn', [
        'itemType' => 'ticket',
        'itemId' => $ticket->id,
        'itemName' => $ticket->name,
        'price' => $ticket->price,
        'buttonText' => 'Buy Now',
        'buttonClass' => 'btn-add-to-cart'
    ])
</div>
```

---

## 4. Product Listings

**File:** Varies (product listing page)  
**Typical locations:**
- `resources/views/products/list.blade.php`
- `resources/views/shop/products.blade.php`
- Or in page-components if using dynamic builder

### Add Cart Button
```blade
<div class="product-item">
    <div class="product-image">
        <img src="{{ $product->image }}" alt="{{ $product->name }}">
    </div>
    
    <div class="product-info">
        <h3>{{ $product->name }}</h3>
        <p class="price">${{ number_format($product->price, 2) }}</p>
        
        <!-- Add to Cart button -->
        @include('components.add-to-cart-btn', [
            'itemType' => 'product',
            'itemId' => $product->id,
            'itemName' => $product->name,
            'price' => $product->price,
            'buttonText' => 'Add to Cart'
        ])
    </div>
</div>
```

---

## 5. Direct Donation Page

**File:** Likely `resources/views/donate.blade.php` or custom page

### Option A: Add Alternative Cart Path
```blade
<div class="donation-methods">
    <!-- Existing: Direct donation -->
    <div class="method">
        <h3>Donate Now</h3>
        <form action="/process-donation" method="POST">
            <!-- Existing donation form -->
        </form>
    </div>
    
    <!-- New: Add to Cart option -->
    <div class="method">
        <h3>Build Your Order</h3>
        <p>Add students to your cart and complete in one checkout</p>
        
        @foreach($students as $student)
            @include('components.add-to-cart-btn', [
                'itemType' => 'student',
                'itemId' => $student->id,
                'itemName' => $student->name,
                'amount' => 100,
                'buttonText' => 'Add to Cart',
                'buttonClass' => 'btn-secondary'
            ])
        @endforeach
    </div>
</div>
```

### Option B: Replace with Cart for Students
If you want to move all donations through cart:
```blade
@foreach($students as $student)
    <div class="student-donation-card">
        <h4>{{ $student->name }}</h4>
        
        @include('components.add-to-cart-btn', [
            'itemType' => 'student',
            'itemId' => $student->id,
            'itemName' => $student->name,
            'amount' => 50,  // Starting amount
            'buttonText' => 'Support This Student'
        ])
    </div>
@endforeach
```

---

## Implementation Workflow

### Step 1: Identify Component Location
Find each component in `render-component.blade.php` using search:
- "student-listing"
- "auction-list"
- "sell-tickets"
- Similar patterns for other components

### Step 2: Locate Button Area
Find the section with existing action buttons (donate, bid, buy, etc.)

### Step 3: Add Cart Button
Insert the component include with appropriate parameters:
```blade
@include('components.add-to-cart-btn', [
    'itemType' => '[type]',
    'itemId' => $item->id,
    'itemName' => $item->name,
    'amount' => $item->price,
    'buttonText' => '[text]'
])
```

### Step 4: Test
1. Load page in browser
2. Cart icon should appear in bottom-right
3. Click "Add to Cart" button
4. Item should appear in cart drawer
5. Cart count should update
6. Navigate to checkout

---

## Alternative Integration: Direct Component Include

If you prefer more control, include the add-to-cart button component directly with custom styling:

```blade
<!-- Method 1: Using component include (easiest) -->
@include('components.add-to-cart-btn', [
    'itemType' => 'student',
    'itemId' => $student->id,
    'itemName' => $student->name,
    'amount' => 100
])

<!-- Method 2: Using custom button with JavaScript event -->
<button class="btn-custom" onclick="ShoppingCart.addItem({
    type: 'student',
    id: {{ $student->id }},
    name: '{{ addslashes($student->name) }}',
    amount: 100
})">
    <i class="fas fa-shopping-cart"></i> Add to Cart
</button>

<!-- Method 3: Custom HTML + inline script -->
<button class="my-custom-button" data-item-type="student" data-item-id="{{ $student->id }}">
    Add to Cart
</button>

<script>
document.querySelectorAll('[data-item-type]').forEach(btn => {
    btn.addEventListener('click', function() {
        ShoppingCart.addItem({
            type: this.dataset.itemType,
            id: parseInt(this.dataset.itemId),
            name: this.dataset.itemName || 'Item',
            amount: parseFloat(this.dataset.amount || 100)
        });
    });
});
</script>
```

---

## Component Parameters Reference

### @include('components.add-to-cart-btn', [ ... ])

| Parameter | Type | Required | Default | Notes |
|-----------|------|----------|---------|-------|
| itemType | string | Yes | - | student, ticket, auction, product |
| itemId | number | Yes | - | Unique ID of the item |
| itemName | string | Yes | - | Display name (e.g., student name, ticket name) |
| amount | number | No | 100 | For students: donation amount |
| price | number | No | 0 | For tickets/products: unit price |
| currentBid | number | No | price | For auctions: current bid amount |
| buttonText | string | No | Auto | "Donate Now", "Buy Ticket", etc. |
| buttonClass | string | No | btn-add-to-cart | CSS class(es) for styling |

---

## Testing Instructions

### Test Each Component

1. **Student Listing**
   - [ ] Click "Add to Cart" on student card
   - [ ] Verify student appears in cart
   - [ ] Verify cart count increases
   - [ ] Open cart drawer and verify student shown
   - [ ] Update donation amount in cart
   - [ ] Remove from cart

2. **Auction List**
   - [ ] Click "Add to Cart" on auction item
   - [ ] Verify current bid is used as price
   - [ ] Verify quantity selector appears
   - [ ] Update quantity
   - [ ] Remove item

3. **Tickets**
   - [ ] Click "Add to Cart" on ticket
   - [ ] Verify fixed price is used
   - [ ] Verify quantity can be increased
   - [ ] Test with multiple tickets

4. **Mixed Cart**
   - [ ] Add 2 students with different amounts
   - [ ] Add 1 ticket with quantity 2
   - [ ] Add 1 auction item
   - [ ] Verify total is correct
   - [ ] Proceed to checkout

5. **Checkout Flow**
   - [ ] Complete checkout with mixed items
   - [ ] Verify payment processes
   - [ ] Check success page shows all items
   - [ ] Verify PaymentFunnelService events recorded

6. **Non-Breaking**
   - [ ] Test existing direct donation still works
   - [ ] Test existing direct ticket purchase still works
   - [ ] Test existing auction bidding still works
   - [ ] Verify no existing features broken

---

## Styling Customization

The "Add to Cart" button uses these CSS classes:
- `.btn-add-to-cart` - Main button style
- `.btn-primary` - Primary color variant
- `.btn-secondary` - Secondary color variant
- `.btn-outline` - Outline style
- `.btn-sm` - Small size
- `.btn-lg` - Large size

### Custom Styling Example
```css
/* Override default styles */
.custom-add-to-cart-btn {
    background: #27ae60;
    color: white;
    padding: 8px 16px;
    border-radius: 4px;
}

.custom-add-to-cart-btn:hover {
    background: #229954;
}
```

Then use in component:
```blade
@include('components.add-to-cart-btn', [
    'itemType' => 'student',
    'itemId' => $student->id,
    'itemName' => $student->name,
    'amount' => 100,
    'buttonClass' => 'custom-add-to-cart-btn'
])
```

---

## Quick Reference: Copy-Paste Code

### Student Card
```blade
@include('components.add-to-cart-btn', [
    'itemType' => 'student',
    'itemId' => $student->id,
    'itemName' => $student->name,
    'amount' => 100,
    'buttonText' => 'Donate Now'
])
```

### Auction Item
```blade
@include('components.add-to-cart-btn', [
    'itemType' => 'auction',
    'itemId' => $auction->id,
    'itemName' => $auction->name,
    'currentBid' => $auction->current_bid,
    'buttonText' => 'Add to Cart'
])
```

### Ticket
```blade
@include('components.add-to-cart-btn', [
    'itemType' => 'ticket',
    'itemId' => $ticket->id,
    'itemName' => $ticket->name,
    'price' => $ticket->price,
    'buttonText' => 'Buy Ticket'
])
```

### Product
```blade
@include('components.add-to-cart-btn', [
    'itemType' => 'product',
    'itemId' => $product->id,
    'itemName' => $product->name,
    'price' => $product->price,
    'buttonText' => 'Add to Cart'
])
```

---

## Troubleshooting Component Integration

**Q: Button doesn't appear**  
A: Check file path matches: `resources/views/components/add-to-cart-btn.blade.php`

**Q: Cart icon doesn't show**  
A: Verify `cart-drawer.blade.php` is included in main layout (check `resources/views/layouts/main.blade.php`)

**Q: JavaScript errors**  
A: Check browser console, verify `cart.js` loaded, check script path: `public/js/cart.js`

**Q: Button styling looks wrong**  
A: Check CSS file loaded: `public/css/cart.css`

**Q: Items don't add to cart**  
A: Check CSRF token in meta tag, verify `/api/cart` routes exist

---

## Next Steps

1. ✅ Framework created and tested
2. 📝 Identify components to update (see locations above)
3. 🔧 Add cart buttons to each component
4. ✅ Test add-to-cart functionality
5. 🛒 Test checkout flow
6. 🚀 Deploy to production

---

## Support

For questions or issues:
1. Check SHOPPING_CART_INTEGRATION_GUIDE.md
2. Review CART_SYSTEM_IMPLEMENTATION.md
3. Check browser console for JavaScript errors
4. Check Laravel logs: `storage/logs/`

