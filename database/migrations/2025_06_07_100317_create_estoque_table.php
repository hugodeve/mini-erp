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
        Schema::create('estoque', function (Blueprint $table) {
            $table->unsignedBigInteger('produto_id');
            $table->string('variacao')->nullable(); 
            $table->integer('quantidade')->default(0);
            $table->timestamps();

            $table->foreign('produto_id')
                  ->references('id')->on('produtos')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estoque');
    }
};
