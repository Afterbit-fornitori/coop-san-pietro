<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transport_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('production_zone_id')->nullable()->constrained()->onDelete('set null');

            // Numerazione progressiva basata su foglio COOPERATIVA
            $table->string('serie', 10); // es. CSP, RPD
            $table->integer('numero'); // progressivo per serie/anno
            $table->integer('anno');
            $table->date('data_documento');
            $table->time('ora_partenza')->nullable();
            $table->date('data_raccolta')->nullable();

            $table->enum('tipo_documento', ['DDT', 'DTN', 'DDR']);
            $table->enum('stato', ['bozza', 'emesso', 'annullato'])->default('bozza');
            $table->string('causale_trasporto')->nullable(); // VENDITA, RESO, etc.
            $table->string('mezzo_trasporto')->nullable(); // MITTENTE, DESTINATARIO, VETTORE
            $table->text('annotazioni')->nullable();

            // Totali documento
            $table->decimal('totale_imponibile', 10, 2)->default(0);
            $table->decimal('iva', 5, 2)->default(0); // aliquota IVA
            $table->decimal('totale_documento', 10, 2)->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Vincoli di unicità e indici per numerazione progressiva
            $table->unique(['company_id', 'serie', 'anno', 'numero']);
            $table->index(['company_id', 'data_documento']);
            $table->index(['client_id']);
            $table->index(['stato']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('transport_documents');
    }
};