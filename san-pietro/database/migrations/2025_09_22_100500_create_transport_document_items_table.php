<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transport_document_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transport_document_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            // Dati riga prodotto
            $table->decimal('quantita_kg', 10, 2);
            $table->integer('numero_colli');
            $table->decimal('prezzo_unitario', 8, 2);
            $table->decimal('totale', 10, 2); // quantita_kg * prezzo_unitario

            $table->timestamps();

            // Indici per performance
            $table->index(['transport_document_id']);
            $table->index(['product_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('transport_document_items');
    }
};