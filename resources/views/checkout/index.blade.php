@extends('layout')

@section('title', 'Checkout - NoreXo')

@section('content')
<style>
    .checkout-container {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 30px;
        margin: 30px 0;
    }

    .checkout-form {
        background: white;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 30px;
    }

    .form-section {
        margin-bottom: 30px;
    }

    .form-section h3 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--primary);
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-size: 14px;
        font-weight: 500;
        color: var(--text);
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--border);
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
        box-sizing: border-box;
    }

    .form-group textarea {
        resize: vertical;
        min-height: 80px;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .checkout-summary {
        background: white;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        height: fit-content;
        position: sticky;
        top: 100px;
    }

    .summary-items {
        max-height: 300px;
        overflow-y: auto;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border);
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 13px;
    }

    .summary-item-name {
        flex: 1;
    }

    .summary-item-price {
        color: var(--primary);
        font-weight: 600;
    }

    .summary-total {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 20px;
        text-align: center;
        padding: 15px 0;
        border-top: 2px solid var(--primary);
    }

    .balance-info {
        background: var(--bg-light);
        border: 1px solid var(--border);
        border-radius: 6px;
        padding: 12px;
        margin-bottom: 15px;
        font-size: 13px;
    }

    .balance-label {
        color: var(--text-light);
        margin-bottom: 5px;
    }

    .balance-amount {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary);
    }

    .insufficient {
        background-color: #fee2e2;
        border-color: var(--danger);
    }

    .insufficient .balance-amount {
        color: var(--danger);
    }

    .checkout-btn {
        width: 100%;
        padding: 12px;
        font-size: 16px;
    }

    .error-message {
        background-color: #fee2e2;
        color: #991b1b;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 20px;
        font-size: 13px;
    }

    @media (max-width: 768px) {
        .checkout-container {
            grid-template-columns: 1fr;
        }

        .checkout-summary {
            position: static;
        }

        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<h1 style="margin: 20px 0; font-size: 28px;">Checkout</h1>

<form method="POST" action="{{ route('checkout.process') }}" class="checkout-container">
    @csrf

    <!-- Checkout Form -->
    <div class="checkout-form">
        <!-- Delivery Address -->
        <div class="form-section">
            <h3>Delivery Address</h3>

            <div class="form-group">
                <label>Full Address *</label>
                <textarea name="delivery_address" required placeholder="Enter your full delivery address">{{ old('delivery_address', auth()->user()->address) }}</textarea>
                @error('delivery_address')<span style="color: var(--danger); font-size: 12px;">{{ $message }}</span>@enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>City *</label>
                    <input type="text" name="delivery_city" required placeholder="e.g., Jakarta" value="{{ old('delivery_city', auth()->user()->city) }}">
                    @error('delivery_city')<span style="color: var(--danger); font-size: 12px;">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Province *</label>
                    <input type="text" name="delivery_province" required placeholder="e.g., DKI Jakarta" value="{{ old('delivery_province', auth()->user()->province) }}">
                    @error('delivery_province')<span style="color: var(--danger); font-size: 12px;">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-group">
                <label>Postal Code *</label>
                <input type="text" name="delivery_postal_code" required placeholder="e.g., 12000" value="{{ old('delivery_postal_code', auth()->user()->postal_code) }}">
                @error('delivery_postal_code')<span style="color: var(--danger); font-size: 12px;">{{ $message }}</span>@enderror
            </div>

            <div style="margin-top: 15px; padding: 12px; background: var(--bg-light); border-radius: 6px; font-size: 13px; color: var(--text-light);">
                <strong>Note:</strong> Your balance will be deducted during checkout. Delivery time will be automatically calculated after order confirmation.
            </div>
        </div>
    </div>

    <!-- Checkout Summary -->
    <div class="checkout-summary">
        <h3 style="margin-bottom: 15px; font-size: 16px;">Order Summary</h3>

        <!-- Items -->
        <div class="summary-items">
            @foreach($cartItems as $item)
                <div class="summary-item">
                    <span class="summary-item-name">
                        {{ substr($item->product->product_name, 0, 20) }}...
                        <br><span style="color: var(--text-light);">x{{ $item->quantity }}</span>
                    </span>
                    <span class="summary-item-price">Rp {{ number_format($item->getSubtotal(), 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>

        <!-- Total -->
        <div class="summary-total">
            Rp {{ number_format($total, 0, ',', '.') }}
        </div>

        <!-- Balance Info -->
        @php
            $balance = auth()->user()->balance;
            $canAfford = $balance >= $total;
        @endphp

        <div class="balance-info {{ !$canAfford ? 'insufficient' : '' }}">
            <div class="balance-label">Your Balance</div>
            <div class="balance-amount">Rp {{ number_format($balance, 0, ',', '.') }}</div>
        </div>

        @if(!$canAfford)
            <div class="error-message">
                ⚠️ Insufficient balance! You need Rp {{ number_format($total - $balance, 0, ',', '.') }} more.
                <a href="{{ route('topup.create') }}" style="color: var(--danger); text-decoration: underline;">Top up now</a>
            </div>
            <button type="button" class="btn btn-danger checkout-btn" disabled>Insufficient Balance</button>
        @else
            <button type="submit" class="btn btn-success checkout-btn">✓ Place Order</button>
        @endif
    </div>
</form>
@endsection
