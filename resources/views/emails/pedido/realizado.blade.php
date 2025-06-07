@component('mail::message')
# Pedido Recebido (#{{ $pedido->id }})

Obrigado pela compra! Seguem os detalhes do seu pedido:

**Data:** {{ $pedido->created_at->format('d/m/Y H:i') }}

**Itens:**

@component('mail::table')
| Produto ID | Variação | Quantidade | Preço Unitário (R$) | Total Item (R$) |
|:-----------|:---------|:-----------|:--------------------:|:---------------:|
@foreach($items as $item)
| {{ $item['produto_id'] }} | {{ $item['variacao'] ?? '—' }} | {{ $item['quantidade'] }} | {{ number_format($item['preco_unitario'], 2, ',', '.') }} | {{ number_format($item['preco_unitario'] * $item['quantidade'], 2, ',', '.') }} |
@endforeach
@endcomponent

**Subtotal:** R$ {{ number_format($pedido->subtotal, 2, ',', '.') }}  
**Desconto:** R$ {{ number_format($pedido->desconto, 2, ',', '.') }}  
**Frete:** R$ {{ number_format($pedido->frete, 2, ',', '.') }}  
**Total:** R$ {{ number_format($pedido->total, 2, ',', '.') }}

**Endereço de Entrega:**  
{{ $pedido->logradouro }}, Nº {{ $pedido->numero }} {{ $pedido->complemento ? '- '.$pedido->complemento : '' }}  
{{ $pedido->bairro }} – {{ $pedido->cidade }}/{{ $pedido->uf }}  
CEP: {{ $pedido->cep }}

@component('mail::button', ['url' => url('/')])
Voltar para a Loja
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
