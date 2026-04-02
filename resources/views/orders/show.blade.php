@extends('layout')

@section('title', 'Order #' . $sale->sale_id . ' - NoreXo')

@section('content')
<style>
    .order-detail-container {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 30px;
        margin: 30px 0;
    }

    .order-detail {
        background: white;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 30px;
    }

    .order-detail h2 {
        font-size: 20px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--primary);
    }

    .detail-section {
        margin-bottom: 30px;
    }

    .detail-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-light);
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .detail-value {
        font-size: 15px;
        color: var(--text);
        line-height: 1.6;
    }

    .order-timeline {
        margin: 30px 0;
    }

    .timeline-item {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }

    .timeline-icon {
        width: 40px;
        height: 40px;
        background: var(--primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        flex-shrink: 0;
    }

    .timeline-icon.completed {
        background: var(--success);
    }

    .timeline-icon.pending {
        background: #e5e7eb;
        color: var(--text-light);
    }

    .timeline-content {
        flex: 1;
    }

    .timeline-title {
        font-weight: 600;
        margin-bottom: 3px;
    }

    .timeline-date {
        font-size: 12px;
        color: var(--text-light);
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }

    .items-table th {
        background: var(--bg-light);
        padding: 12px;
        text-align: left;
        font-size: 13px;
        font-weight: 600;
        border-bottom: 2px solid var(--border);
    }

    .items-table td {
        padding: 12px;
        border-bottom: 1px solid var(--border);
        font-size: 14px;
    }

    .items-table tr:last-child td {
        border-bottom: none;
    }

    .product-link {
        color: var(--primary);
        text-decoration: none;
    }

    .product-link:hover {
        text-decoration: underline;
    }

    .order-sidebar {
        background: white;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        height: fit-content;
        position: sticky;
        top: 100px;
    }

    .sidebar-section {
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border);
    }

    .sidebar-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .sidebar-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-light);
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .sidebar-value {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary);
    }

    .status-badge {
        display: inline-block;
        padding: 8px 12px;
        background: var(--bg-light);
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
    }

    .status-badge.pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-badge.confirmed {
        background: #dbeafe;
        color: #0c4a6e;
    }

    .status-badge.shipped {
        background: #dbeafe;
        color: #0c4a6e;
    }

    .status-badge.delivered {
        background: #dcfce7;
        color: #166534;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .action-buttons .btn {
        flex: 1;
        padding: 10px;
        font-size: 13px;
    }

    @media (max-width: 768px) {
        .order-detail-container {
            grid-template-columns: 1fr;
        }

        .order-sidebar {
            position: static;
        }

        .items-table {
            font-size: 12px;
        }
    }
</style>

<h1 style="margin: 20px 0; font-size: 28px;">Order #{{ $sale->sale_id }}</h1>

