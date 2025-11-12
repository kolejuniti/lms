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
        Schema::table('tblmessage_dtl', function (Blueprint $table) {
            // Add columns for file metadata
            $table->string('file_url', 1000)->nullable()->after('image_url');
            $table->string('file_name', 255)->nullable()->after('file_url');
            $table->string('file_type', 50)->nullable()->after('file_name');
            $table->bigInteger('file_size')->nullable()->after('file_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblmessage_dtl', function (Blueprint $table) {
            // Drop the file metadata columns
            $table->dropColumn(['file_url', 'file_name', 'file_type', 'file_size']);
        });
    }
};
