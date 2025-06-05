@extends('layouts.app')

@section('title', 'Cadastrar Produto')

@section('content')
<div class="container">
    <h1 class="mb-4">Cadastrar Novo Produto</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('cadastro-produtos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Passa um objeto vazio para o partial --}}
        @include('cadastro-produtos.partials.form', ['produto' => new \App\Models\Produto])

        <button type="submit" class="btn btn-primary">Cadastrar</button>
        <a href="{{ route('cadastro-produtos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
