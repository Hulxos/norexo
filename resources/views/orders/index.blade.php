@extends('layout')

@section('title', 'My Orders - NoreXo')

@section('content')
<style>
    .orders-container {
        margin: 30px 0;
    }

    .order-item {
        background: white;
        border: 1px solid var(--border);
        border-radius: 10px;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .order-header {
        background: var(--bg-light);
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--border);
    }

    .order-id {
        font-weight: 600;
        color: var(--text);
    }

    .order-date {
        font-size: 13px;
        color: var(--text-light);
    }

    .order-status {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
    }

    .status-confirmed {
        background-color: #dbeafe;
        color: #0c4a6e;
    }

    .status-shipped {
        background-color: #dbeafe;
        color: #0c4a6e;
    }

    .status-delivered {
        background-color: #dcfce7;
        color: #166534;
    }

    .status-cancelled {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .order-content {
        padding: 20px;
    }

    .order-items {
        margin-bottom: 15px;
    }

    .order-item-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        font-size: 14px;
        border-bottom: 1px solid var(--border);
    }

    .order-item-row:last-child {
        border-bottom: none;
    }

    .item-name {
        flex: 1;
    }

    .item-qty {
        color: var(--text-light);
        margin: 0 15px;
    }

    .item-price {
        color: var(--primary);
        font-weight: 600;
        text-align: right;
        min-width: 100px;
    }

    .order-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        border-top: 1px solid var(--border);
    }

    .order-total {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary);
    }

    .order-actions {
        display: flex;
        gap: 10px;
    }

    .no-orders {
        text-align: center;
        padding: 60px;
        background: white;
        border-radius: 10px;
        color: var(--text-light);
    }

    .no-orders h2 {
        color: var(--text);
        margin-bottom: 10px;
    }

    @media (max-width: 768px) {
        .order-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .order-item-row {
            flex-wrap: wrap;
        }

        .order-footer {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }

        .order-actions {
            width: 100%;
        }

        .order-actions .btn {
            flex: 1;
        }
    }
</style>

<h1 style="margin: 20px 0; font-size: 28px;">📦 My Orders</h1>

@if($orders->count() > 0)
    <div class="orders-container">
        @foreach($orders as $order)
            <div class="order-item">
                <div class="order-header">
                    <div>
                        <div class="order-id">#{{ $order->sale_id }} - Order Date: {{ $order->created_at->format('d M Y H:i') }}</div>
                    </div>
                    <span class="order-status status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                </div>

                <div class="order-content">
                    <div class="order-items">
                        @foreach($order->details as $detail)
                            <div class="order-item-row">
                                <span class="item-name">{{ $detail->product->product_name }}</span>
                                <span class="item-qty">x{{ $detail->quantity }}</span>
                                <span class="item-price">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div style="padding: 12px; background: var(--bg-light); border-radius: 6px; margin-bottom: 15px; font-size: 13px;">
                        <strong>Delivery to:</strong> {{ $order->delivery_city }}, {{ $order->delivery_province }}<br>
                        @if($order->estimated_delivery)
                            <strong>Estimated Delivery:</strong> {{ $order->estimated_delivery->format('d M Y') }}
                        @endif
                    </div>

                    <div class="order-footer">
                        <div class="order-total">Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                        <div class="order-actions">
                            <a href="{{ route('orders.show', $order->sale_id) }}" class="btn btn-primary" style="padding: 8px 15px; font-size: 12px;">View Details</a>
                            @if($order->status === 'pending' || $order->status === 'confirmed')
                                <form method="POST" action="{{ route('orders.cancel', $order->sale_id) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger" style="padding: 8px 15px; font-size: 12px;" onclick="return confirm('Are you sure you want to cancel this order?');">Cancel</button>
                                </form>
                            @endif
                            @if($order->status === 'delivered' && $order->canReview())
                                <a href="{{ route('reviews.create', $order->sale_id) }}" class="btn btn-success" style="padding: 8px 15px; font-size: 12px;">Leave Review</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Pagination -->
        {{ $orders->links() }}
    </div>
@else
    <div class="no-orders">
        <h2>No orders yet</h2>
        <p>Start shopping to place your first order!</p>
        <a href="{{ route('home') }}" class="btn btn-primary" style="margin-top: 15px;">Continue Shopping</a>
    </div>
@endif
@endsection
