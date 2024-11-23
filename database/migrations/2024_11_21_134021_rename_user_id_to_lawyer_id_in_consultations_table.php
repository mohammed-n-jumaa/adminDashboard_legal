<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameUserIdToLawyerIdInConsultationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->renameColumn('user_id', 'lawyer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->renameColumn('lawyer_id', 'user_id');
        });
    }
}
