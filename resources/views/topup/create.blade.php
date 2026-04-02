@extends('layout')

@section('title', 'Select Top Up Amount - NoreXo')

@section('content')
<style>
    .topup-selection {
        max-width: 600px;
        margin: 40px auto;
        background: white;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 30px;
    }

    .topup-selection h1 {
        font-size: 24px;
        margin-bottom: 10px;
    }

    .topup-description {
        color: var(--text-light);
        margin-bottom: 30px;
        font-size: 14px;
    }

    .amount-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-bottom: 30px;
    }

    .amount-card {
        border: 2px solid var(--border);
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .amount-card:hover {
        border-color: var(--primary);
        box-shadow: 0 5px 15px rgba(37, 99, 235, 0.1);
    }

    .amount-card input[type="radio"] {
        display: none;
    }

    .amount-card input[type="radio"]:checked + .amount-label {
        color: var(--primary);
        font-weight: 700;
    }

    .amount-card input[type="radio"]:checked ~ .amount-card {
        border-color: var(--primary);
        background: var(--bg-light);
    }

    .amount-label {
        font-size: 18px;
        font-weight: 600;
        color: var(--text);
    }

    .amount-description {
        font-size: 12px;
        color: var(--text-light);
        margin-top: 8px;
    }

    .custom-input-group {
        margin-top: 30px;
        padding-top: 30px;
        border-top: 2px solid var(--border);
    }

    .custom-input-group label {
        display: block;
        margin-bottom: 10px;
        font-weight: 600;
        color: var(--text);
    }

    .custom-input-group input {
        width: 100%;
        padding: 12px;
        border: 1px solid var(--border);
        border-radius: 6px;
        font-size: 16px;
        font-family: inherit;
    }

    .custom-input-group input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .button-group {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }

    .button-group .btn {
        flex: 1;
        padding: 12px;
        font-size: 16px;
    }
</style>

<div class="topup-selection">
    <h1>💰 Top Up Your Balance</h1>
    <p class="topup-description">Select an amount or enter a custom amount to add to your NoreXo balance.</p>

    <form method="POST" action="{{ route('topup.store') }}" id="topup-form">
        @csrf

        <div class="amount-grid">
            @foreach($amounts as $amount)
                <label class="amount-card">
                    <input type="radio" name="amount" value="{{ $amount }}" {{ $loop->first ? 'checked' : '' }}>
                    <div class="amount-label">Rp {{ number_format($amount, 0, ',', '.') }}</div>
                    <div class="amount-description">
                        @if($amount == 50000)
                            Good for trying
                        @elseif($amount == 100000)
                            Most popular
                        @elseif($amount == 250000)
                            Great value
                        @elseif($amount == 500000)
                            Best deal
                        @else
                            Maximum top-up
                        @endif
                    </div>
                </label>
            @endforeach
        </div>

        <div class="custom-input-group">
            <label>🔢 Or Enter Custom Amount (Rp 50,000 - Rp 10,000,000):</label>
            <input type="number" name="custom_amount" placeholder="Enter amount..." min="50000" max="10000000">
        </div>

        <input type="hidden" name="payment_method" value="bca">

        <div class="button-group">
            <a href="{{ route('topup.index') }}" class="btn" style="background-color: var(--border); color: var(--text); text-align: center; text-decoration: none;">Cancel</a>
            <button type="submit" class="btn btn-success">Continue to Payment</button>
        </div>
    </form>
</div>

<script>
document.getElementById('topup-form').addEventListener('submit', function(e) {
    const customAmount = document.querySelector('input[name="custom_amount"]').value;
    const selectedAmount = document.querySelector('input[name="amount"]:checked').value;
    const finalAmount = customAmount || selectedAmount;

    document.querySelector('input[name="amount"]').value = finalAmount;
});
</script>
@endsection
