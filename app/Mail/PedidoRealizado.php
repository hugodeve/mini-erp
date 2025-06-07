<?php

namespace App\Mail;

use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PedidoRealizado extends Mailable
{
    use Queueable, SerializesModels;

    public $pedido;

    public function __construct(Pedido $pedido)
    {
        $this->pedido = $pedido;
    }

    public function build()
    {
        return $this->subject('Confirmação de Pedido #' . $this->pedido->id)
                    ->markdown('emails.pedido.realizado')
                    ->with([
                        'pedido' => $this->pedido,
                        'items' => $this->pedido->items,
                    ]);
    }
}
