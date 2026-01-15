# Shopping Cart System - Implementation Plan

## Overview
A unified cart system that allows users to add multiple types of items (Students for donation, Tickets, Auction items, Products) to a cart, customize donation amounts for students, and checkout all items in a single transaction.

## Architecture

### 1. Data Model Strategy
- **Session-based Cart** (Primary): Stores cart in Laravel session for lightweight, no-database approach
- **Database Backup**: Optional Cart/CartItem models for persistence across sessions
- **Polymorphic Relationships**: Support different item types (Student, Ticket, Auction, Product)

### 2. Item Types Supported
1. **Students** - Custom donation amount per student
2. **Tickets** - Fixed price, quantity selector
3. **Auction Items** - Current bid price (fixed at checkout)
4. **Products** - Fixed price, quantity selector

### 3. Cart Structure (Session)
```json
{
  "cart": {
    "items": [
      {
        "id": "student_5",
        "type": "student",
        "name": "John Smith",
        "amount": 100,
        "photo_url": "...",
        "quantity": 1
      },
      {
        "id": "ticket_3",
        "type": "ticket",
        "name": "Event Ticket",
        "price": 50,
        "quantity": 2
      }
    ],
    "total": 200,
    "item_count": 3
  }
}
```

## Implementation Steps

### Phase 1: Backend Infrastructure
1. Create Cart API endpoints
2. Create CartService for business logic
3. Add cart middleware

### Phase 2: Frontend Components
1. Create floating cart icon button
2. Create cart drawer/modal
3. Add "Add to Cart" buttons to pages

### Phase 3: Checkout Integration
1. Create unified checkout controller
2. Integrate with existing PaymentFunnelService
3. Handle multi-type transactions

### Phase 4: Testing & Refinement
1. Test each item type
2. Test checkout flow
3. Test payment processing
4. Ensure no existing functionality broken

## Key Design Decisions

### Why Session-Based Cart?
- ✅ No database overhead
- ✅ Works immediately (no migrations needed)
- ✅ Can upgrade to database later without breaking existing code
- ✅ Follows common Laravel e-commerce pattern

### Handling Different Item Types
- Use type discriminator field
- Each type has different validation/pricing logic
- Controller routes to appropriate handler based on type

### Payment Integration
- Create CartCheckout controller
- Transform cart items into payment records
- Leverage existing PaymentFunnelService
- One payment can contain multiple item types

## Files to Create/Modify

### New Files
- `app/Services/CartService.php`
- `app/Http/Controllers/CartController.php`
- `app/Http/Controllers/CheckoutController.php`
- `resources/views/components/cart-button.blade.php`
- `resources/views/components/cart-drawer.blade.php`
- `public/js/cart.js`
- `public/css/cart.css`

### Modified Files
- `routes/web.php` (add cart routes)
- `routes/api.php` (add cart API routes)
- `resources/views/page-components/render-component.blade.php` (add cart button to student-listing)
- `app/Http/Controllers/AuthorizeNetController.php` (handle cart checkout)

## Database Approach (Optional - for persistence)

### Migrations
```sql
CREATE TABLE carts (
  id UUID PRIMARY KEY,
  user_id BIGINT,
  website_id BIGINT,
  session_id VARCHAR(255),
  total DECIMAL(10,2),
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

CREATE TABLE cart_items (
  id UUID PRIMARY KEY,
  cart_id UUID,
  itemable_type VARCHAR(255),
  itemable_id BIGINT,
  item_type ENUM('student','ticket','auction','product'),
  quantity INT DEFAULT 1,
  amount DECIMAL(10,2),
  custom_amount DECIMAL(10,2),
  created_at TIMESTAMP
);
```

But we'll start with SESSION approach for speed and simplicity!

## Security Considerations
- Validate all cart items before checkout
- Verify prices haven't changed
- CSRF protection on all routes
- Rate limiting on cart endpoints
- Validate website_id matches user's website

## Testing Checklist
- [ ] Add student to cart with custom amount
- [ ] Add ticket to cart with quantity
- [ ] Add auction to cart
- [ ] Add product to cart
- [ ] Update student amount
- [ ] Remove item from cart
- [ ] Clear entire cart
- [ ] View cart subtotal/total
- [ ] Checkout with mixed item types
- [ ] Payment processes correctly
- [ ] PaymentFunnelService tracks items
- [ ] Existing donation/ticket/product pages still work
- [ ] Cart persists across page navigation
- [ ] Cart icon shows count
- [ ] Mobile responsive

