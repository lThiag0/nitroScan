@extends('layouts.login')

@section('content')
<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        background: linear-gradient(to right, #007bff, #00c6ff);
    }

    .full-height-wrapper {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .login-box {
        background-color: #fff;
        border-radius: 12px;
        padding: 40px 30px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        width: 100%;
        max-width: 450px;
    }

    .login-box .form-control {
        border-radius: 8px;
    }

    .login-box .btn {
        border-radius: 8px;
    }

    .login-box .form-label {
        font-weight: bold;
        color: #333;
    }

    .login-header {
        text-align: center;
        margin-bottom: 25px;
    }

    .login-header h3 {
        margin: 0;
        font-weight: 600;
        color: #007bff;
    }

    .login-header i {
        font-size: 2rem;
        color: #007bff;
        margin-bottom: 10px;
    }
</style>

<div class="full-height-wrapper">
    <div class="login-box">
        <div class="login-header">
            <i class="fa-solid fa-user-lock"></i>
            <h3>Login de Funcionário</h3>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('funcionario.login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">E-mail Corporativo</label>
                <input type="email" class="form-control" name="email" id="email" required autofocus>
            </div>

            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" name="password" id="senha" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="fa-solid fa-right-to-bracket"></i> Entrar
            </button>

            <a href="{{ route('welcome') }}" class="btn btn-secondary w-100 mt-2">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
        </form>
    </div>
</div>
@endsection
