@extends('layout')

@section('title', 'Shopping Cart - NoreXo')

@section('content')
<style>
    .cart-container {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 30px;
        margin: 30px 0;
    }

    .cart-items {
        background: white;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 0;
    }

    .cart-header {
        padding: 20px;
        border-bottom: 2px solid var(--primary);
        font-size: 20px;
        font-weight: 700;
    }

    .cart-item {
        display: flex;
        gap: 20px;
        padding: 20px;
        border-bottom: 1px solid var(--border);
        align-items: center;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .item-image {
        width: 100px;
        height: 100px;
        background: var(--bg-light);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        overflow: hidden;
        flex-shrink: 0;
    }

    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .item-details {
        flex: 1;
    }

    .item-name {
        font-weight: 600;
        font-size: 16px;
        margin-bottom: 8px;
    }

    .item-price {
        color: var(--primary);
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 12px;
    }

    .item-quantity {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }

    .quantity-field {
        display: flex;
        align-items: center;
        border: 1px solid var(--border);
        border-radius: 4px;
    }

    .quantity-field button {
        padding: 4px 8px;
        border: none;
        background: none;
        cursor: pointer;
        font-size: 14px;
    }

    .quantity-field input {
        width: 40px;
        text-align: center;
        border: none;
        font-size: 14px;
    }

    .item-total {
        font-weight: 600;
        color: var(--text);
    }

    .item-actions {
        display: flex;
        gap: 10px;
    }

    .item-actions form {
        display: inline;
    }

    .cart-summary {
        background: white;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        height: fit-content;
        position: sticky;
        top: 100px;
    }

    .summary-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 20px;
        text-align: center;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid var(--border);
        font-size: 14px;
    }

    .summary-row.total {
        border: none;
        border-top: 2px solid var(--primary);
        padding-top: 15px;
        padding-bottom: 0;
        font-size: 18px;
        font-weight: 700;
        color: var(--primary);
        margin-top: 15px;
    }

    .checkout-btn {
        width: 100%;
        margin-top: 20px;
        padding: 12px;
    }

    .clear-cart-btn {
        width: 100%;
        margin-top: 10px;
        background-color: var(--border);
        color: var(--text);
    }

    .clear-cart-btn:hover {
        background-color: #d1d5db;
    }

    .empty-cart {
        text-align: center;
        padding: 60px;
        color: var(--text-light);
    }

    .empty-cart h2 {
        font-size: 24px;
        margin-bottom: 10px;
        color: var(--text);
    }

    .empty-cart p {
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .cart-container {
            grid-template-columns: 1fr;
        }

        .cart-summary {
            position: static;
        }

        .item-quantity {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<h1 style="margin: 20px 0; font-size: 28px;">🛒 Shopping Cart</h1>

@if($cartItems->count() > 0)
    <div class="cart-container">
        <!-- Cart Items -->
        <div class="cart-items">
            <div class="cart-header">
                {{ $cartItems->count() }} Item{{ $cartItems->count() !== 1 ? 's' : '' }}
            </div>

            @foreach($cartItems as $item)
                <div class="cart-item">
                    <div class="item-image">
                        @if($item->product->image_path)
                            <img src="{{ asset('storage/' . $item->product->image_path) }}" alt="{{ $item->product->product_name }}">
                        @else
                            📦
                        @endif
                    </div>
                    <div class="item-details">
                        <div class="item-name">{{ $item->product->product_name }}</div>
                        <div class="item-price">Rp {{ number_format($item->product->price, 0, ',', '.') }}</div>
                        
                        <div class="item-quantity">
                            <span>Qty:</span>
                            <form method="POST" action="{{ route('cart.update', $item) }}" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <div class="quantity-field">
                                    <button type="button" onclick="this.parentElement.querySelector('input').value--; this.form.submit();">−</button>
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}">
                                    <button type="button" onclick="this.parentElement.querySelector('input').value++; this.form.submit();">+</button>
                                </div>
                            </form>
                        </div>

                        <div class="item-total">
                            Total: Rp {{ number_format($item->getSubtotal(), 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="item-actions">
                        <form method="POST" action="{{ route('cart.delete', $item) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 8px 12px; font-size: 12px;">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Cart Summary -->
        <div class="cart-summary">
            <div class="summary-title">Summary</div>

            <div class="summary-row">
                <span>Subtotal:</span>
                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <div class="summary-row">
                <span>Shipping:</span>
                <span>Free</span>
            </div>

            <div class="summary-row">
                <span>Discount:</span>
                <span>-</span>
            </div>

            <div class="summary-row total">
                <span>Total:</span>
                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <a href="{{ route('checkout.index') }}" class="btn btn-success checkout-btn">Proceed to Checkout</a>

            <form method="POST" action="{{ route('cart.clear') }}">
                @csrf
                <button type="submit" class="btn clear-cart-btn" onclick="return confirm('Are you sure you want to clear your cart?');">Clear Cart</button>
            </form>

            <a href="{{ route('home') }}" class="btn" style="width: 100%; margin-top: 10px; text-align: center; background-color: var(--bg-light); color: var(--text);">Continue Shopping</a>
        </div>
    </div>
@else
    <div class="empty-cart">
        <h2>Your cart is empty</h2>
        <p>Start shopping to add items to your cart!</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Continue Shopping</a>
    </div>
@endif
@endsection
