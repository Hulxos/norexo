@extends('layout-kasir')

@section('title', 'Riwayat Penjualan - NoreXo')

@section('content')
<style>
    .card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 18px;
    }

    .title {
        margin: 0 0 14px;
        font-size: 22px;
        font-weight: 700;
    }

    .trx {
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 12px;
        margin-bottom: 12px;
        background: #fcfdff;
    }

    .trx-head {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
    }

    .detail {
        margin-top: 10px;
        font-size: 14px;
        color: var(--text-light);
    }

    .status {
        display: inline-flex;
        align-items: center;
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 12px;
        font-weight: 700;
    }

    .status-paid {
        background: #dcfce7;
        color: #166534;
    }

    .status-return {
        background: #fee2e2;
        color: #991b1b;
    }

    .action-row {
        margin-top: 12px;
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn {
        border: 0;
        border-radius: 8px;
        padding: 7px 11px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-warning {
        background: #f59e0b;
        color: #111827;
    }

    .btn-danger {
        background: #dc2626;
        color: #fff;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 8px;
        font-size: 13px;
    }

    th,
    td {
        border-bottom: 1px solid var(--border);
        padding: 6px;
        text-align: left;
    }
</style>

<div class="card">
    <h2 class="title">Riwayat Penjualan Kasir</h2>

    @forelse ($salesHistory as $trx)
        <article class="trx">
            <div class="trx-head">
                <strong>Transaksi #{{ $trx->PenjualanID }}</strong>
                <span>{{ $trx->TanggalPenjualan->format('d-m-Y') }}</span>
                <strong style="color: var(--primary);">Rp {{ number_format($trx->TotalHarga, 0, ',', '.') }}</strong>
            </div>

            <div class="detail">
                Pelanggan: {{ $trx->pelanggan?->NamaPelanggan ?? 'Umum' }}
            </div>

            <div class="detail">
                @if ($trx->IsReturned)
                    <span class="status status-return">Sudah Return</span>
                    <span>• {{ $trx->ReturnedAt?->format('d-m-Y H:i') }}</span>
                @else
                    <span class="status status-paid">Aktif</span>
                @endif
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($trx->detail as $item)
                        <tr>
                            <td>{{ $item->produk?->NamaProduk }}</td>
                            <td>{{ $item->JumlahProduk }}</td>
                            <td>Rp {{ number_format($item->Subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="action-row">
                @if (!$trx->IsReturned)
                    <form method="POST" action="{{ route('kasir.riwayat.return', $trx) }}" onsubmit="return confirm('Return transaksi ini? Stok produk akan dikembalikan.');">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-warning">Return Transaksi</button>
                    </form>
                @endif

                <form method="POST" action="{{ route('kasir.riwayat.hapus', $trx) }}" onsubmit="return confirm('Hapus transaksi ini? Data tidak bisa dipulihkan.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </article>
    @empty
        <p style="color: var(--text-light);">Belum ada transaksi.</p>
    @endforelse

    {{ $salesHistory->links() }}
</div>
@endsection
