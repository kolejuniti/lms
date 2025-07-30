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
        Schema::create('student_discount', function (Blueprint $table) {
            $table->id();
            $table->string('student_ic');
            $table->decimal('discount', 5, 2)->default(0.00); // discount percentage (e.g., 15.50 for 15.5%)
            $table->decimal('total_arrears', 10, 2)->default(0.00); // Jumlah Tunggakan
            $table->decimal('received_discount', 10, 2)->default(0.00); // Terimaan Diskaun
            $table->decimal('payment', 10, 2)->default(0.00); // Bayaran Pelajar
            $table->timestamps();
            
            // Add index for faster searching
            $table->index('student_ic');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_discount');
    }
};
