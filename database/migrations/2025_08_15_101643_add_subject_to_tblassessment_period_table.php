<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblassessment_period', function (Blueprint $table) {
            $table->string('subject')->nullable()->after('user_ic');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblassessment_period', function (Blueprint $table) {
            $table->dropColumn('subject');
        });
    }
};
