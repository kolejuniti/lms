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
        Schema::table('replacement_class', function (Blueprint $table) {
            // Drop the old text column if it exists
            if (Schema::hasColumn('replacement_class', 'maklumat_kuliah_gantian_tempat')) {
                $table->dropColumn('maklumat_kuliah_gantian_tempat');
            }
            
            // Add the new lecture room column if it doesn't exist
            if (!Schema::hasColumn('replacement_class', 'lecture_room_id')) {
                $table->integer('lecture_room_id')->after('maklumat_kuliah_gantian_hari_masa');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('replacement_class', function (Blueprint $table) {
            // Drop column
            $table->dropColumn('lecture_room_id');
            
            // Add back the text column
            $table->string('maklumat_kuliah_gantian_tempat')->after('maklumat_kuliah_gantian_hari_masa');
        });
    }
};
