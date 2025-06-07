@extends('layouts.app')

@section('content')
<h2>Checkout</h2>

<form action="{{ route('cart.finalize') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="cep" class="form-label">CEP</label>
        <input type="text" name="cep" id="cep" class="form-control" 
               value="{{ old('cep') }}" required>
        @error('cep')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    {{-- Campos populados via ViaCEP --}}
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="logradouro" class="form-label">Logradouro</label>
            <input type="text" name="logradouro" id="logradouro" class="form-control" readonly>
        </div>
        <div class="col-md-6">
            <label for="bairro" class="form-label">Bairro</label>
            <input type="text" name="bairro" id="bairro" class="form-control" readonly>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="cidade" class="form-label">Cidade</label>
            <input type="text" name="cidade" id="cidade" class="form-control" readonly>
        </div>
        <div class="col-md-4">
            <label for="uf" class="form-label">UF</label>
            <input type="text" name="uf" id="uf" class="form-control" readonly>
        </div>
        <div class="col-md-4">
            <label for="numero" class="form-label">Número</label>
            <input type="text" name="numero" id="numero" class="form-control">
            @error('numero')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
    <div class="mb-3">
        <label for="complemento" class="form-label">Complemento</label>
        <input type="text" name="complemento" id="complemento" class="form-control">
    </div>

    {{-- (Opcional) e-mail do cliente para envio de confirmação --}}
    <div class="mb-3">
        <label for="email_cliente" class="form-label">E-mail do Cliente</label>
        <input type="email" name="email_cliente" id="email_cliente" class="form-control" 
               placeholder="exemplo@dominio.com">
    </div>

    <button class="btn btn-primary">Confirmar Pedido</button>
    <a href="{{ route('cart.show') }}" class="btn btn-secondary">Voltar ao Carrinho</a>
</form>

@push('scripts')
<script>
    document.getElementById('cep').addEventListener('blur', function() {
        let cep = this.value.replace(/\D/g,'');
        if (cep.length !== 8) {
            alert('CEP inválido.');
            return;
        }
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
                if (data.erro) {
                    alert('CEP não encontrado.');
                    return;
                }
                document.getElementById('logradouro').value = data.logradouro;
                document.getElementById('bairro').value = data.bairro;
                document.getElementById('cidade').value = data.localidade;
                document.getElementById('uf').value = data.uf;
            })
            .catch(() => {
                alert('Erro ao consultar CEP.');
            });
    });
</script>
@endpush
@endsection
