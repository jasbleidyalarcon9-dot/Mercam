<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Mercampo')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="{{ asset('css/chatbot.css') }}" rel="stylesheet">
</head>
<body class="dashboard-body">

    <header class="header-esmeralda d-flex justify-content-between align-items-center">
        <h1 style="margin:0;">
            <a href="{{ route('home') }}" style="display:flex; align-items:center; gap:10px; color:inherit; text-decoration:none; font-size:2rem;">
                <img src="{{ asset('img/Logomercam.png') }}" alt="Logo Mercam" style="height:42px; width:auto; display:block;">
                <span style="font-family: Georgia, 'Times New Roman', serif; font-weight: 700; letter-spacing: 0.5px; color: #4b2a1d;">Mercam</span>
            </a>
        </h1>

        <nav class="d-flex align-items-center gap-3" style="margin-right:30px;">
            <a href="{{ route('home') }}">Inicio</a>
            <a href="{{ route('home') }}#productos">Productos</a>
            <a href="{{ route('asistente.index') }}">Asistente IA</a>

            @auth
                @php($rol = auth()->user()->role?->name)

                {{-- Menú según el rol: cada tipo de cuenta ve solo lo suyo --}}
                @if ($rol === 'administrador')
                    <a href="{{ route('admin.dashboard') }}">Admin</a>
                @elseif ($rol === 'productor')
                    <a href="{{ route('posts.mine') }}">Mis productos</a>
                    <a href="{{ route('productor.dashboard') }}">Panel</a>
                @else
                    <a href="{{ route('carrito.index') }}" class="nav-cart">🛒 Carrito</a>
                    <a href="{{ route('pedidos.index') }}">Mis pedidos</a>
                @endif

                <a href="{{ route('perfil.edit') }}" class="d-flex align-items-center" style="gap:6px; text-decoration:none; color:inherit;">
                    @if (auth()->user()->avatar)
                        <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="user-avatar">
                    @endif
                    Mi perfil
                </a>

                <a href="{{ route('notificaciones.index') }}">
                    🔔 Notificaciones
                    @php($noLeidas = auth()->user()->unreadNotifications()->count())
                    @if ($noLeidas > 0)
                        <span style="background:#c0392b; color:#fff; border-radius:50%; padding:1px 7px; font-size:12px;">{{ $noLeidas }}</span>
                    @endif
                </a>

                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-login" style="background:none; border:none; padding:0;">Salir</button>
                </form>
            @else
                <a href="{{ route('login') }}">Iniciar sesión</a>
                <a href="{{ route('register') }}" class="nav-cart">Registrarme</a>
            @endauth
        </nav>
    </header>

    <main>
        @if (session('success'))
            <div class="success-message" style="margin:15px 30px;">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="success-message" style="margin:15px 30px; background:#c0392b;">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>

    <footer class="footer-section">
        <div class="footer-container d-flex justify-content-between flex-wrap">
            <div>
                <h3 style="display:flex; align-items:center; gap:10px; margin:0 0 10px;">
                    <img src="{{ asset('img/Logomercam.png') }}" alt="Logo Mercam" style="height:32px; width:auto; display:block;">
                    <span style="font-family: Georgia, 'Times New Roman', serif; font-weight: 700; letter-spacing: 0.5px; color: #f5d9a2;">Mercam</span>
                </h3>
                <p>Conectamos familias campesinas con consumidores comprometidos. Más campo, menos cadena.</p>
            </div>
            <div>
                <strong>Mercado</strong>
                <ul class="footer-list">
                    <li><a href="{{ route('posts.index') }}" style="color:inherit; text-decoration:none;">Productos</a></li>
                    <li><a href="{{ route('legal.politica-datos') }}" style="color:inherit; text-decoration:none;">Tratamiento de datos</a></li>
                    @guest
                        <li><a href="{{ route('register') }}" style="color:inherit; text-decoration:none;">Vender en Mercam</a></li>
                    @endguest
                </ul>
            </div>
            <div>
                <strong>Contacto</strong>
                <ul class="footer-list">
                    <li><a href="tel:+573000000000" style="color:inherit; text-decoration:none;">+57 300 000 0000</a></li>
                    <li><a href="mailto:tuniversidad801@gmail.com" style="color:inherit; text-decoration:none;">tuniversidad801@gmail.com</a></li>
                    <li><a href="{{ route('manual.uso') }}" style="color:inherit; text-decoration:none;">Manual de uso</a></li>
                    <li><a href="{{ route('asistente.index') }}" style="color:inherit; text-decoration:none;">Asistente virtual Campi</a></li>
                </ul>
            </div>
        </div>
        <div class="header-derechos">
            <div class="derechos-box">
                <h6>&copy; {{ date('Y') }} Mercampo — San Diego de Ubaté</h6>
            </div>
        </div>
    </footer>

    {{-- Asistente virtual flotante en todas las páginas (en /asistente ya está a pantalla completa) --}}
    @unless (request()->routeIs('asistente.index'))
        <x-chatbot modo="flotante" />
    @endunless

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/chatbot.js') }}" defer></script>
</body>
</html>
