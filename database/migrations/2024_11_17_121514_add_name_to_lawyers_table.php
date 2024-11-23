<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNameToLawyersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lawyers', function (Blueprint $table) {
            $table->string('name')->after('id'); // إضافة عمود الاسم بعد عمود id
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
            $table->dropColumn('name'); // حذف العمود في حال التراجع
        });
    }
}
