<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Questa migrazione è stata sostituita da weekly_records_table
        // che riflette meglio la struttura del foglio Excel "PER FARE LA FATTURA"
        // Non creiamo più la tabella productions
    }

    public function down()
    {
        // Nulla da fare
    }
};
