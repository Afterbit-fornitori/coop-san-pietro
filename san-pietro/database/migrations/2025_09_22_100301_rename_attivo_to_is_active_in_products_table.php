<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('attivo', 'is_active');
            $table->dropIndex(['company_id', 'specie', 'attivo']);
            $table->index(['company_id', 'specie', 'is_active']);
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('is_active', 'attivo');
            $table->dropIndex(['company_id', 'specie', 'is_active']);
            $table->index(['company_id', 'specie', 'attivo']);
        });
    }
};
