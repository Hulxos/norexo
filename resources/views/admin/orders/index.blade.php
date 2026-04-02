@extends('layout')

@section('title', 'Manage Orders - NoreXo Admin')

@section('content')
<style>
    .orders-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 30px 0;
        flex-wrap: wrap;
        gap: 15px;
    }

    .filter-form {
        display: flex;
        gap: 10px;
    }

    .filter-form select,
    .filter-form button {
        padding: 8px 12px;
        border: 1px solid var(--border);
        border-radius: 6px;
        font-size: 13px;
    }

    .orders-table {
        width: 100%;
        background: white;
        border: 1px solid var(--border);
        border-collapse: collapse;
        border-radius: 10px;
        overflow: hidden;
    }

    .orders-table th {
        background: var(--bg-light);
        padding: 12px;
        text-align: left;
        font-weight: 600;
        border-bottom: 2px solid var(--border);
        font-size: 12px;
        text-transform: uppercase;
    }

    .orders-table td {
        padding: 12px;
        border-bottom: 1px solid var(--border);
        font-size: 13px;
    }

    .order-id {
        font-weight: 600;
        color: var(--primary);
    }

    .order-amount {
        color: var(--success);
        font-weight: 600;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-confirmed {
        background: #dbeafe;
        color: #0c4a6e;
    }

    .status-shipped {
        background: #dbeafe;
        color: #0c4a6e;
    }

    .status-delivered {
        background: #dcfce7;
        color: #166534;
    }

    .table-actions {
        display: flex;
        gap: 5px;
    }

    .table-actions a {
        padding: 4px 8px;
        text-decoration: none;
        border-radius: 4px;
        background: var(--bg-light);
        color: var(--primary);
        font-size: 11px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .table-actions a:hover {
        background: var(--primary);
        color: white;
    }
</style>

<div class="orders-header">
    <h1 style="font-size: 24px;">📦 Manage Orders</h1>

    <form method="GET" action="{{ route('admin.orders.index') }}" class="filter-form">
        <select name="status">
            <option value="">All Orders</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
            <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
            <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
        </select>
        <button type="submit" class="btn btn-primary" style="padding: 8px 15px;">Filter</button>
    </form>
</div>

@if($orders->count() > 0)
    <table class="orders-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Items</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Order Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td class="order-id">#{{ $order->sale_id }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ $order->details()->sum('quantity') }}</td>
                    <td class="order-amount">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td>
                        <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                    </td>
                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('admin.orders.show', $order->sale_id) }}">View</a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $orders->links() }}
@else
    <div style="text-align: center; padding: 60px; background: white; border-radius: 10px; color: var(--text-light);">
        <h2 style="color: var(--text); margin-bottom: 10px;">No orders found</h2>
    </div>
@endif
@endsection
