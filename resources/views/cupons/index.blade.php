@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Cupons</h2>
    <a href="{{ route('cupons.create') }}" class="btn btn-success">Novo Cupom</a>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Código</th>
            <th>Tipo</th>
            <th>Valor</th>
            <th>Subtotal Mínimo</th>
            <th>Validade</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($cupons as $cupom)
            <tr>
                <td>{{ $cupom->id }}</td>
                <td>{{ $cupom->codigo }}</td>
                <td>{{ ucfirst($cupom->tipo) }}</td>
                <td>
                    {{ $cupom->tipo === 'fixo'
                        ? 'R$ '.number_format($cupom->valor, 2, ',', '.')
                        : number_format($cupom->valor, 2, ',', '.').'%' }}
                </td>
                <td>R$ {{ number_format($cupom->min_subtotal, 2, ',', '.') }}</td>
<td>
    {{ $cupom->validade_inicio 
        ? \Illuminate\Support\Carbon::parse($cupom->validade_inicio)->format('d/m/Y') 
        : '-' }} 
    até  
    {{ $cupom->validade_fim 
        ? \Illuminate\Support\Carbon::parse($cupom->validade_fim)->format('d/m/Y') 
        : '-' }}
</td>
                <td>
                    <a href="{{ route('cupons.edit', $cupom->id) }}" class="btn btn-sm btn-primary">Editar</a>
                    <form action="{{ route('cupons.destroy', $cupom->id) }}" method="POST" style="display:inline-block" 
                          onsubmit="return confirm('Confirma exclusão?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Excluir</button>
                    </form>
                </td>
            </tr>
        @endforeach
        @if($cupons->isEmpty())
            <tr><td colspan="7" class="text-center">Nenhum cupom cadastrado.</td></tr>
        @endif
    </tbody>
</table>
@endsection
