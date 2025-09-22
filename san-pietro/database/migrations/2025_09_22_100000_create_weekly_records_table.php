<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('weekly_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->integer('year');
            $table->integer('week');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('invoice_number')->nullable();

            // Internal Reimmersion (micro, small)
            $table->decimal('kg_micro_internal_reimmersion', 10, 2)->default(0);
            $table->decimal('price_micro_internal_reimmersion', 8, 2)->default(0);
            $table->decimal('kg_small_internal_reimmersion', 10, 2)->default(0);
            $table->decimal('price_small_internal_reimmersion', 8, 2)->default(0);

            // Resale Reimmersion (micro, small)
            $table->decimal('kg_micro_resale_reimmersion', 10, 2)->default(0);
            $table->decimal('price_micro_resale_reimmersion', 8, 2)->default(0);
            $table->decimal('kg_small_resale_reimmersion', 10, 2)->default(0);
            $table->decimal('price_small_resale_reimmersion', 8, 2)->default(0);

            // Direct Consumption (medium, large, super)
            $table->decimal('kg_medium_consumption', 10, 2)->default(0);
            $table->decimal('price_medium_consumption', 8, 2)->default(0);
            $table->decimal('kg_large_consumption', 10, 2)->default(0);
            $table->decimal('price_large_consumption', 8, 2)->default(0);
            $table->decimal('kg_super_consumption', 10, 2)->default(0);
            $table->decimal('price_super_consumption', 8, 2)->default(0);

            // Calculations and payments
            $table->decimal('taxable_amount', 10, 2)->default(0);
            $table->decimal('advance_paid', 10, 2)->default(0);
            $table->decimal('withholding_tax', 10, 2)->default(0);
            $table->decimal('profis', 10, 2)->default(0);
            $table->decimal('bank_transfer', 10, 2)->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Vincoli di unicità e indici
            $table->unique(['company_id', 'member_id', 'year', 'week']);
            $table->index(['company_id', 'year', 'week']);
            $table->index(['member_id', 'year']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('weekly_records');
    }
};