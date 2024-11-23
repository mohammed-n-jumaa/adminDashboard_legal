<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPasswordToLawyersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('lawyers', function (Blueprint $table) {
            $table->string('password')->nullable()->after('lawyer_status'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('lawyers', function (Blueprint $table) {
            $table->dropColumn('password'); 
        });
    }
}
