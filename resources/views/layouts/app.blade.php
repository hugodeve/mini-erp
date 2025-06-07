<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini-ERP</title>
    <!-- Bootstrap CSS (via CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6f9;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .main-content {
            flex: 1 0 auto;
        }
        .navbar-brand {
            font-weight: bold;
            letter-spacing: 1px;
            font-size: 1.5rem;
        }
        .footer {
            background: #212529;
            color: #fff;
            padding: 16px 0;
            text-align: center;
            flex-shrink: 0;
        }
        .card-app {
            box-shadow: 0 2px 16px rgba(0,0,0,0.06);
            border-radius: 12px;
            background: #fff;
            padding: 2rem 2.5rem;
            margin-bottom: 2rem;
        }
        .navbar {
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('produtos.index') }}">Mini-ERP</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                    data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" 
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
    
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('produtos.index') }}">Produtos</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('cupons.index') }}">Cupons</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('pedidos.index') }}">Pedidos</a></li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cart.show') }}">
                            Carrinho 
                            @php $count = count(session()->get('cart', [])); @endphp
                            @if($count) <span class="badge bg-light text-dark">{{ $count }}</span> @endif
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container main-content">
        <div class="card-app mt-4">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </div>

    <footer class="footer mt-auto">
        &copy; {{ date('Y') }} Mini-ERP. Todos os direitos reservados.
    </footer>

    <!-- Bootstrap JS + Popper (via CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
