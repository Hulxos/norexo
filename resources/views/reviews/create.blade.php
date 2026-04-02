@extends('layout')

@section('title', 'Leave Review - NoreXo')

@section('content')
<style>
    .review-form-container {
        max-width: 600px;
        margin: 30px auto;
        background: white;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 30px;
    }

    .review-form-container h1 {
        font-size: 24px;
        margin-bottom: 10px;
    }

    .review-form-intro {
        color: var(--text-light);
        margin-bottom: 30px;
        font-size: 14px;
    }

    .product-review {
        margin-bottom: 30px;
        padding: 20px;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: var(--bg-light);
    }

    .product-review-header {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
    }

    .product-image-small {
        width: 60px;
        height: 60px;
        background: white;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        flex-shrink: 0;
    }

    .product-review-info h3 {
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .product-review-price {
        color: var(--primary);
        font-weight: 600;
        font-size: 14px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
    }

    .star-rating {
        display: flex;
        gap: 10px;
        font-size: 32px;
        margin-bottom: 10px;
    }

    .star-rating button {
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        color: #d1d5db;
        transition: color 0.2s;
    }

    .star-rating button:hover,
    .star-rating button.active {
        color: #fbbf24;
    }

    .rating-value {
        font-size: 13px;
        color: var(--text-light);
        margin-bottom: 10px;
    }

    .textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid var(--border);
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
        resize: vertical;
        min-height: 100px;
    }

    .textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .submit-section {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }

    .submit-section .btn {
        flex: 1;
        padding: 12px;
        font-size: 16px;
    }
</style>

<div class="review-form-container">
    <h1>✍️ Leave Product Reviews</h1>
    <p class="review-form-intro">Share your experience with the products from order #{{ $sale->sale_id }}. Your feedback helps other shoppers make informed decisions.</p>

    <form method="POST" action="{{ route('reviews.store', $sale->sale_id) }}">
        @csrf

        @foreach($sale->details as $detail)
            <div class="product-review">
                <div class="product-review-header">
                    <div class="product-image-small">
                        @if($detail->product->image_path)
                            <img src="{{ asset('storage/' . $detail->product->image_path) }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;">
                        @else
                            📦
                        @endif
                    </div>
                    <div class="product-review-info">
                        <h3>{{ $detail->product->product_name }}</h3>
                        <div class="product-review-price">Rp {{ number_format($detail->product->price, 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Rating</label>
                    <div class="star-rating" data-product="{{ $detail->product_id }}">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" class="star" data-value="{{ $i }}" onclick="setRating(this)">★</button>
                        @endfor
                    </div>
                    <input type="hidden" name="reviews[{{ $loop->index }}][product_id]" value="{{ $detail->product_id }}">
                    <input type="hidden" name="reviews[{{ $loop->index }}][rating]" class="rating-input" value="0">
                    <span class="rating-value">Click a star to rate</span>
                </div>

                <div class="form-group">
                    <label>Comment (Optional)</label>
                    <textarea name="reviews[{{ $loop->index }}][comment]" class="textarea" placeholder="Share your thoughts about this product..." maxlength="500"></textarea>
                </div>
            </div>
        @endforeach

        <div class="submit-section">
            <a href="{{ route('orders.show', $sale->sale_id) }}" class="btn" style="background-color: var(--border); color: var(--text);">Cancel</a>
            <button type="submit" class="btn btn-success">✓ Submit Reviews</button>
        </div>
    </form>
</div>

<script>
function setRating(button) {
    const container = button.parentElement;
    const value = button.dataset.value;
    const input = container.parentElement.querySelector('.rating-input');
    
    // Update hidden input
    input.value = value;
    
    // Update stars display
    const stars = container.querySelectorAll('.star');
    stars.forEach((star, index) => {
        if (index < value) {
            star.classList.add('active');
        } else {
            star.classList.remove('active');
        }
    });
    
    // Update rating text
    const parent = container.parentElement;
    parent.querySelector('.rating-value').textContent = value + ' star' + (value !== '1' ? 's' : '');
}
</script>
@endsection
