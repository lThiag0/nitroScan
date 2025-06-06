@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Cadastro de Produtos</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3 row align-items-center">
        <div class="col-md-auto mb-2 mb-md-0">
            <a href="{{ route('cadastro-produtos.create') }}" class="btn btn-primary w-100 w-md-auto">Cadastrar Novo Produto</a>
        </div>

        <div class="col">
            <form action="{{ route('cadastro-produtos.search') }}" method="GET" class="d-flex gap-2 flex-column flex-md-row">
                <input type="text" name="query" class="form-control" placeholder="Buscar por EAN ou nome" value="{{ request('query') }}">
                <button type="submit" class="btn btn-outline-secondary">Buscar</button>
            </form>
        </div>
    </div>

    <form method="GET" action="{{ route('cadastro-produtos.index') }}" class="mb-3">
        <label for="fabricante">Filtrar por Fabricante:</label>
        <select name="fabricante" id="fabricante" class="form-select" onchange="this.form.submit()">
            <option value="">Todos</option>
            @foreach($fabricantes as $fab)
                <option value="{{ $fab }}" @if($fab == $fabricante) selected @endif>{{ $fab }}</option>
            @endforeach
        </select>
    </form>

    @if($cadastroProdutos->isEmpty())
        <p>Nenhum produto encontrado.</p>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Código EAN</th>
                        <th>Nome</th>
                        <th>Fabricante</th>
                        <th>Ano Fabricação</th>
                        <th>Vencimento</th>
                        <th>Valor</th>
                        <th>Imagem</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cadastroProdutos as $produto)
                        <tr>
                            <td>{{ $produto->codigo_ean }}</td>
                            <td>{{ $produto->nome }}</td>
                            <td>{{ $produto->fabricante }}</td>
                            <td>{{ $produto->ano_fabricacao }}</td>
                            <td>{{ $produto->data_vencimento ? \Carbon\Carbon::parse($produto->data_vencimento)->format('d/m/Y') : '' }}</td>
                            <td>R$ {{ number_format($produto->valor, 2, ',', '.') }}</td>
                            <td class="text-center">
                                @if($produto->imagem)
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#imagemModal" data-img="{{ asset('storage/'.$produto->imagem) }}">
                                        Visualizar
                                    </button>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex flex-wrap justify-content-center gap-1">
                                    <a href="{{ route('cadastro-produtos.edit', $produto) }}" class="btn btn-warning btn-sm" title="Editar">
                                        <i class="fa fa-pen"></i>
                                    </a>

                                    <form action="{{ route('cadastro-produtos.destroy', $produto) }}" method="POST" onsubmit="return confirm('Deseja deletar este produto?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" title="Deletar">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $cadastroProdutos->withQueryString()->links() }}
    @endif
</div>
@endsection

@section('scripts')

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imagemModal = document.getElementById('imagemModal');
    const imagemModalSrc = document.getElementById('imagemModalSrc');

    imagemModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const imgSrc = button.getAttribute('data-img');
        imagemModalSrc.src = imgSrc;
    });

    imagemModal.addEventListener('hidden.bs.modal', function() {
        imagemModalSrc.src = '';
    });
});
</script>
@endsection
