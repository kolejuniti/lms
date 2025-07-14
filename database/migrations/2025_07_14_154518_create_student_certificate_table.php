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
        Schema::create('student_certificate', function (Blueprint $table) {
            $table->id();
            $table->string('student_ic', 20);
            $table->string('serial_no', 20);
            $table->enum('status', ['NEW', 'CLAIMED', 'RECLAIMED'])->default('NEW');
            $table->timestamp('date')->useCurrent();
            $table->timestamp('date_claimed')->nullable();
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
        Schema::dropIfExists('student_certificate');
    }
};
