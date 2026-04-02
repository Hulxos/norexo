@extends('layout')

@section('title', 'Top Up Balance - NoreXo')

@section('content')
<style>
    .topup-container {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 30px;
        margin: 30px 0;
    }

    .topup-section {
        background: white;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 30px;
    }

    .topup-section h2 {
        font-size: 20px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--primary);
    }

    .balance-card {
        background: linear-gradient(135deg, var(--primary) 0%, #1d4ed8 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        text-align: center;
        margin-bottom: 30px;
    }

    .balance-label {
        font-size: 13px;
        opacity: 0.9;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .balance-amount {
        font-size: 36px;
        font-weight: 700;
    }

    .amount-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .amount-btn {
        padding: 15px;
        border: 2px solid var(--border);
        background: white;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        font-weight: 600;
        color: var(--text);
    }

    .amount-btn:hover,
    .amount-btn.active {
        border-color: var(--primary);
        background: var(--bg-light);
        color: var(--primary);
    }

    .custom-amount {
        margin-top: 20px;
    }

    .custom-amount label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--text);
    }

    .custom-amount input {
        width: 100%;
        padding: 12px;
        border: 1px solid var(--border);
        border-radius: 6px;
        font-size: 16px;
        font-family: inherit;
    }

    .custom-amount input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .payment-method {
        margin: 20px 0;
    }

    .payment-method label {
        display: flex;
        align-items: center;
        padding: 15px;
        border: 2px solid var(--border);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .payment-method input[type="radio"] {
        margin-right: 10px;
    }

    .payment-method input[type="radio"]:checked + span {
        color: var(--primary);
        font-weight: 600;
    }

    .payment-method label:has(input[type="radio"]:checked) {
        border-color: var(--primary);
        background: var(--bg-light);
    }

    .topup-btn {
        width: 100%;
        padding: 14px;
        font-size: 16px;
        margin-top: 20px;
    }

    .transaction-history {
        background: white;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 30px;
        height: fit-content;
        position: sticky;
        top: 100px;
    }

    .transaction-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid var(--border);
        font-size: 13px;
    }

    .transaction-item:last-child {
        border-bottom: none;
    }

    .transaction-amount {
        font-weight: 600;
        color: var(--success);
    }

    .transaction-status {
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }

    .status-success {
        background: #dcfce7;
        color: #166534;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-failed {
        background: #fee2e2;
        color: #991b1b;
    }

    .view-history-link {
        display: inline-block;
        margin-top: 15px;
        color: var(--primary);
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .topup-container {
            grid-template-columns: 1fr;
        }

        .transaction-history {
            position: static;
        }
    }
</style>

<h1 style="margin: 20px 0; font-size: 28px;">💰 Top Up Balance</h1>

<div class="topup-container">
    <!-- Top Up Form -->
    <div class="topup-section">
        <div class="balance-card">
            <div class="balance-label">Current Balance</div>
            <div class="balance-amount">Rp {{ number_format(auth()->user()->balance, 0, ',', '.') }}</div>
        </div>

        <form method="POST" action="{{ route('topup.store') }}">
            @csrf

            <h2>Select Amount</h2>
            <div class="amount-options">
                <button type="button" class="amount-btn" onclick="selectAmount(50000, this)">Rp 50K</button>
                <button type="button" class="amount-btn" onclick="selectAmount(100000, this)">Rp 100K</button>
                <button type="button" class="amount-btn" onclick="selectAmount(250000, this)">Rp 250K</button>
                <button type="button" class="amount-btn" onclick="selectAmount(500000, this)">Rp 500K</button>
                <button type="button" class="amount-btn" onclick="selectAmount(1000000, this)">Rp 1M</button>
            </div>

            <div class="custom-amount">
                <label>Or enter custom amount:</label>
                <input type="number" id="custom-amount" name="amount" placeholder="Enter amount (min 50000)" min="50000" max="10000000" value="">
            </div>

            <div class="payment-method">
                <h2 style="margin-bottom: 15px;">Payment Method</h2>
                <label>
                    <input type="radio" name="payment_method" value="bca" checked>
                    <span>💳 BCA Virtual Account</span>
                </label>
            </div>

            <button type="submit" class="btn btn-success topup-btn">🔄 Proceed to Payment</button>
        </form>

        <div style="margin-top: 30px; padding: 15px; background: var(--bg-light); border-radius: 8px; font-size: 13px;">
            <strong>ℹ️ Information:</strong>
            <ul style="margin-top: 10px; margin-left: 20px;">
                <li>Minimum top-up: Rp 50,000</li>
                <li>Maximum top-up: Rp 10,000,000</li>
                <li>Payment will be simulated with a virtual BCA account</li>
                <li>Balance is valid for all purchases on NoreXo</li>
            </ul>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="transaction-history">
        <h2 style="font-size: 16px; margin-bottom: 15px;">Recent Top-Ups</h2>

        @if($transactions->count() > 0)
            @foreach($transactions as $txn)
                <div class="transaction-item">
                    <div>
                        <div style="font-weight: 600; margin-bottom: 3px;">Rp {{ number_format($txn->amount, 0, ',', '.') }}</div>
                        <div style="color: var(--text-light); font-size: 12px;">{{ $txn->created_at->format('d M Y H:i') }}</div>
                    </div>
                    <span class="transaction-status status-{{ $txn->status }}">{{ ucfirst($txn->status) }}</span>
                </div>
            @endforeach

            <a href="{{ route('topup.history') }}" class="view-history-link">→ View All Transactions</a>
        @else
            <p style="color: var(--text-light); text-align: center; padding: 20px 0; font-size: 13px;">No transactions yet</p>
        @endif
    </div>
</div>

<script>
function selectAmount(amount, button) {
    document.getElementById('custom-amount').value = amount;
    
    // Update active button
    document.querySelectorAll('.amount-btn').forEach(btn => btn.classList.remove('active'));
    button.classList.add('active');
}
</script>
@endsection
