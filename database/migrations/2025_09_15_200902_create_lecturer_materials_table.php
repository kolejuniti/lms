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
        Schema::create('lecturer_materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_subjek_id'); // Reference to user_subjek table
            $table->string('file_name'); // Original file name
            $table->string('file_path'); // Path to stored file
            $table->string('file_type'); // File extension/type
            $table->bigInteger('file_size'); // File size in bytes
            $table->enum('category', ['Rubrik', 'Rowscore', 'Others'])->default('Others'); // Document category
            $table->text('description')->nullable(); // Optional description
            $table->string('uploaded_by'); // IC of lecturer who uploaded
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index('user_subjek_id');
            $table->index('uploaded_by');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lecturer_materials');
    }
};
