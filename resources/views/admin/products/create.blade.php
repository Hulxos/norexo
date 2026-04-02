@extends('layout')

@section('title', 'Add Product - NoreXo Admin')

@section('content')
<style>
    .form-container {
        max-width: 600px;
        margin: 30px auto;
        background: white;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--text);
        font-size: 14px;
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

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }

    .form-actions .btn {
        flex: 1;
        padding: 12px;
        font-size: 16px;
    }

    .error-message {
        color: var(--danger);
        font-size: 12px;
        margin-top: 5px;
    }
</style>

<div class="form-container">
    <h1 style="margin-bottom: 30px; font-size: 24px;">📦 Add New Product</h1>

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Product Name *</label>
            <input type="text" name="product_name" required value="{{ old('product_name') }}" placeholder="e.g., Wireless Headphones">
            @error('product_name')<div class="error-message">{{ $message }}</div>@enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Price (IDR) *</label>
                <input type="number" name="price" required step="0.01" min="0" value="{{ old('price') }}" placeholder="100000">
                @error('price')<div class="error-message">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Stock *</label>
                <input type="number" name="stock" required min="0" value="{{ old('stock') }}" placeholder="0">
                @error('stock')<div class="error-message">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-group">
            <label>Category</label>
            <select name="category">
                <option value="">Select Category</option>
                <option value="Electronics" {{ old('category') === 'Electronics' ? 'selected' : '' }}>Electronics</option>
                <option value="Clothing" {{ old('category') === 'Clothing' ? 'selected' : '' }}>Clothing</option>
                <option value="Books" {{ old('category') === 'Books' ? 'selected' : '' }}>Books</option>
                <option value="Sports" {{ old('category') === 'Sports' ? 'selected' : '' }}>Sports</option>
                <option value="Home & Garden" {{ old('category') === 'Home & Garden' ? 'selected' : '' }}>Home & Garden</option>
                <option value="Other" {{ old('category') === 'Other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" placeholder="Describe your product...">{{ old('description') }}</textarea>
            @error('description')<div class="error-message">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Product Image</label>
            <input type="file" name="image" accept="image/*">
            <small style="color: var(--text-light); margin-top: 5px; display: block;">Supported: JPEG, PNG, JPG, GIF (Max 2MB)</small>
            @error('image')<div class="error-message">{{ $message }}</div>@enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.products.index') }}" class="btn" style="background-color: var(--border); color: var(--text); text-align: center; text-decoration: none;">Cancel</a>
            <button type="submit" class="btn btn-success">✓ Add Product</button>
        </div>
    </form>
</div>
@endsection
