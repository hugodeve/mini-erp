@extends('layouts.app')

@section('content')
@php
    $isEdit = $produto->exists;
@endphp

<h2>{{ $isEdit ? 'Editar Produto #' . $produto->id : 'Novo Produto' }}</h2>

<form action="{{ $isEdit ? route('produtos.update', $produto->id) : route('produtos.store') }}" method="POST">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" name="nome" id="nome" class="form-control" 
               value="{{ old('nome', $produto->nome) }}" required>
        @error('nome')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-3">
        <label for="preco" class="form-label">Preço (R$)</label>
        <input type="number" name="preco" id="preco" step="0.01" 
               class="form-control" value="{{ old('preco', $produto->preco) }}" required>
        @error('preco')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    {{-- Variações (campo que salva array JSON) --}}
    <div class="mb-3">
        <label class="form-label">Variações (opcional)</label>
        <small class="form-text text-muted">Insira variações separadas por vírgula: ex. "P,M,G"</small>
        <input type="text" name="variacoes_input" id="variacoes_input" class="form-control"
               value="{{ old('variacoes_input', $produto->variacoes ? implode(',', $produto->variacoes) : '') }}">
        @error('variacoes')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <hr>
    <h5>Estoque por Variação</h5>
    <p class="text-muted">Se não tiver variações, deixe variação em branco e informe a quantidade.</p>
    <div id="container-estoques">
        @php
            $oldEstoques = old('estoques', $estoques->toArray());
        @endphp

        @if(!empty($oldEstoques))
            @foreach($oldEstoques as $i => $item)
                <div class="row mb-2 estoque-item" data-index="{{ $i }}">
                    <div class="col-md-5">
                        <input type="hidden" name="estoques[{{ $i }}][id]" 
                               value="{{ $item['id'] ?? '' }}">
                        <label class="form-label">Variação</label>
                        <input type="text" class="form-control" 
                               name="estoques[{{ $i }}][variacao]" 
                               value="{{ $item['variacao'] ?? '' }}">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Quantidade</label>
                        <input type="number" class="form-control" 
                               name="estoques[{{ $i }}][quantidade]" 
                               value="{{ $item['quantidade'] ?? 0 }}" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm btn-remover-estoque">
                            Remover
                        </button>
                    </div>
                </div>
            @endforeach
        @else
            {{-- 1 linha vazia por padrão --}}
            <div class="row mb-2 estoque-item" data-index="0">
                <div class="col-md-5">
                    <label class="form-label">Variação</label>
                    <input type="text" class="form-control" 
                           name="estoques[0][variacao]" value="">
                </div>
                <div class="col-md-5">
                    <label class="form-label">Quantidade</label>
                    <input type="number" class="form-control" 
                           name="estoques[0][quantidade]" value="0" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm btn-remover-estoque">
                        Remover
                    </button>
                </div>
            </div>
        @endif
    </div>
    <button type="button" id="btn-add-estoque" class="btn btn-secondary btn-sm mb-3">
        Adicionar Variação/Estoque
    </button>

    <div class="mt-3">
        <button class="btn btn-primary">{{ $isEdit ? 'Atualizar' : 'Salvar' }}</button>
        <a href="{{ route('produtos.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

@push('scripts')
<script>
    (function() {
        let container = document.getElementById('container-estoques');
        let addBtn = document.getElementById('btn-add-estoque');

        addBtn.addEventListener('click', function() {
            // Conta quantos itens já existem
            let index = container.querySelectorAll('.estoque-item').length;
            let row = document.createElement('div');
            row.classList.add('row', 'mb-2', 'estoque-item');
            row.setAttribute('data-index', index);

            row.innerHTML = `
                <div class="col-md-5">
                    <label class="form-label">Variação</label>
                    <input type="text" class="form-control" name="estoques[${index}][variacao]" value="">
                </div>
                <div class="col-md-5">
                    <label class="form-label">Quantidade</label>
                    <input type="number" class="form-control" name="estoques[${index}][quantidade]" value="0" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm btn-remover-estoque">
                        Remover
                    </button>
                </div>
            `;
            container.appendChild(row);
            attachRemoveListener(row.querySelector('.btn-remover-estoque'));
        });

        function attachRemoveListener(button) {
            button.addEventListener('click', function() {
                let row = this.closest('.estoque-item');
                row.remove();
                // Reindexar todos os itens
                reindexar();
            });
        }

        function reindexar() {
            let rows = container.querySelectorAll('.estoque-item');
            rows.forEach((row, i) => {
                row.setAttribute('data-index', i);
                // Ajusta name dos inputs
                row.querySelectorAll('input').forEach(input => {
                    let name = input.getAttribute('name'); 
                    // nome original é "estoques[N][campo]"
                    let campo = name.split(']')[1]; // pega "[campo]"
                    input.setAttribute('name', `estoques[${i}]${campo}`);
                });
            });
        }

        document.querySelectorAll('.btn-remover-estoque').forEach(btn => {
            attachRemoveListener(btn);
        });
    })();
</script>
@endpush
@endsection
