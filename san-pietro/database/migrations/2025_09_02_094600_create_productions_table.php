<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->unsignedSmallInteger('week_number');
            $table->year('year');
            $table->decimal('micro_price', 10, 2);
            $table->decimal('micro_quantity', 10, 2);
            $table->decimal('standard_price', 10, 2);
            $table->decimal('standard_quantity', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'member_id', 'week_number', 'year']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('productions');
    }
};
