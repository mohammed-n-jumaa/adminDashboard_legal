<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameLawyerIdToUserIdInConsultationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->renameColumn('lawyer_id', 'user_id');
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
            $table->dropForeign(['lawyer_id']); // حذف المفتاح الأجنبي
            $table->renameColumn('lawyer_id', 'user_id'); // تغيير اسم العمود
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // إعادة المفتاح الأجنبي
        });
        
    }
}
