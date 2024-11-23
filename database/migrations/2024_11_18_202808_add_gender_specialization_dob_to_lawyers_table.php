<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGenderSpecializationDobToLawyersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lawyers', function (Blueprint $table) {
            $table->enum('gender', ['male', 'female'])->after('email');
            $table->string('specialization')->nullable()->after('gender');
            $table->date('date_of_birth')->nullable()->after('specialization');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lawyers', function (Blueprint $table) {
            $table->dropColumn(['gender', 'specialization', 'date_of_birth']);
        });
    }
}
