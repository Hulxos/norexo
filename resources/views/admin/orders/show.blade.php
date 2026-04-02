@extends('layout')

@section('title', 'Order #' . $sale->sale_id . ' - NoreXo Admin')

@section('content')
<style>
    .order-admin-container {
        display: grid;
        grid-template-columns: 1fr 300px;
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
        font-size: 18px;
        margin: 25px 0 15px 0;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--border);
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid var(--border);
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: var(--text);
    }

    .detail-value {
        color: var(--primary);
        font-weight: 600;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin: 15px 0;
    }

    .items-table th,
    .items-table td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid var(--border);
    }

    .items-table th {
        background: var(--bg-light);
        font-weight: 600;
        font-size: 12px;
    }

    .admin-sidebar {
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
    }

    .sidebar-label {
        font-size: 12px;
        color: var(--text-light);
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .sidebar-value {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary);
    }

    .status-form {
        margin: 15px 0;
    }

    .status-form select {
        width: 100%;
        padding: 8px;
        border: 1px solid var(--border);
        border-radius: 6px;
        margin-bottom: 10px;
    }

    .status-form button {
        width: 100%;
        padding: 10px;
    }
</style>

<div class="order-admin-container">
    <!-- Order Details -->
    <div class="order-detail">
        <h1 style="font-size: 24px; margin-bottom: 20px;">Order #{{ $sale->sale_id }}</h1>

        <h2>Customer Information</h2>
        <div class="detail-row">
            <span class="detail-label">Name</span>
            <span>{{ $sale->user->name }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Email</span>
            <span>{{ $sale->user->email }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Phone</span>
            <span>{{ $sale->user->phone ?? '-' }}</span>
        </div>

        <h2>Delivery Address</h2>
        <div style="padding: 12px; background: var(--bg-light); border-radius: 6px; margin: 15px 0;">
            {{ $sale->delivery_address }}<br>
            {{ $sale->delivery_city }}, {{ $sale->delivery_province }}<br>
            {{ $sale->delivery_postal_code }}
        </div>

        @if($sale->estimated_delivery)
            <div class="detail-row">
                <span class="detail-label">Estimated Delivery</span>
                <span>{{ $sale->estimated_delivery->format('d M Y') }}</span>
            </div>
        @endif

        <h2>Order Items</h2>
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
                        <td>{{ $detail->product->product_name }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>Rp {{ number_format($detail->product->price, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Admin Sidebar -->
    <div class="admin-sidebar">
        <div class="sidebar-section">
            <div class="sidebar-label">Order Total</div>
            <div class="sidebar-value">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</div>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-label">Current Status</div>
            <div style="padding: 8px; background: var(--bg-light); border-radius: 6px; font-weight: 600; text-align: center;">
                {{ ucfirst($sale->status) }}
            </div>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-label">Update Status</div>
            <form method="POST" action="{{ route('admin.orders.updateStatus', $sale->sale_id) }}" class="status-form">
                @csrf
                @method('PATCH')
                
                <select name="status" required>
                    <option value="">Select new status...</option>
                    @if($sale->status === 'pending')
                        <option value="confirmed">Confirm Order</option>
                    @elseif($sale->status === 'confirmed')
                        <option value="shipped">Mark as Shipped</option>
                    @elseif($sale->status === 'shipped')
                        <option value="delivered">Mark as Delivered</option>
                    @endif
                </select>
                
                <button type="submit" class="btn btn-success">Update</button>
            </form>
        </div>

        <div class="sidebar-section">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary" style="display: block; text-align: center; text-decoration: none; width: 100%;">← Back to Orders</a>
        </div>
    </div>
</div>
@endsection
