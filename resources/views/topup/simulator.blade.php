@extends('layout')

@section('title', 'BCA Virtual Account Simulator - NoreXo')

@section('content')
<style>
    .bca-simulator {
        max-width: 500px;
        margin: 40px auto;
        background: white;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 30px;
    }

    .bca-header {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
        color: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
        text-align: center;
    }

    .bca-header h1 {
        font-size: 28px;
        margin-bottom: 5px;
    }

    .bca-header p {
        font-size: 12px;
        opacity: 0.9;
    }

    .bank-logo {
        font-size: 48px;
        margin-bottom: 10px;
    }

    .transaction-details {
        background: var(--bg-light);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid var(--border);
        font-size: 14px;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        color: var(--text-light);
        font-weight: 500;
    }

    .detail-value {
        font-weight: 600;
        color: var(--text);
    }

    .virtual-account {
        background: white;
        border: 2px dashed var(--primary);
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        margin: 20px 0;
    }

    .va-label {
        font-size: 12px;
        color: var(--text-light);
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .va-number {
        font-size: 24px;
        font-weight: 700;
        color: var(--primary);
        font-family: monospace;
        margin-bottom: 10px;
        letter-spacing: 2px;
    }

    .copy-button {
        background: var(--bg-light);
        border: 1px solid var(--border);
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.2s;
    }

    .copy-button:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .instructions {
        background: #fef3c7;
        border-left: 4px solid var(--warning);
        border-radius: 6px;
        padding: 15px;
        margin: 20px 0;
        font-size: 13px;
        line-height: 1.6;
    }

    .instructions h3 {
        color: #92400e;
        margin-bottom: 10px;
    }

    .instructions ol {
        margin-left: 20px;
    }

    .instructions li {
        margin-bottom: 8px;
        color: #92400e;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .action-buttons .btn {
        flex: 1;
        padding: 12px;
        font-size: 14px;
    }

    .simulator-note {
        background: #dbeafe;
        border-left: 4px solid var(--primary);
        border-radius: 6px;
        padding: 12px;
        margin: 20px 0;
        font-size: 12px;
        color: #0c4a6e;
    }
</style>

<div class="bca-simulator">
    <!-- BCA Header -->
    <div class="bca-header">
        <div class="bank-logo">🏦</div>
        <h1>BCA</h1>
        <p>Bank Central Asia</p>
    </div>

    <!-- Transaction Info -->
    <h2 style="margin-bottom: 15px; font-size: 18px;">Payment Details</h2>
    <div class="transaction-details">
        <div class="detail-row">
            <span class="detail-label">Reference Number</span>
            <span class="detail-value">{{ $transaction->reference_number }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Amount</span>
            <span class="detail-value">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Status</span>
            <span class="detail-value" style="color: var(--warning);">⏳ Pending</span>
        </div>
    </div>

    <!-- Virtual Account -->
    <h2 style="margin-bottom: 15px; font-size: 18px;">Virtual Account Number</h2>
    <div class="virtual-account">
        <div class="va-label">Transfer to</div>
        <div class="va-number">{{ $virtualAccount }}</div>
        <button type="button" class="copy-button" onclick="copyVA()">📋 Copy</button>
    </div>

    <!-- Instructions -->
    <div class="instructions">
        <h3>How to Transfer:</h3>
        <ol>
            <li>Open your BCA mobile banking app or ATM</li>
            <li>Select "Transfer to BCA VA" or "Transfer to Other Banks"</li>
            <li>Enter the virtual account number above</li>
            <li>Enter the amount: <strong>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</strong></li>
            <li>Confirm and complete the transfer</li>
            <li>Return here to confirm payment (simulated)</li>
        </ol>
    </div>

    <!-- Simulator Note -->
    <div class="simulator-note">
        ℹ️ <strong>Simulator Mode:</strong> This is a payment simulator for testing purposes. Click "Confirm Payment" to complete the transaction.
    </div>

    <!-- Action Buttons -->
    <form method="POST" action="{{ route('topup.confirm', $transaction->id) }}" class="action-buttons">
        @csrf
        <a href="{{ route('topup.index') }}" class="btn" style="background-color: var(--border); color: var(--text); text-align: center; text-decoration: none;">← Cancel</a>
        
        <input type="hidden" name="confirm" value="1">
        <button type="submit" class="btn btn-success">✓ Confirm Payment</button>
    </form>

    <form method="POST" action="{{ route('topup.confirm', $transaction->id) }}" style="margin-top: 10px;">
        @csrf
        <input type="hidden" name="confirm" value="0">
        <button type="submit" class="btn btn-danger" style="width: 100%; padding: 12px;">✗ Payment Failed</button>
    </form>
</div>

<script>
function copyVA() {
    const vaNumber = '{{ $virtualAccount }}';
    navigator.clipboard.writeText(vaNumber).then(() => {
        alert('Virtual Account number copied to clipboard!');
    });
}
</script>
@endsection
