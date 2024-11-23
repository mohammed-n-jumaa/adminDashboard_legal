<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommentIdToLawyersTable extends Migration
{
    public function up()
    {
        Schema::table('lawyers', function (Blueprint $table) {
            $table->unsignedBigInteger('comment_id')->nullable()->after('deleted_at');
            $table->foreign('comment_id')->references('id')->on('comments')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('lawyers', function (Blueprint $table) {
            $table->dropForeign(['comment_id']);
            $table->dropColumn('comment_id');
        });
    }
}