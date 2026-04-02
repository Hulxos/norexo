@extends('layout-kasir')

@section('title', 'FAQ - NoreXo Kasir')

@section('content')
<style>
    .faq-wrap {
        display: grid;
        gap: 12px;
        max-width: 980px;
    }

    .faq-head {
        background: linear-gradient(135deg, #8b1010, #ef2b2b 70%, #ff9b9b);
        color: #fff;
        border-radius: 16px;
        padding: 22px;
    }

    .faq-head h1 {
        margin: 0 0 8px;
        font-size: 30px;
        font-family: 'Sora', sans-serif;
    }

    .faq-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 14px;
    }

    .faq-card h3 {
        margin: 0 0 8px;
        font-size: 16px;
    }

    .faq-card p {
        margin: 0;
        color: var(--text-light);
        line-height: 1.65;
        font-size: 14px;
    }
</style>

<div class="faq-wrap">
    <section class="faq-head">
        <h1>FAQ Kasir Norexos</h1>
        <p>Panduan singkat fitur kasir, transaksi, return, dan pengelolaan stok produk.</p>
    </section>

    <article class="faq-card">
        <h3>Bagaimana menambah produk ke keranjang tanpa reload?</h3>
        <p>Klik tombol Tambah pada kartu produk atau tombol plus minus di keranjang. Sistem memakai AJAX sehingga subtotal dan total langsung berubah tanpa refresh halaman.</p>
    </article>

    <article class="faq-card">
        <h3>Apa fungsi Return Transaksi pada riwayat?</h3>
        <p>Return akan mengembalikan jumlah produk pada transaksi tersebut ke stok. Status transaksi berubah menjadi Sudah Return agar tidak diproses return dua kali.</p>
    </article>

    <article class="faq-card">
        <h3>Jika transaksi dihapus apakah stok kembali?</h3>
        <p>Jika transaksi belum pernah direturn, stok produk otomatis dikembalikan sebelum transaksi dihapus. Jika sudah direturn sebelumnya, sistem tidak menambah stok lagi.</p>
    </article>

    <article class="faq-card">
        <h3>Kenapa notifikasi sekarang tampil kanan bawah?</h3>
        <p>Notifikasi dipusatkan di kanan bawah agar antarmuka tetap bersih dan tidak mengganggu area form. Pesan sukses maupun error tampil sebagai toast.</p>
    </article>
</div>
@endsection
