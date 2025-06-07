<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\CupomController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\WebhookController;

// Home: redireciona para lista de produtos
Route::get('/', function () {
    return redirect()->route('produtos.index');
});

// Rotas de Produtos
Route::resource('produtos', ProdutoController::class);

// Rotas de Cupons
Route::resource('cupons', CupomController::class);

// Rotas de Carrinho
Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
Route::post('/cart/add/{produto}', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/remove/{key}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/apply-cupom', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
Route::get('/checkout', [CartController::class, 'checkoutForm'])->name('cart.checkoutForm');
Route::post('/checkout/finalize', [CartController::class, 'finalize'])->name('cart.finalize');

// Rotas de Pedidos (apenas leitura, e possivelmente exclusão)
Route::resource('pedidos', PedidoController::class)->only(['index', 'show', 'destroy']);

// Webhook
Route::post('/webhook/pedido-status', [WebhookController::class, 'updateStatus'])->name('webhook.pedidoStatus');
