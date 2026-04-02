@extends('layout')

@section('title', 'Transaction History - NoreXo')

@section('content')
<style>
    .history-table {
        width: 100%;
        background: white;
        border: 1px solid var(--border);
        border-collapse: collapse;
        border-radius: 10px;
        overflow: hidden;
        margin: 30px 0;
    }

    .history-table th {
        background: var(--bg-light);
        padding: 15px;
        text-align: left;
        font-weight: 600;
        border-bottom: 2px solid var(--border);
        font-size: 13px;
        text-transform: uppercase;
    }

    .history-table td {
        padding: 15px;
        border-bottom: 1px solid var(--border);
    }

    .history-table tr:last-child td {
        border-bottom: none;
    }

    .transaction-type {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }

    .type-topup {
        background: #dbeafe;
        color: #0c4a6e;
    }

    .type-payment {
        background: #dcfce7;
        color: #166534;
    }

    .transaction-status {
        display: inline-block;
        padding: 4px 8px;
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

    .amount-value {
        font-weight: 600;
        color: var(--primary);
        font-size: 15px;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin: 30px 0;
    }

    .pagination a,
    .pagination span {
        padding: 8px 12px;
        border: 1px solid var(--border);
        border-radius: 6px;
        text-decoration: none;
        color: var(--text);
        font-size: 13px;
    }

    .pagination a.active,
    .pagination span.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
</style>

<h1 style="margin: 20px 0; font-size: 28px;">📜 Transaction History</h1>

@if($transactions->count() > 0)
    <table class="history-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Reference</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Method</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $txn)
                <tr>
                    <td>{{ $txn->created_at->format('d M Y H:i') }}</td>
                    <td style="font-family: monospace; font-size: 12px;">{{ $txn->reference_number }}</td>
                    <td>
                        <span class="transaction-type type-{{ $txn->type }}">
                            {{ $txn->type === 'topup' ? '⬆️ Top-up' : '💳 Payment' }}
                        </span>
                    </td>
                    <td>
                        <span class="amount-value">
                            {{ $txn->type === 'topup' ? '+' : '-' }} Rp {{ number_format($txn->amount, 0, ',', '.') }}
                        </span>
                    </td>
                    <td>
                        <span class="transaction-status status-{{ $txn->status }}">
                            @match($txn->status)
                                'success' => '✓ Success',
                                'pending' => '⏳ Pending',
                                'failed' => '✗ Failed',
                            @endmatch
                        </span>
                    </td>
                    <td style="font-size: 12px; color: var(--text-light);">
                        {{ $txn->payment_method ? ucfirst($txn->payment_method) : '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination">
        {{ $transactions->links() }}
    </div>
@else
    <div style="text-align: center; padding: 60px; background: white; border-radius: 10px; color: var(--text-light);">
        <h2 style="color: var(--text); margin-bottom: 10px;">No transactions</h2>
        <p>Your transaction history will appear here once you make your first payment or top-up.</p>
    </div>
@endif
@endsection
