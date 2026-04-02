@extends('layout')

@section('title', $product->product_name . ' - NoreXo')

@section('content')
<style>
    .product-detail {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        margin: 30px 0;
        background: white;
        padding: 30px;
        border-radius: 12px;
        border: 1px solid var(--border);
    }

    .product-image-large {
        width: 100%;
        height: 500px;
        background: var(--bg-light);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 120px;
        border-radius: 10px;
        overflow: hidden;
    }

    .product-image-large img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-details {
        display: flex;
        flex-direction: column;
    }

    .product-title {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 20px;
        color: var(--text);
    }

    .product-rating {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .stars {
        color: #fbbf24;
        font-size: 18px;
    }

    .review-count {
        color: var(--text-light);
        font-size: 14px;
    }

    .product-price-section {
        padding: 20px 0;
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
        margin-bottom: 20px;
    }

    .product-price-large {
        font-size: 40px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 10px;
    }

    .product-category {
        font-size: 14px;
        color: var(--text-light);
        margin-bottom: 10px;
    }

    .product-stock-info {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 16px;
        font-weight: 600;
    }

    .stock-status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 13px;
    }

    .stock-available {
        background-color: #dcfce7;
        color: #166534;
    }

    .stock-low {
        background-color: #fef3c7;
        color: #92400e;
    }

    .stock-out {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .product-description {
        margin: 20px 0;
        line-height: 1.6;
        color: var(--text);
    }

    .add-to-cart-form {
        display: flex;
        gap: 15px;
        margin: 20px 0;
    }

    .quantity-selector {
        display: flex;
        align-items: center;
        border: 1px solid var(--border);
        border-radius: 6px;
        width: fit-content;
    }

    .quantity-selector button {
        padding: 10px 15px;
        border: none;
        background: none;
        cursor: pointer;
        font-size: 16px;
        transition: background 0.2s;
    }

    .quantity-selector button:hover {
        background: var(--bg-light);
    }

    .quantity-selector input {
        width: 50px;
        text-align: center;
        border: none;
        font-size: 16px;
    }

    .add-cart-btn {
        flex: 1;
        padding: 12px 20px;
        font-size: 16px;
    }

    .reviews-section {
        margin-top: 60px;
    }

    .reviews-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid var(--primary);
    }

    .reviews-header h2 {
        font-size: 24px;
        color: var(--text);
    }

    .review-item {
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .review-user {
        font-weight: 600;
        color: var(--text);
    }

    .review-date {
        color: var(--text-light);
        font-size: 13px;
    }

    .review-rating {
        color: #fbbf24;
        margin-bottom: 10px;
    }

    .review-comment {
        color: var(--text);
        line-height: 1.6;
    }

    .related-products {
        margin-top: 60px;
    }

    .related-products h2 {
        font-size: 24px;
        margin-bottom: 20px;
        border-bottom: 2px solid var(--primary);
        padding-bottom: 15px;
    }

    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 15px;
    }

    .related-card {
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 15px;
        text-align: center;
    }

    .related-card-image {
        width: 100%;
        height: 150px;
        background: var(--bg-light);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 50px;
        border-radius: 6px;
        margin-bottom: 10px;
    }

    .related-card a {
        text-decoration: none;
        color: var(--text);
        font-weight: 600;
        font-size: 13px;
    }

    .related-card-price {
        color: var(--primary);
        font-weight: 700;
        margin: 10px 0;
    }

    @media (max-width: 768px) {
        .product-detail {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .add-to-cart-form {
            flex-direction: column;
        }
    }
</style>

<!-- Product Detail -->
<div class="product-detail">
    <!-- Image -->
    <div>
        <div class="product-image-large">
            @if($product->image_path)
                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->product_name }}">
            @else
                📦
            @endif
        </div>
    </div>

    <!-- Details -->
    <div class="product-details">
        <h1 class="product-title">{{ $product->product_name }}</h1>

        <div class="product-rating">
            <span class="stars">★★★★★</span>
            <span>{{ $product->getAverageRating() }}/5</span>
            <span class="review-count">({{ $product->reviews()->count() }} reviews)</span>
        </div>

        <div class="product-price-section">
            <div class="product-price-large">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
            @if($product->category)
                <div class="product-category">Category: <strong>{{ $product->category }}</strong></div>
            @endif
            <div class="product-stock-info">
                Stock:
                @if($product->stock > 20)
                    <span class="stock-status stock-available">{{ $product->stock }} Available</span>
                @elseif($product->stock > 0)
                    <span class="stock-status stock-low">Only {{ $product->stock }} left!</span>
                @else
                    <span class="stock-status stock-out">Out of Stock</span>
                @endif
            </div>
        </div>

        @if($product->description)
            <div class="product-description">
                <h3 style="margin-bottom: 10px;">Description</h3>
                {{ $product->description }}
            </div>
        @endif

        <!-- Add to Cart -->
        @auth
            @if(auth()->user()->isBuyer())
                <form method="POST" action="{{ route('cart.add') }}" class="add-to-cart-form" id="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                    
                    @if($product->stock > 0)
                        <div class="quantity-selector">
                            <button type="button" onclick="decreaseQty()">−</button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}">
                            <button type="button" onclick="increaseQty({{ $product->stock }})">+</button>
                        </div>
                        <button type="submit" class="btn btn-primary add-cart-btn" id="add-cart-btn">🛒 Add to Cart</button>
                    @else
                        <button type="button" class="btn btn-danger add-cart-btn" disabled>Out of Stock</button>
                    @endif
                </form>
            @endif
        @else
            <div style="padding: 20px; background: var(--bg-light); border-radius: 6px; text-align: center;">
                <p><a href="{{ route('login') }}" class="btn btn-primary">Login to shop</a></p>
            </div>
        @endauth
    </div>
