<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('production_zones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            // Campi dal foglio AREE-MQ Excel
            $table->string('codice'); // codice ministeriale (es. 006FE156 - LI-FE6 - 81M/182807/2016)
            $table->string('nome'); // nome breve della zona
            $table->decimal('mq', 12, 2)->nullable(); // superficie in metri quadri
            $table->enum('classe_sanitaria', ['A', 'B', 'C'])->nullable();
            $table->boolean('declassificazione_temporanea')->default(false);
            $table->date('data_declassificazione')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Vincoli di unicità e indici
            $table->unique(['company_id', 'codice']);
            $table->index(['company_id', 'is_active']);
            $table->index(['classe_sanitaria']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('production_zones');
    }
};
