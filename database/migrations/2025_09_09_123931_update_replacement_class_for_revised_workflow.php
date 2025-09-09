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
        Schema::table('replacement_class', function (Blueprint $table) {
            // Add new fields for revised date workflow only if they don't exist
            if (!Schema::hasColumn('replacement_class', 'revised_date')) {
                $table->date('revised_date')->nullable()->after('next_date');
            }
            if (!Schema::hasColumn('replacement_class', 'revised_time')) {
                $table->string('revised_time')->nullable()->after('revised_date');
            }
            if (!Schema::hasColumn('replacement_class', 'revised_room_id')) {
                $table->unsignedBigInteger('revised_room_id')->nullable()->after('revised_time');
            }
            if (!Schema::hasColumn('replacement_class', 'revised_status')) {
                $table->enum('revised_status', ['PENDING', 'YES', 'NO'])->nullable()->after('revised_room_id');
            }
            if (!Schema::hasColumn('replacement_class', 'revised_rejection_reason')) {
                $table->text('revised_rejection_reason')->nullable()->after('revised_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('replacement_class', function (Blueprint $table) {
            $table->dropColumn([
                'revised_date',
                'revised_time', 
                'revised_room_id',
                'revised_status',
                'revised_rejection_reason'
            ]);
        });
    }
};
