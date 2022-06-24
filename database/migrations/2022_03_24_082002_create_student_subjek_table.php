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
        Schema::create('student_subjek', function (Blueprint $table) {
            $table->id();
            $table->string('student_ic');
            $table->unsignedBigInteger('courseid');
            $table->unsignedBigInteger('sessionid');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_subjek');
    }
};
