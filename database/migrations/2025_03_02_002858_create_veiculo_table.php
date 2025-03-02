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
        Schema::create('cores', function (Blueprint $table) {
            $table->id();
            $table->string('descricao',20);
        });
        Schema::create('marcas', function (Blueprint $table) {
            $table->id();
            $table->string('descricao',20);
        });

        Schema::create('veiculo', function (Blueprint $table) {
            $table->id();
            $table->string('placa',7);
            $table->string('modelo',10);
            $table->integer('id_proprietario'); 
            $table->foreign('id_proprietario')->references('id')->on('cliente');
            $table->integer('cor');
            $table->foreign('cor')->references('id')->on('cores');
            $table->integer('marca'); 
            $table->foreign('marca')->references('id')->on('marcas');
            $table->integer('ano_fabricacao'); ;
            $table->integer('ano_modelo'); ;
            $table->boolean('ativado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('veiculo');
        Schema::dropIfExists('cores');
        Schema::dropIfExists('marcas');
    }
};
