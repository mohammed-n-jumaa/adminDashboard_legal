<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->softDeletes(); // Add deleted_at column
        });
    }
    
    public function down()
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Remove deleted_at column
        });
    }
    
};
