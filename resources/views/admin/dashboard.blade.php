@extends('layout')

@section('title', 'Admin Dashboard - NoreXo')

@section('content')
<style>
    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 30px 0;
        padding-bottom: 20px;
        border-bottom: 2px solid var(--primary);
    }

    .admin-header h1 {
        font-size: 28px;
    }

    .admin-nav {
        display: flex;
        gap: 15px;
    }

    .admin-nav a {
        padding: 10px 15px;
        background: var(--bg-light);
        border-radius: 6px;
        text-decoration: none;
        color: var(--text);
        font-size: 13px;
        border: 1px solid var(--border);
        transition: all 0.2s;
    }

    .admin-nav a:hover,
    .admin-nav a.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin: 30px 0;
    }

    .stat-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 20px;
        text-align: center;
    }

    .stat-icon {
        font-size: 36px;
        margin-bottom: 10px;
    }

    .stat-label {
        font-size: 13px;
        color: var(--text-light);
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--primary);
    }

    .section {
        background: white;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 20px;
        margin: 30px 0;
    }

    .section h2 {
        font-size: 20px;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--border);
    }

    .product-list,
    .order-list {
        margin-top: 15px;
    }

    .product-item,
    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px;
        border: 1px solid var(--border);
        border-radius: 6px;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .product-item-info {
        flex: 1;
    }

    .product-item-name {
        font-weight: 600;
        margin-bottom: 3px;
    }

    .product-item-meta {
        color: var(--text-light);
        font-size: 12px;
    }

    .product-item-action {
        text-align: right;
        min-width: 100px;
        font-weight: 600;
        color: var(--primary);
    }

    .no-data {
        text-align: center;
        padding: 30px;
        color: var(--text-light);
    }

    @media (max-width: 768px) {
        .admin-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .admin-nav {
            flex-wrap: wrap;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="admin-header">
    <h1>📊 Admin Dashboard</h1>
    <div class="admin-nav">
        <a href="{{ route('admin.dashboard') }}" class="active">Dashboard</a>
        <a href="{{ route('admin.products.index') }}">Products</a>
        <a href="{{ route('admin.orders.index') }}">Orders</a>
        <a href="{{ route('admin.analytics') }}">Analytics</a>
    </div>
</div>

<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">📦</div>
        <div class="stat-label">Total Products</div>
        <div class="stat-value">{{ $totalProducts }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">💰</div>
        <div class="stat-label">Total Revenue</div>
        <div class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📋</div>
        <div class="stat-label">Delivered Orders</div>
        <div class="stat-value">{{ $totalOrders }}</div>
    </div>
</div>

<!-- Most Sold Products -->
<div class="section">
    <h2>🏆 Top Selling Products</h2>
    @if($mostSoldProducts->count() > 0)
        <div class="product-list">
            @foreach($mostSoldProducts as $product)
                <div class="product-item">
                    <div class="product-item-info">
                        <div class="product-item-name">{{ $product->product_name }}</div>
                        <div class="product-item-meta">
                            Sold: {{ $product->sale_details_count }} units | Price: Rp {{ number_format($product->price, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="product-item-action">
                        <a href="{{ route('admin.products.edit', $product->product_id) }}" style="text-decoration: none; color: var(--primary);">Edit →</a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="no-data">No sales yet</div>
    @endif
</div>

<!-- Low Stock Alert -->
<div class="section" style="border-left: 4px solid var(--warning);">
    <h2>⚠️ Low Stock Items</h2>
    @if($lowStockProducts->count() > 0)
        <div class="product-list">
            @foreach($lowStockProducts as $product)
                <div class="product-item" style="border-left: 3px solid {{ $product->stock == 0 ? 'var(--danger)' : 'var(--warning)' }};">
                    <div class="product-item-info">
                        <div class="product-item-name">{{ $product->product_name }}</div>
                        <div class="product-item-meta">
                            Stock: <strong style="color: {{ $product->stock == 0 ? 'var(--danger)' : 'var(--warning)' }};">{{ $product->stock }} units</strong>
                        </div>
                    </div>
                    <div class="product-item-action">
                        <a href="{{ route('admin.products.edit', $product->product_id) }}" style="text-decoration: none; color: var(--primary);">Restock →</a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="no-data">All products are well-stocked!</div>
    @endif
</div>

<!-- Recent Orders -->
<div class="section">
    <h2>📦 Recent Orders</h2>
    @if($recentOrders->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; font-size: 13px;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--border);">
                        <th style="text-align: left; padding: 10px;">Order ID</th>
                        <th style="text-align: left; padding: 10px;">Customer</th>
                        <th style="text-align: left; padding: 10px;">Amount</th>
                        <th style="text-align: left; padding: 10px;">Items</th>
                        <th style="text-align: left; padding: 10px;">Status</th>
                        <th style="text-align: left; padding: 10px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 10px; font-weight: 600;">#{{ $order->sale_id }}</td>
                            <td style="padding: 10px;">{{ $order->user->name }}</td>
                            <td style="padding: 10px; color: var(--primary); font-weight: 600;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td style="padding: 10px;">{{ $order->details()->sum('quantity') }}</td>
                            <td style="padding: 10px;">
                                <span style="padding: 4px 8px; border-radius: 4px; background: var(--bg-light);">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td style="padding: 10px;">
                                <a href="{{ route('admin.orders.show', $order->sale_id) }}" style="color: var(--primary); text-decoration: none;">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="no-data">No orders yet</div>
    @endif
</div>

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ route('admin.products.create') }}" class="btn btn-success" style="padding: 12px 20px;">+ Add New Product</a>
</div>
@endsection
