<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('loading_unloading_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->date('data_operazione');
            $table->enum('tipo_operazione', ['CARICO', 'SCARICO']);
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->string('lotto')->nullable();

            // Quantità per categoria dal foglio CARICO SCARICO
            $table->decimal('kg_reimmersione', 10, 2)->default(0);
            $table->decimal('kg_piccola', 10, 2)->default(0);
            $table->decimal('kg_media', 10, 2)->default(0);
            $table->decimal('kg_grossa', 10, 2)->default(0);
            $table->decimal('kg_granchio', 10, 2)->default(0);

            $table->string('provenienza_destinazione')->nullable(); // zona produzione o cliente
            $table->foreignId('transport_document_id')->nullable()->constrained()->onDelete('set null');
            $table->text('note')->nullable();

            $table->timestamps();

            // Indici per performance
            $table->index(['company_id', 'data_operazione']);
            $table->index(['tipo_operazione']);
            $table->index(['transport_document_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('loading_unloading_registers');
    }
};