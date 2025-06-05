@extends('layouts.app')

@section('title', 'Editar Produto')

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Produto</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('cadastro-produtos.update', $produto) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @include('cadastro-produtos.partials.form', ['produto' => $produto, 'modo' => 'editar'])

        <button type="submit" class="btn btn-success">Atualizar</button>
        <a href="{{ route('cadastro-produtos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
@section('scripts')
    <script>
        var imagemModal = document.getElementById('imagemModal');
        var imagemModalSrc = document.getElementById('imagemModalSrc');

        imagemModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var imagemUrl = button.getAttribute('data-imagem');
            imagemModalSrc.src = imagemUrl;
        });

        imagemModal.addEventListener('hidden.bs.modal', function () {
            imagemModalSrc.src = '';
        });
    </script>
@endsection
