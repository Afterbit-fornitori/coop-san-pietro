<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            // Fields from DATI CLIENTI Excel sheet
            $table->string('business_name');
            $table->string('vat_number', 11)->nullable();
            $table->string('tax_code', 16)->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('province', 2)->nullable();
            $table->string('pec')->nullable();
            $table->string('phone')->nullable();
            $table->string('sdi_code', 7)->nullable(); // for electronic invoicing
            $table->text('note')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Unique constraints and indexes
            $table->unique(['company_id', 'vat_number']);
            $table->index(['company_id', 'business_name']);
            $table->index(['company_id', 'tax_code']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('clients');
    }
};
