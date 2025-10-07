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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('parent_company_id')->nullable();
            $table->enum('type', ['main', 'invited']); // Solo main (San Pietro) e invited (tutte le altre)
            $table->string('vat_number', 11)->nullable()->unique();
            $table->string('tax_code', 16)->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province', 2)->nullable();
            $table->string('zip_code', 10)->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('pec')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('impostazioni')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('parent_company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade');
        });

        // Aggiungiamo company_id alla tabella users
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->nullable()->after('id');

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });

        Schema::dropIfExists('companies');
    }
};
