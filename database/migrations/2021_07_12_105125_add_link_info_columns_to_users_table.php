<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLinkInfoColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('link_at')->nullable()->default(null)->after('active');
            $table->string('link_by', 20)->nullable()->default(null)->after('link_at');
            $table->foreign('link_by')->references('key')->on('hrs')->onDelete('SET NULL')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('link_at');
            $table->dropForeign(['link_by']);
            $table->dropColumn('link_by');
        });
    }
}
