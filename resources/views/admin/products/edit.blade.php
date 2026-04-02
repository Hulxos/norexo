@extends('layout')

@section('title', 'Edit Product - NoreXo Admin')

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

    .current-image {
        width: 150px;
        height: 150px;
        background: var(--bg-light);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 50px;
        margin-bottom: 15px;
        overflow: hidden;
    }

    .current-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<div class="form-container">
    <h1 style="margin-bottom: 30px; font-size: 24px;">✏️ Edit Product</h1>

    <form method="POST" action="{{ route('admin.products.update', $product->product_id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Product Name *</label>
            <input type="text" name="product_name" required value="{{ old('product_name', $product->product_name) }}">
            @error('product_name')<div class="error-message">{{ $message }}</div>@enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Price (IDR) *</label>
                <input type="number" name="price" required step="0.01" min="0" value="{{ old('price', $product->price) }}">
                @error('price')<div class="error-message">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Stock *</label>
                <input type="number" name="stock" required min="0" value="{{ old('stock', $product->stock) }}">
                @error('stock')<div class="error-message">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-group">
            <label>Category</label>
            <select name="category">
                <option value="">Select Category</option>
                <option value="Electronics" {{ old('category', $product->category) === 'Electronics' ? 'selected' : '' }}>Electronics</option>
                <option value="Clothing" {{ old('category', $product->category) === 'Clothing' ? 'selected' : '' }}>Clothing</option>
                <option value="Books" {{ old('category', $product->category) === 'Books' ? 'selected' : '' }}>Books</option>
                <option value="Sports" {{ old('category', $product->category) === 'Sports' ? 'selected' : '' }}>Sports</option>
                <option value="Home & Garden" {{ old('category', $product->category) === 'Home & Garden' ? 'selected' : '' }}>Home & Garden</option>
                <option value="Other" {{ old('category', $product->category) === 'Other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description">{{ old('description', $product->description) }}</textarea>
            @error('description')<div class="error-message">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Product Image</label>
            @if($product->image_path)
                <div style="margin-bottom: 15px;">
                    <strong>Current Image:</strong>
                    <div class="current-image">
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->product_name }}">
                    </div>
                </div>
            @endif
            <input type="file" name="image" accept="image/*">
            <small style="color: var(--text-light); margin-top: 5px; display: block;">Upload a new image to replace</small>
            @error('image')<div class="error-message">{{ $message }}</div>@enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.products.index') }}" class="btn" style="background-color: var(--border); color: var(--text); text-align: center; text-decoration: none;">Cancel</a>
            <button type="submit" class="btn btn-success">✓ Update Product</button>
        </div>
    </form>
</div>
@endsection
