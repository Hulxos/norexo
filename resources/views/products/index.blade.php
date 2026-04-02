@extends('layout')

@php
    /** @var \Illuminate\Pagination\LengthAwarePaginator $products */
    /** @var \Illuminate\Support\Collection $categories */
@endphp

@section('title', 'Home - NoreXo')

@section('content')
<style>
    .hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 60px 0;
        margin: 20px 0;
        border-radius: 12px;
        text-align: center;
    }

    .hero h1 {
        font-size: 48px;
        margin-bottom: 20px;
        font-weight: 700;
    }

    .hero p {
        font-size: 18px;
        margin-bottom: 30px;
    }

    .filters {
        display: flex;
        gap: 15px;
        margin: 30px 0;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .filter-group label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
    }

    .filter-group input,
    .filter-group select {
        padding: 8px 12px;
        border: 1px solid var(--border);
        border-radius: 6px;
        font-size: 13px;
        font-family: inherit;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
        margin: 30px 0;
    }

    .product-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transform: translateY(-5px);
    }

    .product-image {
        width: 100%;
        height: 200px;
        background: var(--bg-light);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 60px;
        overflow: hidden;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-info {
        padding: 15px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .product-name {
        font-weight: 600;
        font-size: 15px;
        margin-bottom: 8px;
        line-height: 1.3;
    }

    .product-price {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 8px;
    }

    .product-rating {
        font-size: 13px;
        color: var(--text-light);
        margin-bottom: 8px;
    }

    .stars {
        color: #fbbf24;
    }

    .product-stock {
        font-size: 12px;
        color: var(--text-light);
        margin-bottom: 12px;
    }

    .stock-low {
        color: var(--danger);
        font-weight: 600;
    }

    .product-actions {
        display: flex;
        gap: 8px;
    }

    .product-actions .btn {
        flex: 1;
        padding: 8px 12px;
        font-size: 12px;
        text-align: center;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin: 40px 0;
    }

    .pagination a,
    .pagination span {
        padding: 8px 12px;
        border: 1px solid var(--border);
        border-radius: 6px;
        text-decoration: none;
        color: var(--text);
        font-size: 13px;
    }

    .pagination a.active,
    .pagination span.active {
        background-color: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .no-products {
        text-align: center;
        padding: 60px 0;
        color: var(--text-light);
    }

    .no-products h2 {
        font-size: 24px;
        margin-bottom: 10px;
        color: var(--text);
    }
</style>

<!-- Hero Section -->
<div class="hero">
    <h1>Welcome to NoreXo</h1>
    <p>Your trusted online shopping destination</p>
</div>

<!-- Filters -->
<form method="GET" action="{{ route('home') }}" class="filters">
    <div class="filter-group" style="flex: 1; min-width: 200px;">
        <label>Search Products</label>
        <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}">
    </div>

    <div class="filter-group">
        <label>Category</label>
        <select name="category">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat ?? 'Uncategorized' }}</option>
            @endforeach
        </select>
    </div>

    <div class="filter-group">
        <label>Min Price</label>
        <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}">
    </div>

    <div class="filter-group">
        <label>Max Price</label>
        <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}">
    </div>

    <div class="filter-group">
        <label>Sort</label>
        <select name="sort">
            <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest</option>
            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
            <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
        </select>
    </div>

    <div class="filter-group" style="margin-top: auto;">
        <button type="submit" class="btn btn-primary">Filter</button>
    </div>
</form>

<!-- Products Grid -->
@if($products->count() > 0)
    <div class="products-grid">
        @foreach($products as $product)
            <div class="product-card" data-turbo-frame="product-{{ $product->product_id }}">
                <div class="product-image">
                    @if($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->product_name }}">
                    @else
                        📦
                    @endif
                </div>
                <div class="product-info">
                    <h3 class="product-name">{{ $product->product_name }}</h3>
                    <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    <div class="product-rating">
                        <span class="stars">★★★★★</span> {{ $product->getAverageRating() }} ({{ $product->reviews()->count() }})
                    </div>
                    <div class="product-stock">
                        @if($product->stock > 20)
                            <span>{{ $product->stock }} in stock</span>
                        @elseif($product->stock > 0)
                            <span class="stock-low">Only {{ $product->stock }} left!</span>
                        @else
                            <span class="stock-low">Out of stock</span>
                        @endif
                    </div>
                    <div class="product-actions">
                        <a href="{{ route('products.show', $product->product_id) }}" class="btn btn-primary">View</a>
                        @auth
                            @if(auth()->user()->isBuyer() && $product->stock > 0)
                                <form method="POST" action="{{ route('cart.add') }}" style="flex: 1;" class="quick-add-form">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-success quick-add-btn" style="width: 100%; padding: 8px;" data-product="{{ $product->product_name }}">Add</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="pagination">
        {{ $products->links() }}
    </div>
@else
    <div class="no-products">
        <h2>No products found</h2>
        <p>Try adjusting your search or filter criteria.</p>
    </div>
@endif

<style>
    .quick-add-btn {
        transition: all 0.3s ease;
    }

    .quick-add-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .quick-add-btn.adding {
        opacity: 0.7;
        pointer-events: none;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.quick-add-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btn = this.querySelector('.quick-add-btn');
            const productName = btn.dataset.product;
            const productId = this.querySelector('input[name="product_id"]').value;
            
            btn.classList.add('adding');
            btn.textContent = '⏳';
            
            try {
                const response = await fetch('{{ route("cart.add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1
                    })
                });

                if (response.ok) {
                    // Show success notification
                    showNotification(`✓ ${productName} added to cart!`, 'success');
                    
                    // Update cart count
                    updateCartCount();
                    
                    // Reset button
                    btn.textContent = 'Add';
                } else {
                    showNotification('Failed to add item', 'error');
                    btn.textContent = 'Add';
                }
            } catch (error) {
                showNotification('Error adding to cart', 'error');
                btn.textContent = 'Add';
            } finally {
                btn.classList.remove('adding');
            }
        });
    });
});

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 80px;
        right: 20px;
        background: ${type === 'success' ? 'var(--success)' : 'var(--danger)'};
        color: white;
        padding: 16px 24px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        font-weight: 500;
        z-index: 1000;
        animation: slideInRight 0.3s ease-out, slideOutRight 0.3s ease-out 2.7s forwards;
    `;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => notification.remove(), 3000);
}

function updateCartCount() {
    @auth
        @if(auth()->user()->isBuyer())
            fetch('{{ route("cart.count") }}')
                .then(r => r.json())
                .then(d => {
                    const badge = document.getElementById('cart-count');
                    if (badge) {
                        badge.textContent = d.count;
                        badge.style.animation = 'none';
                        setTimeout(() => {
                            badge.style.animation = 'pulse 0.5s ease-out';
                        }, 10);
                    }
                });
        @endif
    @endauth
}
</script>

@endsection
