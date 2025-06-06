@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Produtos Escaneados</h1>

    {{-- Exibir mensagem --}}
    @if ($codigosEscaneados->isEmpty())
        <div class="alert alert-warning alerta-vazio">
            Nenhum código EAN escaneado encontrado.
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Botões principais --}}
    <div class="d-flex flex-column flex-md-row align-items-stretch gap-2 flex-wrap w-100 mb-3">

        {{-- Botão Gerar Etiquetas --}}
        @if ($codigosEscaneados->isNotEmpty())
            <a href="{{ route('produtos.etiquetas') }}" class="btn btn-primary">
                <i class="fa-solid fa-print"></i> Gerar Etiquetas
            </a>
        @endif

        {{-- Botão Recarregar --}}
        <a href="{{ route('produtos.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-rotate-right"></i> Recarregar
        </a>

        {{-- Formulário Adicionar Código --}}
        <form action="{{ route('ean.adicionar') }}" method="POST" class="flex-grow-1">
            @csrf
            <div class="input-group">
                <input
                    type="number"
                    name="codigo_ean"
                    id="input-ean"
                    class="form-control"
                    placeholder="Digite o código EAN"
                    required
                    min="1"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                >
                <button class="btn btn-success" type="submit">
                    <i class="fa fa-plus"></i> Adicionar Código
                </button>
            </div>
        </form>

        {{-- Botão Limpar Códigos --}}
        <form action="{{ route('ean.limpar.todos') }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir TODOS os códigos?')" class="ms-md-auto">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger w-100 w-md-auto">
                <i class="fa fa-trash"></i> Limpar Códigos
            </button>
        </form>
    </div>

    {{-- Lista códigos EAN com botão para abrir modal --}}
    <ul class="list-group mb-3" id="lista-codigos-ean">
        @foreach($codigosEscaneados as $item)
            <li class="list-group-item d-flex justify-content-between align-items-center" data-codigo="{{ $item->codigo_ean }}">
                <span>{{ $item->codigo_ean }}</span>
                <div>
                    <button class="btn btn-sm btn-secondary me-2" onclick="mostrarDetalhesProduto('{{ $item->codigo_ean }}')">
                        <i class="fa-solid fa-info"></i> Detalhes
                    </button>

                    <form action="{{ route('ean.deletar', $item->codigo_ean) }}" method="POST" onsubmit="return confirm('Deseja excluir este código EAN?')" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>

    <!-- Paginação -->
    {{ $codigosEscaneados->links() }}
</div>

<!-- Modal de detalhes do produto -->
<div class="modal fade" id="modalDetalhesProduto" tabindex="-1" aria-labelledby="modalDetalhesProdutoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDetalhesProdutoLabel">Detalhes do Produto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <div id="conteudoDetalhes"></div>
      </div>
    </div>
  </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
