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
        Schema::table('company_invitations', function (Blueprint $table) {
            // Cambia business_type da ENUM a VARCHAR per permettere valori più lunghi
            $table->string('business_type', 100)->change();

            // Cambia sector da ENUM a VARCHAR per permettere valori più lunghi
            $table->string('sector', 100)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_invitations', function (Blueprint $table) {
            // Ripristina ENUM originali
            $table->enum('business_type', ['cooperativa', 'cdm', 'centro_spedizione'])->change();
            $table->enum('sector', ['mitili', 'vongole', 'misto'])->change();
        });
    }
};
