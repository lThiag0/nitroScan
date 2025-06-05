<div class="mb-3">
    <label for="codigo_ean" class="form-label">Código EAN</label>
    <input
        type="number"
        name="codigo_ean"
        id="codigo_ean"
        class="form-control"
        value="{{ old('codigo_ean', $produto->codigo_ean) }}"
        @if(isset($modo) && $modo === 'editar') readonly @endif
        required
    >
</div>

<div class="mb-3">
    <label for="nome" class="form-label">Nome</label>
    <input
        type="text"
        name="nome"
        class="form-control"
        value="{{ old('nome', $produto->nome) }}"
        required
    >
</div>

<div class="mb-3">
    <label for="descricao" class="form-label">Descrição</label>
    <textarea
        name="descricao"
        class="form-control"
        required
    >{{ old('descricao', $produto->descricao) }}</textarea>
</div>

<div class="mb-3">
    <label for="fabricante" class="form-label">Fabricante</label>
    <input
        type="text"
        name="fabricante"
        class="form-control"
        value="{{ old('fabricante', $produto->fabricante) }}"
        required
    >
</div>

<div class="mb-3">
    <label for="ano_fabricacao" class="form-label">Ano de Fabricação</label>
    <input
        type="number"
        name="ano_fabricacao"
        class="form-control"
        value="{{ old('ano_fabricacao', $produto->ano_fabricacao) }}"
        min="1900"
        max="{{ date('Y') }}"
        required
    >
</div>

<div class="mb-3">
    <label for="data_vencimento" class="form-label">Data de Vencimento</label>
    <input
        type="date"
        name="data_vencimento"
        class="form-control"
        value="{{ old('data_vencimento', optional($produto->data_vencimento)->format('Y-m-d')) }}"
        required
    >
</div>

<div class="mb-3">
    <label for="valor" class="form-label">Valor</label>
    <input
        type="number"
        name="valor"
        class="form-control"
        value="{{ old('valor', $produto->valor) }}"
        step="0.01"
        min="0"
        required
    >
</div>

<div class="mb-3">
    <label for="imagem" class="form-label">Imagem</label>
    
    @if(isset($modo) && $modo === 'editar' && $produto->imagem)
        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#imagemModal" data-imagem="{{ asset('storage/' . $produto->imagem) }}">
            <i class="fa fa-image"></i> Visualizar Imagem
        </button>
    @endif

    <input
        type="file"
        name="imagem"
        class="form-control mt-2"
        @if(!isset($modo) || $modo !== 'editar' || !$produto->imagem) required @endif
    >
</div>
