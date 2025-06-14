<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';

    protected $fillable = [
        'items',
        'subtotal',
        'desconto',
        'frete',
        'total',
        'status',
        'cep',
        'logradouro',
        'bairro',
        'cidade',
        'uf',
        'complemento',
        'numero',
        'enviado_email',
    ];

    protected $casts = [
        'items' => 'array',
    ];
}
