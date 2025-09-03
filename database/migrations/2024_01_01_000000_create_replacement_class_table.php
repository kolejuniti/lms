<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('replacement_class', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_subjek_id'); // Reference to user_subjek table
            $table->date('tarikh_kuliah_dibatalkan');
            $table->string('sebab_kuliah_dibatalkan');
            $table->text('maklumat_kuliah');
            $table->string('wakil_pelajar_nama'); // Manual input
            $table->string('wakil_pelajar_no_tel'); // Manual input
            $table->date('maklumat_kuliah_gantian_tarikh'); // Manual input
            $table->string('maklumat_kuliah_gantian_hari_masa'); // Manual input
            $table->unsignedBigInteger('lecture_room_id'); // Reference to tbllecture_room
            $table->integer('group_id'); // Reference to the group from user_subjek
            $table->string('group_name'); // Group name
            $table->json('selected_programs'); // Store selected program IDs as JSON
            $table->string('student_ic'); // The selected wakil pelajar IC
            $table->enum('is_verified', ['PENDING', 'YES', 'NO'])->default('PENDING');
            $table->string('kp_ic')->nullable(); // KP who verified
            $table->date('next_date')->nullable(); // Next date if rejected
            $table->text('rejection_reason')->nullable(); // Reason for rejection
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('user_subjek_id')->references('id')->on('user_subjek')->onDelete('cascade');
            $table->foreign('lecture_room_id')->references('id')->on('tbllecture_room')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('replacement_class');
    }
};
