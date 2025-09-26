<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('production_zone_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->foreignId('transport_document_id')->nullable()->constrained()->onDelete('restrict');
            $table->foreignId('weekly_record_id')->nullable()->constrained()->onDelete('restrict');
            $table->date('production_date');
            $table->enum('production_type', ['internal_reimmersion', 'resale_reimmersion', 'consumption']);
            $table->enum('category', ['micro', 'small', 'medium', 'large', 'super']);
            $table->decimal('quantity_kg', 10, 2);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->enum('status', ['available', 'sold', 'reimmersed'])->default('available');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productions');
    }
};