<div class="order-detail-container">
    <!-- Order Details -->
    <div class="order-detail">
        <!-- Order Info -->
        <h2>Order Information</h2>
        <div class="detail-section">
            <div class="detail-label">Order ID</div>
            <div class="detail-value">#{{ $sale->sale_id }}</div>
        </div>
        <div class="detail-section">
            <div class="detail-label">Order Date</div>
            <div class="detail-value">{{ $sale->created_at->format('d M Y H:i') }}</div>
        </div>
        <div class="detail-section">
            <div class="detail-label">Status</div>
            <div class="detail-value">
                <span class="status-badge {{ $sale->status }}">
                    @if($sale->status === 'pending')
                        ⏳ Pending
                    @elseif($sale->status === 'confirmed')
                        ✓ Confirmed
                    @elseif($sale->status === 'shipped')
                        📦 Shipped
                    @elseif($sale->status === 'delivered')
                        ✓ Delivered
                    @elseif($sale->status === 'cancelled')
                        ✗ Cancelled
                    @endif
                </span>
            </div>
        </div>

        <!-- Delivery Info -->
        <h2 style="margin-top: 40px;">Delivery Address</h2>
        <div class="detail-section">
            <div class="detail-value">
                {{ $sale->delivery_address }}<br>
                {{ $sale->delivery_city }}, {{ $sale->delivery_province }}<br>
                {{ $sale->delivery_postal_code }}
            </div>
        </div>

        @if($sale->estimated_delivery)
            <div class="detail-section">
                <div class="detail-label">Estimated Delivery</div>
                <div class="detail-value">{{ $sale->estimated_delivery->format('d M Y') }}</div>
            </div>
        @endif

        @if($sale->delivered_at)
            <div class="detail-section">
                <div class="detail-label">Delivered At</div>
                <div class="detail-value">{{ $sale->delivered_at->format('d M Y H:i') }}</div>
            </div>
        @endif

        <!-- Order Timeline -->
        <h2 style="margin-top: 40px;">Order Timeline</h2>
        <div class="order-timeline">
            <div class="timeline-item">
                <div class="timeline-icon completed">✓</div>
                <div class="timeline-content">
                    <div class="timeline-title">Order Placed</div>
                    <div class="timeline-date">{{ $sale->created_at->format('d M Y H:i') }}</div>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-icon {{ in_array($sale->status, ['confirmed', 'shipped', 'delivered']) ? 'completed' : 'pending' }}">
                    {{ in_array($sale->status, ['confirmed', 'shipped', 'delivered']) ? '✓' : '' }}
                </div>
                <div class="timeline-content">
                    <div class="timeline-title">Order Confirmed</div>
                    <div class="timeline-date">{{ in_array($sale->status, ['confirmed', 'shipped', 'delivered']) ? $sale->updated_at->format('d M Y H:i') : 'Waiting...' }}</div>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-icon {{ in_array($sale->status, ['shipped', 'delivered']) ? 'completed' : 'pending' }}">
                    {{ in_array($sale->status, ['shipped', 'delivered']) ? '✓' : '' }}
                </div>
                <div class="timeline-content">
                    <div class="timeline-title">Shipped</div>
                    <div class="timeline-date">{{ in_array($sale->status, ['shipped', 'delivered']) ? $sale->updated_at->format('d M Y H:i') : 'Waiting...' }}</div>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-icon {{ $sale->status === 'delivered' ? 'completed' : 'pending' }}">
                    {{ $sale->status === 'delivered' ? '✓' : '' }}
                </div>
                <div class="timeline-content">
                    <div class="timeline-title">Delivered</div>
                    <div class="timeline-date">{{ $sale->status === 'delivered' ? $sale->delivered_at->format('d M Y H:i') : 'Waiting...' }}</div>
                </div>
            </div>
        </div>

        <!-- Items -->
        <h2 style="margin-top: 40px;">Order Items</h2>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->details as $detail)
                    <tr>
                        <td>
                            <a href="{{ route('products.show', $detail->product_id) }}" class="product-link">
                                {{ $detail->product->product_name }}
                            </a>
                        </td>
                        <td>{{ $detail->quantity }}</td>
                        <td>Rp {{ number_format($detail->product->price, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Reviews Section -->
        @if($sale->status === 'delivered')
            <h2 style="margin-top: 40px;">Product Reviews</h2>
            @if($sale->canReview())
                <p style="margin: 15px 0;">
                    <a href="{{ route('reviews.create', $sale->sale_id) }}" class="btn btn-primary" style="padding: 10px 20px;">✍️ Leave Review</a>
                </p>
            @elseif($sale->reviews()->count() > 0)
                @foreach($sale->reviews as $review)
                    <div style="border: 1px solid var(--border); border-radius: 6px; padding: 15px; margin-bottom: 10px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <strong>{{ $review->product->product_name }}</strong>
                            <span style="color: #fbbf24;">
                                @for($i = 1; $i <= 5; $i++)
                                    {{ $i <= $review->rating ? '★' : '☆' }}
                                @endfor
                            </span>
                        </div>
                        @if($review->comment)
                            <p>{{ $review->comment }}</p>
                        @endif
                    </div>
                @endforeach
            @endif
        @endif
    </div>

    <!-- Order Sidebar -->
    <div class="order-sidebar">
        <div class="sidebar-section">
            <div class="sidebar-title">Order Status</div>
            <div style="margin-top: 10px;">
                <span class="status-badge {{ $sale->status }}">
                    @if($sale->status === 'pending')
                        ⏳ Pending
                    @elseif($sale->status === 'confirmed')
                        ✓ Confirmed
                    @elseif($sale->status === 'shipped')
                        📦 Shipped
                    @elseif($sale->status === 'delivered')
                        ✓ Delivered
                    @elseif($sale->status === 'cancelled')
                        ✗ Cancelled
                    @endif
                </span>
            </div>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Order Total</div>
            <div class="sidebar-value">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</div>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Items</div>
            <div style="font-size: 16px; font-weight: 600;">{{ $sale->details()->sum('quantity') }} Item{{ $sale->details()->sum('quantity') !== 1 ? 's' : '' }}</div>
        </div>

        <div class="action-buttons">
            <a href="{{ route('orders.index') }}" class="btn btn-primary">← Back to Orders</a>
            @if(($sale->status === 'pending' || $sale->status === 'confirmed') && $sale->status !== 'cancelled')
                <form method="POST" action="{{ route('orders.cancel', $sale->sale_id) }}" style="flex: 1;">
                    @csrf
                    <button type="submit" class="btn btn-danger" style="width: 100%;" onclick="return confirm('Are you sure?');">Cancel Order</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
