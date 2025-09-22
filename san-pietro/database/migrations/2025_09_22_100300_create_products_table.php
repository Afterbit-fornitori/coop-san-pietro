<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('codice'); // codice interno prodotto
            $table->string('nome_scientifico')->nullable(); // es. Tapes Semidecussatus
            $table->string('nome_commerciale'); // es. Vongola Verace
            $table->enum('specie', ['VONGOLE', 'COZZE', 'OSTRICHE', 'ALTRO']);
            $table->enum('pezzatura', ['MICRO', 'PICCOLA', 'MEDIA', 'GROSSA', 'SUPER', 'SGRANATA', 'TRECCIA'])->nullable();
            $table->enum('destinazione', ['CONSUMO', 'REIMMERSIONE', 'DEPURAZIONE']);
            $table->decimal('prezzo_base', 8, 2);
            $table->string('unita_misura', 10)->default('KG');
            $table->boolean('attivo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Vincoli di unicità e indici
            $table->unique(['company_id', 'codice']);
            $table->index(['company_id', 'specie', 'attivo']);
            $table->index(['destinazione']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};