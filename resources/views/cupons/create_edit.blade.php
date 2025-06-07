@extends('layouts.app')

@section('content')
@php
    $isEdit = $cupom->exists;
@endphp

<h2>{{ $isEdit ? 'Editar Cupom #' . $cupom->id : 'Novo Cupom' }}</h2>

<form action="{{ $isEdit ? route('cupons.update', $cupom->id) : route('cupons.store') }}" method="POST">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="mb-3">
        <label for="codigo" class="form-label">Código</label>
        <input type="text" name="codigo" id="codigo" class="form-control"
               value="{{ old('codigo', $cupom->codigo) }}" required>
        @error('codigo')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-3">
        <label for="tipo" class="form-label">Tipo</label>
        <select name="tipo" id="tipo" class="form-select" required>
            <option value="fixo" {{ old('tipo', $cupom->tipo) === 'fixo' ? 'selected' : '' }}>Fixo</option>
            <option value="percentual" {{ old('tipo', $cupom->tipo) === 'percentual' ? 'selected' : '' }}>Percentual</option>
        </select>
        @error('tipo')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-3">
        <label for="valor" class="form-label">Valor (R$ ou %)</label>
        <input type="number" step="0.01" name="valor" id="valor" class="form-control"
               value="{{ old('valor', $cupom->valor) }}" required>
        @error('valor')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-3">
        <label for="min_subtotal" class="form-label">Subtotal Mínimo (R$)</label>
        <input type="number" step="0.01" name="min_subtotal" id="min_subtotal" class="form-control"
               value="{{ old('min_subtotal', $cupom->min_subtotal) }}" required>
        @error('min_subtotal')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="validade_inicio" class="form-label">Validade Início</label>
            <input type="date" name="validade_inicio" id="validade_inicio" class="form-control"
                   value="{{ old('validade_inicio', $cupom->validade_inicio ? $cupom->validade_inicio->format('Y-m-d') : '') }}">
            @error('validade_inicio')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="validade_fim" class="form-label">Validade Fim</label>
            <input type="date" name="validade_fim" id="validade_fim" class="form-control"
                   value="{{ old('validade_fim', $cupom->validade_fim ? $cupom->validade_fim->format('Y-m-d') : '') }}">
            @error('validade_fim')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <button class="btn btn-primary">{{ $isEdit ? 'Atualizar' : 'Salvar' }}</button>
    <a href="{{ route('cupons.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection
