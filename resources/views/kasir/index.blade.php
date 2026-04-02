@extends('layout-kasir')

@section('title', 'Beranda Kasir - NoreXo')

@section('content')
<style>
    .ajax-status {
        position: fixed;
        right: 18px;
        bottom: 18px;
        z-index: 40;
        background: #111827;
        color: #fff;
        border-radius: 10px;
        padding: 10px 12px;
        font-size: 13px;
        opacity: 0;
        transform: translateY(8px);
        transition: opacity 0.2s ease, transform 0.2s ease;
        pointer-events: none;
    }

    .ajax-status.show {
        opacity: 1;
        transform: translateY(0);
    }

    .hero-grid {
        display: grid;
        grid-template-columns: 1.6fr 0.8fr;
        gap: 14px;
        margin-bottom: 14px;
    }

    .hero {
        border-radius: 18px;
        padding: 28px;
        color: #fff;
        background: linear-gradient(130deg, #8b1010, #ef2b2b 60%, #ff8f8f);
        box-shadow: 0 18px 30px rgba(183, 22, 22, 0.25);
    }

    .hero small {
        display: inline-block;
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.18);
        margin-bottom: 12px;
    }

    .hero h1 {
        margin: 0;
        font-size: clamp(26px, 3.4vw, 44px);
        line-height: 1.04;
        font-family: 'Sora', sans-serif;
    }

    .hero p {
        margin: 14px 0;
        max-width: 600px;
        color: #f8d7d7;
    }

    .hero-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .hero-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.35);
        background: #fff;
        color: #991b1b;
        padding: 8px 13px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
    }

    .hero-btn-alt {
        background: rgba(255, 255, 255, 0.18);
        color: #fff;
        border-color: rgba(255, 255, 255, 0.45);
    }

    .aside-stack {
        display: grid;
        gap: 12px;
    }

    .aside-card {
        border: 1px solid #f0d9d9;
        border-radius: 14px;
        padding: 16px;
        background: #fff;
    }

    .aside-card .k {
        color: #6b7280;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .aside-card .v {
        font-size: 40px;
        line-height: 1;
        font-weight: 800;
        margin: 7px 0;
    }


    .filter-box {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: #fff;
        padding: 16px;
        margin-bottom: 16px;
    }

    .filter-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .filter-title {
        font-size: 31px;
        margin: 0;
        line-height: 1;
        font-family: 'Sora', sans-serif;
    }

    .grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 360px;
        gap: 16px;
        align-items: start;
    }

    .products-wrap {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .product-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 12px;
        display: grid;
        gap: 8px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(17, 24, 39, 0.08);
    }

    .product-image {
        height: 120px;
        border-radius: 10px;
        background: linear-gradient(145deg, #f3f4f6, #ffffff);
        border: 1px solid #eceff4;
        display: grid;
        place-content: center;
        font-size: 36px;
        overflow: hidden;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-name {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
    }

    .product-desc {
        color: var(--text-light);
        font-size: 12px;
        min-height: 32px;
    }

    .meta {
        color: #64748b;
        font-size: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stock-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 999px;
        padding: 4px 9px;
        background: #eff6ff;
        color: #1d4ed8;
        font-weight: 700;
    }

    .stock-pill.low {
        background: #fee2e2;
        color: #b91c1c;
    }

    .stock-dot {
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: currentColor;
    }

    .price {
        font-size: 31px;
        line-height: 1;
        font-family: 'Sora', sans-serif;
        color: #dc2626;
        margin-top: 4px;
    }

    .buy-row {
        display: flex;
        gap: 8px;
    }

    .buy-row input,
    .checkout-form input,
    .checkout-form textarea,
    .checkout-form select,
    .filter-form input,
    .filter-form select {
        width: 100%;
        border: 1px solid #dbe3ef;
        border-radius: 8px;
        padding: 8px 10px;
        font-size: 14px;
    }

    .filter-form {
        display: grid;
        grid-template-columns: 1.2fr 0.7fr 0.7fr 0.7fr auto;
        gap: 8px;
    }

    .filter-submit {
        border: 0;
        border-radius: 8px;
        padding: 8px 14px;
        background: linear-gradient(90deg, #e41f1f, #f43f5e);
        color: #fff;
        font-weight: 700;
        cursor: pointer;
    }

    .cart-card {
        background: #fff;
        border: 1px solid #ecdede;
        border-radius: 16px;
        padding: 15px;
        position: sticky;
        top: 96px;
    }

    .cart-title {
        margin: 0 0 10px;
        font-size: 18px;
        font-weight: 800;
    }

    .cart-item {
        border-bottom: 1px solid var(--border);
        padding: 10px 0;
    }

    .cart-item:last-child {
        border-bottom: 0;
    }

    .cart-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
    }

    .qty-box {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .qty-number {
        min-width: 26px;
        text-align: center;
        font-weight: 700;
    }

    .qty-btn {
        width: 30px;
        height: 30px;
        border: 1px solid #dbe3ef;
        background: linear-gradient(180deg, #ffffff, #f8fafc);
        border-radius: 10px;
        cursor: pointer;
        font-size: 17px;
        line-height: 1;
        font-weight: 700;
        color: #334155;
    }

    .qty-btn:hover {
        background: linear-gradient(180deg, #fff1f2, #ffe4e6);
        border-color: #fda4af;
        color: #be123c;
    }

    .total-box {
        margin-top: 14px;
        border-top: 2px solid #f2d2d2;
        padding-top: 12px;
        font-weight: 700;
        font-size: 24px;
        color: #b91c1c;
        text-align: right;
    }

    .checkout-form {
        margin-top: 14px;
        display: grid;
        gap: 10px;
    }

    .empty {
        color: var(--text-light);
        font-size: 14px;
        padding: 10px 0;
    }

    @media (max-width: 1220px) {
        .products-wrap {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 1000px) {
        .hero-grid,
        .grid {
            grid-template-columns: 1fr;
        }

        .products-wrap {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .filter-form {
            grid-template-columns: 1fr 1fr;
        }

        .cart-card {
            position: static;
        }
    }

    @media (max-width: 640px) {
        .products-wrap,
        .filter-form {
            grid-template-columns: 1fr;
        }

        .filter-title {
            font-size: 25px;
        }
    }
</style>

<section class="hero-grid">
    <div class="hero">
        <small>Toko Resmi</small>
        <h1>Belanja Cepat, Harga Tepat, Sampai Di Rumah</h1>
        <p>
            Kelola transaksi kasir dengan proses pencarian cepat, input pelanggan fleksibel,
            dan checkout yang aman dalam satu layar.
        </p>
        <div class="hero-actions">
            <a href="#produk" class="hero-btn">Lihat Produk</a>
            <a href="{{ route('kasir.riwayat') }}" class="hero-btn">Transaksi Hari Ini</a>
            <a href="{{ route('kasir.produk.index') }}" class="hero-btn hero-btn-alt">Kelola Produk</a>
        </div>
    </div>
    <div class="aside-stack">
        <article class="aside-card">
            <div class="k">Katalog Aktif</div>
            <div class="v">{{ $jumlahProdukAktif }}</div>
            <div style="font-size: 13px; color: #6b7280;">Produk siap dipilih kasir.</div>
        </article>
        <article class="aside-card">
            <div class="k">Komitmen Norexos</div>
            <div style="font-size: 13px; line-height: 1.7; color: #4b5563; margin-top: 8px;">
                Proses capset setelah pembayaran.<br>
                Status pesanan jelas dan mudah dicek.<br>
                Mendukung COD dan checkout cepat.
            </div>
        </article>
    </div>
</section>

<section class="filter-box" id="produk">
    <div class="filter-head">
        <h2 class="filter-title">Filter Produk</h2>
        <small style="color: #6b7280;">Sesuaikan hasil sesuai budget dan prioritasmu</small>
    </div>

    <form class="filter-form" method="GET" action="{{ route('kasir.index') }}">
        <input type="text" name="q" placeholder="Cari produk" value="{{ $filter['q'] }}">
        <input type="number" name="min_harga" placeholder="Harga min" value="{{ $filter['min_harga'] }}" min="0">
        <input type="number" name="max_harga" placeholder="Harga maks" value="{{ $filter['max_harga'] }}" min="0">
        <select name="sort">
            <option value="terbaru" {{ $filter['sort'] === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
            <option value="harga_termurah" {{ $filter['sort'] === 'harga_termurah' ? 'selected' : '' }}>Harga Terendah</option>
            <option value="harga_tertinggi" {{ $filter['sort'] === 'harga_tertinggi' ? 'selected' : '' }}>Harga Tertinggi</option>
            <option value="stok" {{ $filter['sort'] === 'stok' ? 'selected' : '' }}>Stok Terbanyak</option>
        </select>
        <button class="filter-submit" type="submit">Terapkan</button>
    </form>
</section>

<div class="grid">
    <section class="products-wrap">
        @forelse ($products as $item)
            @php
                $initial = strtoupper(substr($item->NamaProduk, 0, 1));
            @endphp
            <article class="product-card">
                <div class="product-image">
                    @if($item->image_path)
                        <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->NamaProduk }}">
                    @else
                        {{ $initial }}
                    @endif
                </div>
                <h3 class="product-name">{{ $item->NamaProduk }}</h3>
                <p class="product-desc">Pilihan produk untuk kebutuhan transaksi kasir harian.</p>
                <div class="meta">
                    <span class="stock-pill {{ $item->Stok <= 5 ? 'low' : '' }}"><span class="stock-dot"></span>Stok {{ $item->Stok }}</span>
                    <span>ID: {{ $item->ProdukID }}</span>
                </div>
                <div class="price">Rp {{ number_format($item->Harga, 0, ',', '.') }}</div>

                <form method="POST" action="{{ route('kasir.keranjang.tambah') }}">
                    @csrf
                    <input type="hidden" name="produk_id" value="{{ $item->ProdukID }}">
                    <div class="buy-row">
                        <input type="number" name="jumlah" min="1" max="{{ $item->Stok }}" value="1" required>
                        <button class="btn btn-primary" type="submit">Tambah</button>
                    </div>
                </form>
            </article>
        @empty
            <article class="product-card" style="grid-column: 1 / -1; text-align: center;">
                <h3 class="product-name">Produk tidak ditemukan</h3>
                <p class="product-desc">Coba ganti kata kunci atau rentang harga.</p>
            </article>
        @endforelse
    </section>

    <div id="kasir-cart-panel">
        @include('kasir.partials.cart-panel', ['keranjang' => $keranjang, 'customers' => $customers, 'total' => $total])
    </div>
</div>

<div id="ajax-status" class="ajax-status"></div>

<script>
    (function () {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const cartContainer = document.getElementById('kasir-cart-panel');
        const statusBox = document.getElementById('ajax-status');

        function showStatus(message, isError = false) {
            if (!statusBox) {
                return;
            }

            statusBox.textContent = message;
            statusBox.style.background = isError ? '#b91c1c' : '#111827';
            statusBox.classList.add('show');

            window.clearTimeout(showStatus._timer);
            showStatus._timer = window.setTimeout(() => {
                statusBox.classList.remove('show');
            }, 1800);
        }

        async function submitCartForm(form) {
            const formData = new FormData(form);

            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: formData,
            });

            const payload = await response.json().catch(() => null);

            if (!response.ok || !payload) {
                throw new Error(payload?.message || 'Gagal memproses keranjang.');
            }

            if (payload.cart_html && cartContainer) {
                cartContainer.innerHTML = payload.cart_html;
            }

            showStatus(payload.message || 'Keranjang diperbarui.');
        }


        document.addEventListener('submit', function (event) {
            const form = event.target;
            if (!(form instanceof HTMLFormElement)) {
                return;
            }

            if (!form.action.includes('/keranjang/')) {
                return;
            }

            event.preventDefault();

            submitCartForm(form).catch((error) => {
                showStatus(error.message, true);
            });
        });

    })();
</script>
@endsection
