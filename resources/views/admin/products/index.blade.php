@extends('layout')

@php
    /** @var \Illuminate\Pagination\LengthAwarePaginator $products */
@endphp

@section('title', 'Manage Products - NoreXo Admin')

@section('content')
<style>
    .admin-container {
        margin: 30px 0;
    }

    .products-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .products-filters {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .products-filters input,
    .products-filters select {
        padding: 8px 12px;
        border: 1px solid var(--border);
        border-radius: 6px;
        font-size: 13px;
    }

    .products-table {
        width: 100%;
        background: white;
        border: 1px solid var(--border);
        border-collapse: collapse;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 20px;
    }

    .products-table th {
        background: var(--bg-light);
        padding: 12px;
        text-align: left;
        font-weight: 600;
        border-bottom: 2px solid var(--border);
        font-size: 12px;
        text-transform: uppercase;
    }

    .products-table td {
        padding: 12px;
        border-bottom: 1px solid var(--border);
    }

    .product-name {
        font-weight: 600;
        color: var(--text);
    }

    .product-price {
        color: var(--primary);
        font-weight: 600;
    }

    .stock-status {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
    }

    .stock-good {
        background: #dcfce7;
        color: #166534;
    }

    .stock-low {
        background: #fef3c7;
        color: #92400e;
    }

    .stock-out {
        background: #fee2e2;
        color: #991b1b;
    }

    .table-actions {
        display: flex;
        gap: 8px;
    }

    .table-actions a,
    .table-actions form {
        display: inline;
    }

    .table-actions button {
        padding: 6px 10px;
        font-size: 11px;
    }
</style>

<div class="admin-container">
    <div class="products-header">
        <h1 style="font-size: 24px;">📦 Manage Products</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-success" style="padding: 10px 20px;">+ Add Product</a>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('admin.products.index') }}" class="products-filters">
        <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}">
        <select name="sort_stock">
            <option value="">All Products</option>
            <option value="low" {{ request('sort_stock') === 'low' ? 'selected' : '' }}>Low Stock</option>
        </select>
        <button type="submit" class="btn btn-primary" style="padding: 8px 15px;">Filter</button>
    </form>

    <!-- Products Table -->
    @if($products->count() > 0)
        <table class="products-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Sold</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td class="product-name">{{ $product->product_name }}</td>
                        <td>{{ $product->category ?? '-' }}</td>
                        <td class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>
                            <span class="stock-status 
                                {{ $product->stock > 20 ? 'stock-good' : ($product->stock > 0 ? 'stock-low' : 'stock-out') }}">
                                {{ $product->stock }} units
                            </span>
                        </td>
                        <td>{{ $product->getTotalSold() }}</td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('admin.products.edit', $product->product_id) }}" class="btn btn-primary" style="padding: 6px 10px; font-size: 11px;">Edit</a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product->product_id) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 6px 10px; font-size: 11px;" onclick="return confirm('Delete this product?');">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        {{ $products->links() }}
    @else
        <div style="text-align: center; padding: 60px; background: white; border-radius: 10px; color: var(--text-light);">
            <h2 style="color: var(--text); margin-bottom: 10px;">No products found</h2>
            <a href="{{ route('admin.products.create') }}" class="btn btn-success" style="margin-top: 15px;">Add your first product</a>
        </div>
    @endif
</div>
@endsection
