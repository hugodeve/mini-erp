@extends('layouts.app')

@section('content')
<h2>Pedido #{{ $pedido->id }}</h2>

<div class="mb-4">
    <h5>Dados Gerais</h5>
    <ul class="list-unstyled">
        <li><strong>Data:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</li>
        <li><strong>Status:</strong> {{ ucfirst($pedido->status) }}</li>
        <li><strong>CEP:</strong> {{ $pedido->cep }}</li>
        <li><strong>Endereço:</strong> 
            {{ $pedido->logradouro }}, Nº {{ $pedido->numero }} 
            {{ $pedido->complemento ? ' ('.$pedido->complemento.')' : '' }}, 
            {{ $pedido->bairro }} - {{ $pedido->cidade }}/{{ $pedido->uf }}
        </li>
        <li><strong>Subtotal:</strong> R$ {{ number_format($pedido->subtotal, 2, ',', '.') }}</li>
        <li><strong>Desconto:</strong> R$ {{ number_format($pedido->desconto, 2, ',', '.') }}</li>
        <li><strong>Frete:</strong> R$ {{ number_format($pedido->frete, 2, ',', '.') }}</li>
        <li><strong>Total:</strong> R$ {{ number_format($pedido->total, 2, ',', '.') }}</li>
    </ul>
</div>

<div>
    <h5>Itens do Pedido</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Produto ID</th>
                <th>Variação</th>
                <th>Quantidade</th>
                <th>Preço Unitário (R$)</th>
                <th>Total Item (R$)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedido->items as $item)
                <tr>
                    <td>{{ $item['produto_id'] }}</td>
                    <td>{{ $item['variacao'] ?? '—' }}</td>
                    <td>{{ $item['quantidade'] }}</td>
                    <td>{{ number_format($item['preco_unitario'], 2, ',', '.') }}</td>
                    <td>{{ number_format($item['preco_unitario'] * $item['quantidade'], 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<a href="{{ route('pedidos.index') }}" class="btn btn-secondary">Voltar</a>
@endsection
