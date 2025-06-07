<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Cupom extends Model
{
    use HasFactory;

    protected $table = 'cupons';

    protected $fillable = [
        'codigo',
        'tipo',
        'valor',
        'min_subtotal',
        'validade_inicio',
        'validade_fim',
    ];

    protected $dates = [
        'validade_inicio',
        'validade_fim',
    ];

    public function isValidoParaSubtotal($subtotal)
    {
        $hoje = Carbon::today();

        // verificar datas
        if ($this->validade_inicio && $hoje->lt($this->validade_inicio)) {
            return false;
        }
        if ($this->validade_fim && $hoje->gt($this->validade_fim)) {
            return false;
        }
        // verificar subtotal mínimo
        if ($subtotal < $this->min_subtotal) {
            return false;
        }
        return true;
    }

    public function calculaDesconto($subtotal)
    {
        if ($this->tipo === 'percentual') {
            return round($subtotal * ($this->valor / 100), 2);
        }
        // tipo fixo
        return min($this->valor, $subtotal);
    }
}