</div>

<!-- Reviews Section -->
<div class="reviews-section">
    <div class="reviews-header">
        <h2>Customer Reviews</h2>
    </div>

    @if($reviews->count() > 0)
        @foreach($reviews as $review)
            <div class="review-item">
                <div class="review-header">
                    <span class="review-user">{{ $review->user->name }}</span>
                    <span class="review-date">{{ $review->created_at->diffForHumans() }}</span>
                </div>
                <div class="review-rating">
                    @for($i = 1; $i <= 5; $i++)
                        <span style="color: {{ $i <= $review->rating ? '#fbbf24' : '#d1d5db' }};">★</span>
                    @endfor
                    <span style="margin-left: 10px; color: var(--text);">{{ $review->rating }}/5</span>
                </div>
                @if($review->comment)
                    <div class="review-comment">{{ $review->comment }}</div>
                @endif
            </div>
        @endforeach

        <!-- Review Pagination -->
        <div style="margin-top: 20px;">
            {{ $reviews->links() }}
        </div>
    @else
        <p style="color: var(--text-light); text-align: center; padding: 20px;">No reviews yet. Be the first to review!</p>
    @endif
</div>

<!-- Related Products -->
@if($relatedProducts->count() > 0)
    <div class="related-products">
        <h2>Related Products</h2>
        <div class="related-grid">
            @foreach($relatedProducts as $related)
                <div class="related-card">
                    <div class="related-card-image">
                        @if($related->image_path)
                            <img src="{{ asset('storage/' . $related->image_path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            📦
                        @endif
                    </div>
                    <a href="{{ route('products.show', $related->product_id) }}">{{ $related->product_name }}</a>
                    <div class="related-card-price">Rp {{ number_format($related->price, 0, ',', '.') }}</div>
                </div>
            @endforeach
        </div>
    </div>
@endif

<style>
    .add-cart-notification {
        position: fixed;
        top: 80px;
        right: 20px;
        background: var(--success);
        color: white;
        padding: 16px 24px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 500;
        z-index: 1000;
        animation: slideInRight 0.3s ease-out, slideOutRight 0.3s ease-out 2.7s forwards;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }

    .add-cart-btn {
        transition: all 0.3s ease;
    }

    .add-cart-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .add-cart-btn.loading {
        opacity: 0.7;
        pointer-events: none;
    }
</style>

<script>
function decreaseQty() {
    const input = document.getElementById('quantity');
    if (input.value > 1) {
        input.value--;
    }
}

function increaseQty(max) {
    const input = document.getElementById('quantity');
    if (input.value < max) {
        input.value++;
    }
}

// Smooth cart add with AJAX
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('add-to-cart-form');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('add-cart-btn');
            const quantity = document.getElementById('quantity').value;
            const productId = form.querySelector('input[name="product_id"]').value;
            
            btn.classList.add('loading');
            btn.textContent = '⏳ Adding...';
            
            try {
                const response = await fetch('{{ route("cart.add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity
                    })
                });

                if (response.ok) {
                    const data = await response.json();
                    
                    // Show success notification
                    showAddedNotification(data.product_name || 'Item', quantity);
                    
                    // Update cart count
                    fetch('{{ route("cart.count") }}')
                        .then(r => r.json())
                        .then(d => {
                            const badge = document.getElementById('cart-count');
                            if (badge) {
                                badge.textContent = d.count;
                                badge.style.animation = 'pulse 0.5s ease-out';
                            }
                        });
                    
                    // Reset form
                    document.getElementById('quantity').value = 1;
                } else {
                    showErrorNotification('Failed to add item');
                }
            } catch (error) {
                showErrorNotification('Error adding to cart');
            } finally {
                btn.classList.remove('loading');
                btn.textContent = '🛒 Add to Cart';
            }
        });
    }
});

function showAddedNotification(productName, quantity) {
    const notification = document.createElement('div');
    notification.className = 'add-cart-notification';
    notification.innerHTML = `
        <span>✓</span>
        <span>${quantity}x ${productName} added to cart!</span>
    `;
    document.body.appendChild(notification);
    
    // Remove after animation completes
    setTimeout(() => notification.remove(), 3000);
}

function showErrorNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'add-cart-notification';
    notification.style.background = 'var(--danger)';
    notification.innerHTML = `
        <span>✕</span>
        <span>${message}</span>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => notification.remove(), 3000);
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); }
}
</script>
@endsection
