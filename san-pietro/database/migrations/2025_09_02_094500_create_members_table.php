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
            $table->string('name');
            $table->string('tax_code', 16)->unique();
            $table->date('birth_date');
            $table->string('birth_place');
            $table->string('rpm_code');
            $table->date('registration_date');
            $table->string('business_name');
            $table->string('plant_location');
            $table->text('rpm_notes')->nullable();
            $table->text('vessel_notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('members');
    }
};
