<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'NitroScan')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap (opcional) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonte Google -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f9fc;
            margin: 0;
            padding-bottom: 60px;
        }

        .navbar-brand {
            font-weight: bold;
            color: #2575fc;
        }

        footer {
            background-color: #f0f0f0;
            text-align: center;
            padding: 20px 0;
            font-size: 0.9rem;
            border-top: 1px solid #ccc;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand me-5" href="/">NitroScan</a>

            <!-- Botão mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Itens da navbar -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto me-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('produtos.index') }}">Produtos Escaneados</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cadastro-produtos.index') }}">Cadastrar Produto</a>
                    </li>
                </ul>

                <!-- Menu de usuário -->
                @auth('funcionario')
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-user-circle me-2"></i>
                            {{ Auth::guard('funcionario')->user()->nome ?? 'Funcionário' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form method="POST" action="{{ route('funcionario.logout') }}">
                                    @csrf
                                    <button class="dropdown-item" type="submit">
                                        <i class="fa-solid fa-right-from-bracket me-2"></i> Sair
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
                @endauth
            </div>
        </div>
    </nav>




    <!-- Conteúdo principal -->
    <main class="py-4">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <!-- Modal para visualizar imagem -->
    <div class="modal fade" id="imagemModal" tabindex="-1" aria-labelledby="imagemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagemModalLabel">Visualizar Imagem</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body text-center p-3">
                <img src="" id="imagemModalSrc" alt="Imagem Produto" style="width: 100%; height: auto; border-radius: 6px;">
            </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        &copy; {{ date('Y') }} NitroScan. Todos os direitos reservados.
    </footer>

    <!-- JS (opcional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
                }, 3000);
            });
        });
    </script>

    @yield('scripts')

</body>
</html>
