<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'NoreXo Kasir')</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800|sora:600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --primary: #e41f1f;
                --primary-dark: #a30d0d;
                --success: #16a34a;
                --danger: #dc2626;
                --text: #161616;
                --text-light: #626262;
                --border: #e5e7eb;
                --bg-light: #f5f6f8;
                --surface: #ffffff;
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                font-family: 'Manrope', sans-serif;
                color: var(--text);
                background:
                    radial-gradient(circle at 12% -10%, rgba(228, 31, 31, 0.12), transparent 28%),
                    radial-gradient(circle at 95% -20%, rgba(17, 24, 39, 0.08), transparent 26%),
                    #f7f7f8;
            }

            .container {
                max-width: 1320px;
                margin: 0 auto;
                padding: 0 18px;
            }

            .top-strip {
                background: linear-gradient(90deg, #7f1d1d, #b91c1c 40%, #dc2626);
                color: #fee2e2;
                font-size: 11px;
                letter-spacing: 0.02em;
            }

            .top-strip .container {
                display: flex;
                justify-content: space-between;
                align-items: center;
                min-height: 28px;
                gap: 12px;
                overflow: hidden;
                white-space: nowrap;
            }

            .main-header {
                position: sticky;
                top: 0;
                z-index: 30;
                border-bottom: 1px solid #f0d6d6;
                background: rgba(255, 248, 248, 0.95);
                backdrop-filter: blur(10px);
            }

            .header-content {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 12px 0;
                gap: 12px;
                flex-wrap: wrap;
            }

            .brand {
                font-size: 28px;
                font-weight: 700;
                color: #111827;
                text-decoration: none;
                font-family: 'Sora', sans-serif;
            }

            .search-wrap {
                flex: 1;
                min-width: 220px;
                display: flex;
                gap: 8px;
            }

            .search-wrap input {
                width: 100%;
                border: 1px solid #f3b2b2;
                background: #fff;
                border-radius: 999px;
                padding: 10px 14px;
                font-size: 13px;
            }

            .search-wrap button {
                border: 0;
                border-radius: 999px;
                min-width: 68px;
                font-weight: 700;
                color: white;
                background: linear-gradient(90deg, var(--primary), #f43f5e);
                cursor: pointer;
            }

            nav {
                display: flex;
                gap: 6px;
                flex-wrap: wrap;
            }

            nav a {
                text-decoration: none;
                color: var(--text);
                font-size: 12px;
                padding: 8px 10px;
                border-radius: 999px;
                border: 1px solid transparent;
            }

            nav a:hover {
                background: #fff;
                border-color: #f5c7c7;
                color: var(--primary-dark);
            }

            .alert {
                margin: 18px 0;
                padding: 12px 14px;
                border-radius: 12px;
                font-size: 14px;
                border: 1px solid var(--border);
                background: var(--surface);
                box-shadow: 0 10px 24px rgba(16, 24, 40, 0.06);
            }

            .alert-success {
                background: #dcfce7;
                color: #166534;
                border-color: #86efac;
            }

            .alert-error {
                background: #fee2e2;
                color: #991b1b;
                border-color: #fca5a5;
            }

            .global-toast {
                position: fixed;
                right: 18px;
                bottom: 18px;
                z-index: 70;
                max-width: 360px;
                background: #111827;
                color: #fff;
                border-radius: 10px;
                padding: 10px 12px;
                font-size: 14px;
                box-shadow: 0 12px 28px rgba(15, 23, 42, 0.3);
                opacity: 0;
                transform: translateY(8px);
                pointer-events: none;
                transition: opacity 0.2s ease, transform 0.2s ease;
            }

            .global-toast.show {
                opacity: 1;
                transform: translateY(0);
            }

            .btn {
                border: 0;
                border-radius: 10px;
                padding: 9px 14px;
                font-size: 14px;
                cursor: pointer;
                font-weight: 700;
            }

            .btn-primary {
                background: var(--primary);
                color: white;
            }

            .btn-primary:hover {
                background: var(--primary-dark);
            }

            .btn-danger {
                background: var(--danger);
                color: white;
            }

            .btn-success {
                background: var(--success);
                color: white;
            }

            main {
                min-height: calc(100vh - 94px);
                padding: 14px 0 30px;
                animation: reveal 0.4s ease;
            }

            @keyframes reveal {
                from {
                    opacity: 0;
                    transform: translateY(8px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @media (max-width: 920px) {
                .brand {
                    font-size: 24px;
                }

                .top-strip .container {
                    justify-content: center;
                }
            }
        </style>
    </head>
    <body>
        <div class="top-strip">
            <div class="container">
                <span>Toko Resmi Norexos</span>
                <span>Proses Cepat • Pelacakan Pesanan Real-time • Pembayaran Aman</span>
            </div>
        </div>

        <header class="main-header">
            <div class="container">
                <div class="header-content">
                    <a href="{{ route('kasir.index') }}" class="brand">Norexos Mart</a>
                    <form class="search-wrap" method="GET" action="{{ route('kasir.index') }}">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk, kategori, atau merek">
                        <button type="submit">Cari</button>
                    </form>
                    <nav>
                        <a href="{{ route('kasir.index') }}">Beranda</a>
                        <a href="{{ route('kasir.dashboard') }}">Dashboard</a>
                        <a href="{{ route('kasir.produk.index') }}">Kelola Produk</a>
                        <a href="{{ route('kasir.faq') }}">FAQ</a>
                        <a href="{{ route('kasir.riwayat') }}">Riwayat Penjualan</a>
                    </nav>
                </div>
            </div>
        </header>

        <main>
            <div class="container">
                @yield('content')
            </div>
        </main>

        @php
            $toastType = null;
            $toastMessage = null;

            if (session('success')) {
                $toastType = 'success';
                $toastMessage = session('success');
            } elseif (session('error')) {
                $toastType = 'error';
                $toastMessage = session('error');
            } elseif ($errors->any()) {
                $toastType = 'error';
                $toastMessage = $errors->first();
            }
        @endphp

        <div id="global-toast" class="global-toast" data-type="{{ $toastType }}" data-message="{{ $toastMessage }}"></div>

        <script>
            (function () {
                const toast = document.getElementById('global-toast');
                if (!toast) {
                    return;
                }

                const message = toast.dataset.message;
                const type = toast.dataset.type;

                if (!message) {
                    return;
                }

                toast.textContent = message;
                toast.style.background = type === 'error' ? '#b91c1c' : '#111827';
                toast.classList.add('show');

                window.setTimeout(() => {
                    toast.classList.remove('show');
                }, 2200);
            })();
        </script>
    </body>
</html>
