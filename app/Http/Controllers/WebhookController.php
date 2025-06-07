<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
     public function updateStatus(Request $request)
    {
        $request->validate([
            'pedido_id' => 'required|integer|exists:pedidos,id',
            'status'    => 'required|string',
        ]);

        $pedido = Pedido::find($request->pedido_id);

        if (!$pedido) {
            return response()->json(['message' => 'Pedido não encontrado.'], 404);
        }

        if (strtolower($request->status) === 'cancelado') {
            $pedido->delete();
            return response()->json(['message' => 'Pedido cancelado e removido.'], 200);
        }

        $pedido->update(['status' => $request->status]);
        return response()->json(['message' => 'Status do pedido atualizado.'], 200);
    }
}
