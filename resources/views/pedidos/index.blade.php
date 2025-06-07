@extends('layouts.app')

@section('content')
<h2>Pedidos</h2>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Data</th>
            <th>Subtotal (R$)</th>
            <th>Desconto (R$)</th>
            <th>Frete (R$)</th>
            <th>Total (R$)</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pedidos as $pedido)
            <tr>
                <td>{{ $pedido->id }}</td>
                <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                <td>R$ {{ number_format($pedido->subtotal, 2, ',', '.') }}</td>
                <td>R$ {{ number_format($pedido->desconto, 2, ',', '.') }}</td>
                <td>R$ {{ number_format($pedido->frete, 2, ',', '.') }}</td>
                <td>R$ {{ number_format($pedido->total, 2, ',', '.') }}</td>
                <td>{{ ucfirst($pedido->status) }}</td>
                <td>
                    <a href="{{ route('pedidos.show', $pedido->id) }}" class="btn btn-sm btn-primary">Ver</a>
                    <form action="{{ route('pedidos.destroy', $pedido->id) }}" method="POST" style="display:inline-block" 
                          onsubmit="return confirm('Confirma exclusão do pedido?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Excluir</button>
                    </form>
                </td>
            </tr>
        @endforeach

        @if($pedidos->isEmpty())
            <tr><td colspan="8" class="text-center">Nenhum pedido encontrado.</td></tr>
        @endif
    </tbody>
</table>

{{ $pedidos->links() }}
@endsection
