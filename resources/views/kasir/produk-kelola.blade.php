@extends('layout-kasir')

@section('title', 'Kelola Produk - NoreXo')

@section('content')
<style>
    .grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 14px;
    }

    .title {
        margin: 0 0 10px;
        font-size: 20px;
        font-family: 'Sora', sans-serif;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1.3fr auto;
        gap: 8px;
    }

    .input {
        width: 100%;
        border: 1px solid #dbe3ef;
        border-radius: 9px;
        padding: 9px 10px;
        font-size: 14px;
    }

    .btn {
        border: 0;
        border-radius: 9px;
        padding: 9px 13px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-primary {
        background: linear-gradient(90deg, #dc2626, #ef4444);
        color: #fff;
    }

    .btn-secondary {
        background: #eef2ff;
        color: #1e3a8a;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    th,
    td {
        border-bottom: 1px solid #eef1f4;
        padding: 8px 6px;
        text-align: left;
    }

    details {
        background: #fafcff;
        border: 1px solid #e6edf5;
        border-radius: 10px;
        padding: 8px 10px;
    }

    details summary {
        cursor: pointer;
        font-weight: 700;
        color: #1f2937;
    }

    .edit-grid {
        margin-top: 8px;
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1.3fr auto;
        gap: 8px;
    }

    .thumb {
        width: 52px;
        height: 52px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #e2e8f0;
    }

    .thumb-fallback {
        width: 52px;
        height: 52px;
        border-radius: 8px;
        border: 1px dashed #cbd5e1;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
    }

    @media (max-width: 860px) {
        .form-grid,
        .edit-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="grid">
    <section class="card">
        <h2 class="title">Tambah Produk</h2>
        <form method="POST" action="{{ route('kasir.produk.store') }}" enctype="multipart/form-data" class="form-grid">
            @csrf
            <input class="input" type="text" name="NamaProduk" placeholder="Nama produk" required>
            <input class="input" type="number" name="Harga" min="0" step="0.01" placeholder="Harga" required>
            <input class="input" type="number" name="Stok" min="0" placeholder="Stok" required>
            <input class="input" type="file" name="foto_produk" accept="image/*">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </section>

    <section class="card">
        <h2 class="title">Edit Produk</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Foto</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $item)
                    <tr>
                        <td>{{ $item->ProdukID }}</td>
                        <td>
                            @if($item->image_path)
                                <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->NamaProduk }}" class="thumb">
                            @else
                                <span class="thumb-fallback">No Img</span>
                            @endif
                        </td>
                        <td>{{ $item->NamaProduk }}</td>
                        <td>Rp {{ number_format($item->Harga, 0, ',', '.') }}</td>
                        <td>{{ $item->Stok }}</td>
                        <td style="min-width: 190px;">
                            <details>
                                <summary>Edit</summary>
                                <form method="POST" action="{{ route('kasir.produk.update', $item) }}" enctype="multipart/form-data" class="edit-grid">
                                    @csrf
                                    @method('PATCH')
                                    <input class="input" type="text" name="NamaProduk" value="{{ $item->NamaProduk }}" required>
                                    <input class="input" type="number" name="Harga" min="0" step="0.01" value="{{ $item->Harga }}" required>
                                    <input class="input" type="number" name="Stok" min="0" value="{{ $item->Stok }}" required>
                                    <input class="input" type="file" name="foto_produk" accept="image/*">
                                    <button type="submit" class="btn btn-secondary">Update</button>
                                </form>
                            </details>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="color:#6b7280;">Belum ada produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 10px;">
            {{ $products->links() }}
        </div>
    </section>
</div>
@endsection
