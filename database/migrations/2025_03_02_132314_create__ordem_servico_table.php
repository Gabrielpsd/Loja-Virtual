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
        Schema::create('ordem_servico', function (Blueprint $table) {
            $table->id();
            $table->integer('id_cliente');
            $table->foreign('id_cliente')->references('id')->on('cliente');
            $table->integer('id_veiculo');
            $table->foreign('id_veiculo')->references('id')->on('veiculo');
            $table->boolean('ativado');
            $table->date('data');
            $table->string('obs',255);
            $table->timestamps();
        });

        Schema::create('ordem_servico_servicos', function (Blueprint $table) {
            $table->integer('id_servico');
            $table->foreign('id_servico')->references('id')->on('servicos');
            $table->integer('id_ordem_servico');
            $table->foreign('id_ordem_servico')->references('id')->on('ordem_servico');
            $table->integer('quantidade');
            $table->decimal('preco');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordem_servico');
        Schema::dropIfExists('ordem_servico_servicos');
    }
};
