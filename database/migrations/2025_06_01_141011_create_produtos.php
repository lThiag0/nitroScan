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
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_ean');
            $table->string('nome');
            $table->text('descricao');
            $table->string('fabricante');
            $table->year('ano_fabricacao');
            $table->date('data_vencimento');
            $table->decimal('valor', 8, 2);
            $table->timestamp('data_cadastro')->useCurrent();
            $table->string('imagem')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
