<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>NitroScan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Fonte moderna -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />

    <!-- Ícones Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: #ffffff;
            color: #1a237e; 
            display: flex;
            flex-direction: column;
        }

        body > *:not(footer) {
            flex-grow: 1;
        }

        header {
            background-color: #2575fc; 
            color: white;
            padding: 70px 20px;
            text-align: center;
        }

        .logo {
            font-size: 3.5rem;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }

        header p {
            font-size: 1.25rem;
            margin: 0;
            font-weight: 400;
        }

        .main-box {
            background: #f9f9f9;
            border-radius: 20px;
            padding: 50px;
            max-width: 1000px;
            margin: 60px auto;
            display: flex;
            align-items: center;
            gap: 40px;
            flex-wrap: wrap;
            border: 1px solid #e0e0e0;
        }

        .content-left {
            flex: 1 1 400px;
        }

        .content-left h2 {
            font-size: 2.2rem;
            margin-bottom: 30px;
            color: #2575fc;
        }

        .buttons {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #2575fc;
            color: white;
            padding: 16px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            width: 100%;
            max-width: 300px;
            min-width: 250px;
            transition: background-color 0.2s ease;
        }

        .action-btn:hover {
            background-color: #1a5ed9;
        }

        .action-btn i {
            margin-right: 12px;
            font-size: 1.3rem;
        }

        .content-right {
            flex: 1 1 400px;
            text-align: center;
        }

        .content-right img {
            width: 100%;
            max-width: 600px;
            border-radius: 16px;
        }

        footer {
            background: #f4f6f8;
            text-align: center;
            padding: 25px 10px;
            font-size: 0.95rem;
            color: #444;
            border-top: 1px solid #ddd;
            flex-shrink: 0;
        }

        @media (max-width: 768px) {
            .main-box {
                flex-direction: column;
                padding: 40px 20px;
                margin: 40px 20px;
            }

            .content-left {
                width: 100%;
                text-align: center;
            }

            .buttons {
                display: flex;
                flex-direction: column;
                align-items: center;
                width: 100%;
            }

            .action-btn {
                width: 90%;
                min-width: unset;
                max-width: unset;
                font-size: 1.05rem;
            }

            .content-right {
                margin-top: 30px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">NitroScan</div>
        <p>Escaneie, gerencie e controle seus produtos com facilidade.</p>
    </header>

    <div class="main-box">
        <div class="content-left">
            <h2>Comece agora</h2>
            <div class="buttons">
                <a href="{{ route('produtos.index') }}" class="action-btn">
                    <i class="fas fa-barcode"></i> Códigos Escaneados
                </a>
                <a href="{{ route('cadastro-produtos.index') }}" class="action-btn">
                    <i class="fas fa-plus-circle"></i> Cadastrar Produto
                </a>
            </div>
        </div>
        <div class="content-right">
            <img src="{{ asset('images/pcMobileScan.png') }}" alt="Aplicativo NitroScan em celular" />
        </div>
    </div>

    <footer>
        &copy; {{ date('Y') }} NitroScan. Todos os direitos reservados.
    </footer>
</body>
</html>