function mostrarDetalhesProduto(codigoEAN) {
    const modal = new bootstrap.Modal(document.getElementById('modalDetalhesProduto'));
    const conteudo = document.getElementById('conteudoDetalhes');
    conteudo.innerHTML = '';
    modal.show();

    fetch(`/api/produto/${codigoEAN}`)
        .then(response => {
            if (!response.ok) throw new Error('Produto não encontrado');
            return response.json();
        })
        .then(produto => {
            let html = `
                <div style="display: flex; gap: 20px; align-items: stretch; flex-wrap: wrap;">
                    <div style="flex-shrink: 0; display: flex; align-items: center; justify-content: center; width: 200px;">
                        ${
                            produto.imagem
                                ? `<img src="${produto.imagem}" style="max-width: 180px; max-height: 100%; object-fit: contain;" alt="${produto.nome || 'Imagem do produto'}">`
                                : '<div style="width: 180px; height: 100%; display: flex; align-items: center; justify-content: center; color: #888;">Sem imagem</div>'
                        }
                    </div>
                    <div style="flex: 1; min-width: 250px;">
                        <table class="table table-bordered mb-0">
                            <tbody>
                                <tr><th>Código EAN</th><td>${produto.codigo_ean ?? '-'}</td></tr>
                                <tr><th>Nome</th><td>${produto.nome ?? '-'}</td></tr>
                                <tr><th>Descrição</th><td>${produto.descricao ?? '-'}</td></tr>
                                <tr><th>Fabricante</th><td>${produto.fabricante ?? '-'}</td></tr>
                                <tr><th>Ano de Fabricação</th><td>${produto.ano_fabricacao ?? '-'}</td></tr>
                                <tr><th>Vencimento</th><td>${produto.data_vencimento ? new Date(produto.data_vencimento).toLocaleDateString('pt-BR') : '-'}</td></tr>
                                <tr><th>Valor</th><td>R$ ${produto.valor != null ? Number(produto.valor).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '-'}</td></tr>
                                <tr><th>Data de Cadastro</th><td>${produto.data_cadastro ? new Date(produto.data_cadastro).toLocaleString('pt-BR') : '-'}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
            conteudo.innerHTML = html;
        })
        .catch(err => {
            conteudo.innerHTML = `<div class="alert alert-danger">Erro ao carregar detalhes: ${err.message}</div>`;
        });
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const lista = document.getElementById('lista-codigos-ean');
    const alertaVazio = document.querySelector('.alerta-vazio');
    let codigosAtuais = Array.from(lista.querySelectorAll('li')).map(el => el.dataset.codigo);

    function atualizarLista() {
        fetch('/api/eans')
            .then(response => response.json())
            .then(codigos => {
                // Adiciona novos códigos
                const novos = codigos.filter(c => !codigosAtuais.includes(c));
                const alertaExistente = document.querySelector('.alerta-vazio');
                if (novos.length > 0 && alertaExistente) {
                    alertaExistente.remove();
                }

                novos.forEach(codigo => {
                    codigosAtuais.push(codigo);
                    const item = document.createElement('li');
                    item.className = 'list-group-item d-flex justify-content-between align-items-center';
                    item.setAttribute('data-codigo', codigo);
                    item.innerHTML = `
                        <span>${codigo}</span>
                        <div>
                            <button class="btn btn-sm btn-secondary me-2 btn-detalhes" data-codigo="${codigo}">
                                <i class="fa-solid fa-info"></i> Detalhes
                            </button>
                            <form action="/produtos/ean/${codigo}" method="POST" onsubmit="return confirm('Deseja excluir este código EAN?')" style="display:inline;">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    `;
                    lista.appendChild(item);
                });

                // Remove códigos que não estão mais na json
                const removidos = codigosAtuais.filter(c => !codigos.includes(c));
                removidos.forEach(codigo => {
                    const el = lista.querySelector(`li[data-codigo="${codigo}"]`);
                    if (el) el.remove();
                });

                // Atualiza a lista local
                codigosAtuais = codigos;

                // Se lista estiver vazia, mostra alerta
                if (codigosAtuais.length === 0 && !document.querySelector('.alerta-vazio')) {
                    const alerta = document.createElement('div');
                    alerta.className = 'alert alert-warning alerta-vazio';
                    alerta.innerText = 'Nenhum código EAN encontrado.';
                    lista.parentElement.insertBefore(alerta, lista);
                }
            })
            .catch(error => console.error('Erro ao buscar EANs:', error));
    }

    // Botões de detalhes
    lista.addEventListener('click', function (e) {
        if (e.target.closest('.btn-detalhes')) {
            const codigo = e.target.closest('.btn-detalhes').dataset.codigo;
            mostrarDetalhesProduto(codigo);
        }
    });

    setInterval(atualizarLista, 5000);
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const eanInput = document.getElementById('input-ean');
        if (eanInput) {
            eanInput.focus();
        }
    });
</script>
@endsection
