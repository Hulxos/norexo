<aside class="cart-card">
    <h2 class="cart-title">Keranjang Kasir</h2>

    @if (count($keranjang) === 0)
        <div class="empty">Belum ada item. Tambahkan produk dari panel kiri.</div>
    @else
        @foreach ($keranjang as $item)
            <div class="cart-item">
                <div class="cart-line">
                    <strong>{{ $item['produk']->NamaProduk }}</strong>
                    <span>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                </div>
                <div class="cart-line" style="margin-top: 8px;">
                    <div class="qty-box">
                        @if ($item['jumlah'] > 1)
                            <form method="POST" action="{{ route('kasir.keranjang.update', $item['produk']) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="jumlah" value="{{ $item['jumlah'] - 1 }}">
                                <button class="qty-btn qty-btn-minus" type="submit" aria-label="Kurangi jumlah">-</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('kasir.keranjang.hapus', $item['produk']) }}">
                                @csrf
                                @method('DELETE')
                                <button class="qty-btn qty-btn-minus" type="submit" aria-label="Kurangi jumlah">-</button>
                            </form>
                        @endif

                        <span class="qty-number">{{ $item['jumlah'] }}</span>

                        <form method="POST" action="{{ route('kasir.keranjang.update', $item['produk']) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="jumlah" value="{{ $item['jumlah'] + 1 }}">
                            <button class="qty-btn qty-btn-plus" type="submit" aria-label="Tambah jumlah">+</button>
                        </form>
                    </div>

                    <form method="POST" action="{{ route('kasir.keranjang.hapus', $item['produk']) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit">Hapus</button>
                    </form>
                </div>
            </div>
        @endforeach
    @endif

    <div class="total-box">
        Total: Rp {{ number_format($total, 0, ',', '.') }}
    </div>

    <form method="POST" action="{{ route('kasir.transaksi.simpan') }}" class="checkout-form">
        @csrf
        <select name="pelanggan_id">
            <option value="">Pilih pelanggan lama (opsional)</option>
            @foreach ($customers as $cust)
                <option value="{{ $cust->PelangganID }}">{{ $cust->NamaPelanggan }} - {{ $cust->NomorTelepon }}</option>
            @endforeach
        </select>

        <input type="text" name="nama_pelanggan" placeholder="Nama pelanggan baru (jika belum ada)">
        <textarea name="alamat" rows="2" placeholder="Alamat pelanggan (opsional)"></textarea>
        <input type="text" name="nomor_telepon" maxlength="15" placeholder="Nomor telepon (opsional)">

        <button class="btn btn-success" type="submit">Simpan Transaksi</button>
    </form>
</aside>
