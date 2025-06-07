<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->json('items'); 
            // será um array JSON com {produto_id, variacao, quantidade, preco_unitario}
            $table->decimal('subtotal', 10, 2);
            $table->decimal('desconto', 10, 2)->default(0);
            $table->decimal('frete', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('status')->default('pendente');
            $table->string('cep');
            $table->string('logradouro')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('uf')->nullable();
            $table->string('complemento')->nullable();
            $table->string('numero')->nullable();
            $table->boolean('enviado_email')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
