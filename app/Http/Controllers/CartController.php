<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Cupom;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PedidoRealizado;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    // Exibe o carrinho (itens + cálculo de frete + cupom)
    public function showCart()
    {
        $cart = session()->get('cart', []); 
        $itens = [];
        $subtotal = 0;

        foreach ($cart as $key => $item) {
            $produto = Produto::find($item['produto_id']);
            if (!$produto) continue;
            $preco_unit = $produto->preco;
            // Se houver variação, preço é o mesmo do produto, apenas definimos variação
            $qtde = $item['quantidade'];
            $total_item = $preco_unit * $qtde;
            $subtotal += $total_item;

            $itens[] = [
                'produto' => $produto,
                'variacao' => $item['variacao'] ?? null,
                'quantidade' => $qtde,
                'preco_unitario' => $preco_unit,
                'total_item' => $total_item,
            ];
        }

        // Cálculo de frete
        if ($subtotal >= 52 && $subtotal <= 166.59) {
            $frete = 15.00;
        } elseif ($subtotal > 200) {
            $frete = 0.00;
        } else {
            $frete = 20.00;
        }

        // Cupom
        $cupomAplicado = session()->get('cupom', null);
        $desconto = 0;
        if ($cupomAplicado) {
            $cupom = Cupom::where('codigo', $cupomAplicado)->first();
            if ($cupom && $cupom->isValidoParaSubtotal($subtotal)) {
                $desconto = $cupom->calculaDesconto($subtotal);
            } else {
                // Cupom inválido: remove
                session()->forget('cupom');
                $cupomAplicado = null;
            }
        }

        $total = $subtotal - $desconto + $frete;
        return view('cart.index', compact('itens', 'subtotal', 'frete', 'desconto', 'total', 'cupomAplicado'));
    }

    // Adiciona ao carrinho (via botão “Comprar”)
    public function addToCart(Request $request, $produtoId)
    {
        $produto = Produto::findOrFail($produtoId);
        $variacao = $request->input('variacao', null);
        $qtde = intval($request->input('quantidade', 1));

        // Verificar estoque disponível
        $estoqueQuery = $produto->estoques()->where('produto_id', $produto->id);
        if ($variacao) {
            $estoqueQuery->where('variacao', $variacao);
        }
        $registroEstoque = $estoqueQuery->first();
        $estoqueDisponivel = $registroEstoque ? $registroEstoque->quantidade : 0;

        // Quantidade já no carrinho
        $cart = session()->get('cart', []);
        $chave = $produto->id . '_' . ($variacao ?: 'null');
        $qtdeNoCart = isset($cart[$chave]) ? $cart[$chave]['quantidade'] : 0;

        if ($qtdeNoCart + $qtde > $estoqueDisponivel) {
            return redirect()->back()->with('error', 'Quantidade indisponível em estoque.');
        }

        // Adicionar ou incrementar no carrinho (session)
        if (isset($cart[$chave])) {
            $cart[$chave]['quantidade'] += $qtde;
        } else {
            $cart[$chave] = [
                'produto_id' => $produto->id,
                'variacao' => $variacao,
                'quantidade' => $qtde,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->route('cart.show')->with('success', 'Produto adicionado ao carrinho.');
    }

    // Remove item do carrinho
    public function removeFromCart($key)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }
        return redirect()->route('cart.show')->with('success', 'Item removido do carrinho.');
    }

    // Aplica cupom no carrinho
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'codigo_cupom' => 'required|string',
        ]);

        $cupom = Cupom::where('codigo', $request->codigo_cupom)->first();
        if (!$cupom) {
            return redirect()->back()->with('error', 'Cupom não encontrado.');
        }

        session()->put('cupom', $cupom->codigo);
        return redirect()->route('cart.show')->with('success', 'Cupom aplicado.');
    }

    // Exibe formulário de checkout (endereço via CEP)
    public function checkoutForm()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.show')->with('error', 'Carrinho vazio.');
        }
        return view('cart.checkout');
    }

    // Processa finalização do pedido
    public function finalize(Request $request)
    {
        $request->validate([
            'cep' => 'required|string',
            'numero' => 'nullable|string|max:20',
            'complemento' => 'nullable|string|max:255',
        ]);

        $cart = session()->get('cart', []);
        $itensPedido = [];
        $subtotal = 0;

        foreach ($cart as $item) {
            $produto = Produto::find($item['produto_id']);
            if (!$produto) continue;
            $preco_unit = $produto->preco;
            $qtde = $item['quantidade'];
            $total_item = $preco_unit * $qtde;
            $subtotal += $total_item;
            $itensPedido[] = [
                'produto_id' => $produto->id,
                'variacao' => $item['variacao'] ?? null,
                'quantidade' => $qtde,
                'preco_unitario' => $preco_unit,
            ];
        }

        // Cálculo de frete
        if ($subtotal >= 52 && $subtotal <= 166.59) {
            $frete = 15.00;
        } elseif ($subtotal > 200) {
            $frete = 0.00;
        } else {
            $frete = 20.00;
        }

        // Desconto do cupom
        $desconto = 0;
        $cupomAplicado = session()->get('cupom', null);
        if ($cupomAplicado) {
            $cupom = Cupom::where('codigo', $cupomAplicado)->first();
            if ($cupom && $cupom->isValidoParaSubtotal($subtotal)) {
                $desconto = $cupom->calculaDesconto($subtotal);
            }
        }

        $total = $subtotal - $desconto + $frete;

        // Consumir API ViaCEP para preencher endereço adicional
        $cep = preg_replace('/\D/', '', $request->cep);
        $viacep = json_decode(file_get_contents("https://viacep.com.br/ws/{$cep}/json/"));
        if (isset($viacep->erro)) {
            return redirect()->back()->with('error', 'CEP inválido.');
        }

        // Criar Pedido no BD
        $pedido = Pedido::create([
            'items' => $itensPedido,
            'subtotal' => $subtotal,
            'desconto' => $desconto,
            'frete' => $frete,
            'total' => $total,
            'status' => 'pendente',
            'cep' => $viacep->cep,
            'logradouro' => $viacep->logradouro,
            'bairro' => $viacep->bairro,
            'cidade' => $viacep->localidade,
            'uf' => $viacep->uf,
            'complemento' => $request->complemento,
            'numero' => $request->numero,
        ]);

        // Disparar e-mail para o cliente (supondo que tenhamos e-mail do cliente, 
        // mas aqui usaremos um e-mail fixo de exemplo)
        try {
            // Exemplo: endereço fixo; numa aplicação real, pegar do usuário
            $clienteEmail = $request->input('email_cliente', 'cliente@exemplo.com');
            Mail::to($clienteEmail)->send(new PedidoRealizado($pedido));
            $pedido->update(['enviado_email' => true]);
        } catch (\Exception $e) {
            // logar erro, mas não impedir continuidade
            Log::error('Erro ao enviar e-mail do pedido: '.$e->getMessage());
        }

        // Limpar carrinho e cupom
        session()->forget('cart');
        session()->forget('cupom');

        return redirect()->route('pedidos.show', $pedido->id)
                         ->with('success', 'Pedido finalizado com sucesso.');
    }
}
