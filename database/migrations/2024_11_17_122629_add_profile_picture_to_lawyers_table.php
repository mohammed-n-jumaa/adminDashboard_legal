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
        Schema::table('lawyers', function (Blueprint $table) {
            $table->string('profile_picture')->nullable()->after('syndicate_card'); // إضافة عمود الصورة الشخصية
        });
    }
    
    public function down()
    {
        Schema::table('lawyers', function (Blueprint $table) {
            $table->dropColumn('profile_picture'); // حذف العمود إذا تم التراجع
        });
    }
    
};
