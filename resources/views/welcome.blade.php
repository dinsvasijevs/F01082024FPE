<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Silver Bank</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <style>
        /* Base styles */
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        /* Header styles */
        header {
            background-color: #003366;
            color: white;
            padding: 20px 0;
        }
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }
        /* Hero section styles */
        .hero {
            background-color: #004080;
            color: white;
            padding: 60px 0;
            text-align: center;
        }
        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
        }
        .hero p {
            font-size: 18px;
            margin-bottom: 30px;
        }
        .cta-button {
            background-color: #ff9900;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
        }
        /* Features section styles */
        .features {
            padding: 60px 0;
            background-color: white;
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }
        .feature-card {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
        }
        .feature-card img {
            width: 60px;
            height: 60px;
            margin-bottom: 20px;
        }
        /* Footer styles */
        footer {
            background-color: #003366;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>
<header>
    <div class="container">
        <nav>
            <div class="logo">Silver Bank</div>
            <div class="nav-links">
                <a href="#home">Home</a>
                <a href="#accounts">Accounts</a>
                <a href="#investments">Investments</a>
                <a href="#support">Support</a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                @endif
            </div>
        </nav>
    </div>
</header>

<main>
    <section class="hero">
        <div class="container">
            <h1>Welcome to Silver Bank</h1>
            <p>Secure, reliable, and innovative banking solutions for your financial needs.</p>
            <a href="#" class="cta-button">Open an Account</a>
        </div>
    </section>

    <section class="features">
        <div class="container">
            <div class="features-grid">
                <div class="feature-card">
                    <img src="/api/placeholder/60/60" alt="Online Banking">
                    <h2>Online Banking</h2>
                    <p>Manage your accounts, transfer funds, and pay bills with ease.</p>
                </div>
                <div class="feature-card">
                    <img src="/api/placeholder/60/60" alt="Mobile App">
                    <h2>Mobile App</h2>
                    <p>Bank on-the-go with our secure and user-friendly mobile application.</p>
                </div>
                <div class="feature-card">
                    <img src="/api/placeholder/60/60" alt="Investment Services">
                    <h2>Investment Services</h2>
                    <p>Grow your wealth with our expert investment advice and tools.</p>
                </div>
            </div>
        </div>
    </section>
</main>

<footer>
    <div class="container">
        <p>&copy; 2024 Silver Bank. All rights reserved.</p>
    </div>
</footer>
</body>
</html>
