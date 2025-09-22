<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            // Fields from SOCI Excel sheet
            $table->string('last_name');
            $table->string('first_name');
            $table->string('tax_code', 16);
            $table->date('birth_date');
            $table->string('birth_place');
            $table->string('rpm_registration');
            $table->date('rpm_registration_date');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Indici per performance e vincoli
            $table->unique(['company_id', 'tax_code']);
            $table->index(['company_id', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('members');
    }
};
