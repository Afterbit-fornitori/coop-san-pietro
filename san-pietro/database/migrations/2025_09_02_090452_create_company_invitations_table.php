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
        Schema::create('company_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inviter_company_id')->constrained('companies')->onDelete('cascade');
            $table->string('email');
            $table->string('token', 64)->unique();
            $table->string('company_name');
            $table->enum('business_type', ['cooperativa', 'cdm', 'centro_spedizione']);
            $table->enum('sector', ['mitili', 'vongole', 'misto']);
            $table->json('permissions')->nullable();
            $table->enum('status', ['pending', 'viewed', 'expired', 'accepted'])->default('pending');
            $table->timestamp('expires_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_invitations');
    }
};
