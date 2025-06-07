@extends('layouts.app')

@section('content')
<h2>Carrinho de Compras</h2>

@if($itens)
    <table class="table table-bordered mb-4">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Variação</th>
                <th>Quantidade</th>
                <th>Preço Unitário (R$)</th>
                <th>Total Item (R$)</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($itens as $key => $item)
                <tr>
                    <td>{{ $item['produto']->nome }}</td>
                    <td>{{ $item['variacao'] ?? '—' }}</td>
                    <td>{{ $item['quantidade'] }}</td>
                    <td>{{ number_format($item['preco_unitario'], 2, ',', '.') }}</td>
                    <td>{{ number_format($item['total_item'], 2, ',', '.') }}</td>
                    <td>
                        <form action="{{ route('cart.remove', $key) }}" method="POST">
                            @csrf
                            <button class="btn btn-sm btn-danger">Remover</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Resumo do pedido --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <h5>Subtotal:</h5>
            <p>R$ {{ number_format($subtotal, 2, ',', '.') }}</p>
        </div>
        <div class="col-md-4">
            <h5>Frete:</h5>
            <p>R$ {{ number_format($frete, 2, ',', '.') }}</p>
        </div>
        <div class="col-md-4">
            <h5>Desconto:</h5>
            <p>R$ {{ number_format($desconto, 2, ',', '.') }}</p>
        </div>
    </div>
    <h4>Total: R$ {{ number_format($total, 2, ',', '.') }}</h4>

    {{-- Formulário de cupom --}}
    <form action="{{ route('cart.applyCoupon') }}" method="POST" class="mt-3 mb-4">
        @csrf
        <div class="input-group">
            <input type="text" name="codigo_cupom" class="form-control" 
                   placeholder="Código do Cupom" value="{{ $cupomAplicado ?? '' }}">
            <button class="btn btn-secondary" type="submit">Aplicar Cupom</button>
        </div>
        @error('codigo_cupom')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </form>

    {{-- Botão para checkout --}}
    <a href="{{ route('cart.checkoutForm') }}" class="btn btn-success">Finalizar Pedido</a>
@else
    <p>Carrinho vazio.</p>
@endif

@endsection
