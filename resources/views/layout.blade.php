<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'NoreXo - E-Commerce')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Turbo.js for fast navigation -->
        <script src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@7.3.0/dist/turbo.es2017-umd.js"></script>
        
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            :root {
                --primary: #2563eb;
                --primary-dark: #1d4ed8;
                --success: #16a34a;
                --danger: #dc2626;
                --warning: #ea580c;
                --text: #1f2937;
                --text-light: #6b7280;
                --border: #e5e7eb;
                --bg-light: #f9fafb;
            }

            body {
                font-family: 'Figtree', sans-serif;
                color: var(--text);
                background-color: #fff;
            }

            .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 20px;
            }

            /* Header */
            header {
                background: white;
                border-bottom: 1px solid var(--border);
                position: sticky;
                top: 0;
                z-index: 100;
            }

            .header-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 15px 0;
            }

            .logo {
                font-size: 24px;
                font-weight: 600;
                color: var(--primary);
                text-decoration: none;
            }

            nav {
                display: flex;
                gap: 30px;
                align-items: center;
            }

            nav a {
                text-decoration: none;
                color: var(--text);
                font-size: 14px;
                transition: color 0.2s;
            }

            nav a:hover {
                color: var(--primary);
            }

            .cart-badge {
                position: relative;
            }

            .cart-icon {
                font-size: 20px;
            }

            .badge {
                position: absolute;
                top: -8px;
                right: -12px;
                background: var(--danger);
                color: white;
                border-radius: 50%;
                width: 20px;
                height: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 12px;
                font-weight: 600;
            }

            .btn {
                display: inline-block;
                padding: 10px 20px;
                border-radius: 6px;
                text-decoration: none;
                font-size: 14px;
                border: none;
                cursor: pointer;
                transition: all 0.2s;
                font-weight: 500;
            }

            .btn-primary {
                background-color: var(--primary);
                color: white;
            }

            .btn-primary:hover {
                background-color: var(--primary-dark);
            }

            .btn-success {
                background-color: var(--success);
                color: white;
            }

            .btn-success:hover {
                background-color: #15803d;
            }

            .btn-danger {
                background-color: var(--danger);
                color: white;
            }

            .btn-danger:hover {
                background-color: #b91c1c;
            }

            .alert {
                padding: 12px 16px;
                border-radius: 6px;
                margin-bottom: 20px;
                font-size: 14px;
            }

            .alert-success {
                background-color: #dcfce7;
                color: #166534;
                border-left: 4px solid var(--success);
            }

            .alert-error {
                background-color: #fee2e2;
                color: #991b1b;
                border-left: 4px solid var(--danger);
            }

            .alert-warning {
                background-color: #fef3c7;
                color: #92400e;
                border-left: 4px solid var(--warning);
            }

            /* Footer */
            footer {
                background-color: var(--bg-light);
                border-top: 1px solid var(--border);
                padding: 40px 0;
                margin-top: 60px;
            }

            .footer-content {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 30px;
                margin-bottom: 30px;
            }

            .footer-section h3 {
                font-size: 16px;
                margin-bottom: 15px;
                color: var(--text);
            }

            .footer-section ul {
                list-style: none;
            }

            .footer-section ul li {
                margin-bottom: 10px;
            }

            .footer-section a {
                color: var(--text-light);
                text-decoration: none;
                font-size: 14px;
                transition: color 0.2s;
            }

            .footer-section a:hover {
                color: var(--primary);
            }

            .footer-bottom {
                text-align: center;
                padding-top: 20px;
                border-top: 1px solid var(--border);
                color: var(--text-light);
                font-size: 14px;
            }

            main {
                min-height: calc(100vh - 200px);
            }

            /* Animations */
            @keyframes pulse {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.15); }
            }

            /* Responsiveness */
            @media (max-width: 768px) {
                nav {
                    gap: 15px;
                    font-size: 12px;
                }

                .header-content {
                    flex-wrap: wrap;
                }
            }
        </style>
    </head>
    <body>
        <!-- Header -->
        <header>
            <div class="container">
                <div class="header-content">
                    <a href="{{ route('home') }}" class="logo">🛍️ NoreXo</a>
                    
                    <nav>
                        <a href="{{ route('home') }}">Home</a>
                        
                        @auth
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                            @else
                                <a href="{{ route('orders.index') }}">My Orders</a>
                                <a href="{{ route('topup.index') }}" style="color: var(--success);">💰 Balance: Rp {{ number_format(auth()->user()->balance, 0, ',', '.') }}</a>
                            @endif
                            
                            @if(auth()->user()->isBuyer())
                                <a href="{{ route('cart.index') }}" class="cart-badge">
                                    <span class="cart-icon">🛒</span>
                                    <span class="badge" id="cart-count">0</span>
                                </a>
                            @endif

                            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger" style="padding: 8px 15px; font-size: 12px;">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary" style="padding: 8px 15px;">Login</a>
                            <a href="{{ route('register') }}" class="btn btn-success" style="padding: 8px 15px;">Register</a>
                        @endauth
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            <div class="container">
                @if ($errors->any())
                    <div class="alert alert-error">
                        <strong>Error:</strong> Please check the form below.
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-error">{{ session('error') }}</div>
                @endif

                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer>
            <div class="container">
                <div class="footer-content">
                    <div class="footer-section">
                        <h3>About NoreXo</h3>
                        <ul>
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">Careers</a></li>
                            <li><a href="#">Blog</a></li>
                        </ul>
                    </div>
                    <div class="footer-section">
                        <h3>Customer Service</h3>
                        <ul>
                            <li><a href="#">Contact Us</a></li>
                            <li><a href="#">FAQ</a></li>
                            <li><a href="#">Track Order</a></li>
                        </ul>
                    </div>
                    <div class="footer-section">
                        <h3>Legal</h3>
                        <ul>
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Terms & Conditions</a></li>
                            <li><a href="#">Return Policy</a></li>
                        </ul>
                    </div>
                    <div class="footer-section">
                        <h3>Follow Us</h3>
                        <ul>
                            <li><a href="#">Facebook</a></li>
                            <li><a href="#">Instagram</a></li>
                            <li><a href="#">Twitter</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p>&copy; {{ date('Y') }} NoreXo. All rights reserved.</p>
                </div>
            </div>
        </footer>

        <script>
            // Update cart count via Turbo.js
            function updateCartCount() {
                @auth
                    @if(auth()->user()->isBuyer())
                        fetch('{{ route("cart.count") }}')
                            .then(response => response.json())
                            .then(data => {
                                document.getElementById('cart-count').textContent = data.count;
                            });
                    @endif
                @endauth
            }

            // Update cart count on page load and on cart changes
            document.addEventListener('turbo:load', updateCartCount);
            document.addEventListener('DOMContentLoaded', updateCartCount);
        </script>
    </body>
</html>
