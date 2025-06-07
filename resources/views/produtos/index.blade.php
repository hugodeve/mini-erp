@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Produtos</h2>
    <a href="{{ route('produtos.create') }}" class="btn btn-success">Novo Produto</a>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Preço (R$)</th>
            <th>Variações / Estoque</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($produtos as $produto)
            <tr>
                <td>{{ $produto->id }}</td>
                <td>{{ $produto->nome }}</td>
                <td>{{ number_format($produto->preco, 2, ',', '.') }}</td>
                <td>
                    <ul class="list-unstyled">
                        @foreach($produto->estoques as $estoque)
                            <li>
                                <strong>{{ $estoque->variacao ?? 'Sem variação' }}</strong>: 
                                {{ $estoque->quantidade }}
                            </li>
                        @endforeach
                    </ul>
                </td>
<td>
    <a href="{{ route('produtos.edit', $produto->id) }}" class="btn btn-sm btn-primary">Editar</a>
    <form action="{{ route('produtos.destroy', $produto->id) }}" method="POST" style="display:inline-block" 
          onsubmit="return confirm('Confirma exclusão?')">
        @csrf
        @method('DELETE')
        <button class="btn btn-sm btn-danger">Excluir</button>
    </form>
    @foreach($produto->estoques as $estoque)
        <form action="{{ route('cart.add', $produto->id) }}" method="POST" style="display:inline-block; margin-top: 4px;">
            @csrf
            @if($estoque->variacao)
                <input type="hidden" name="variacao" value="{{ $estoque->variacao }}">
            @endif
            <input type="hidden" name="quantidade" value="1">
            <button class="btn btn-sm btn-success">
                Comprar{{ $estoque->variacao ? ' (' . $estoque->variacao . ')' : '' }}
            </button>
        </form>
    @endforeach
</td>
            </tr>
        @endforeach
        @if($produtos->isEmpty())
            <tr><td colspan="5" class="text-center">Nenhum produto cadastrado.</td></tr>
        @endif
    </tbody>
</table>
@endsection
