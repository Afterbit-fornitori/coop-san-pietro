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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('title');
            $table->string('path');
            $table->string('type', 50); // PDF, DOC, XLS, etc.
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('size')->nullable(); // Size in bytes
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade');
                
            // Indexes
            $table->index(['company_id', 'type']);
            $table->index(['company_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};