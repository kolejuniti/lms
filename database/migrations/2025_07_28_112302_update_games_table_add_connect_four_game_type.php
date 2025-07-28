<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Use raw SQL to modify the ENUM column
        DB::statement("ALTER TABLE games MODIFY COLUMN game_type ENUM('tic_tac_toe', 'connect_four') NOT NULL DEFAULT 'tic_tac_toe'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert back to only tic_tac_toe
        DB::statement("ALTER TABLE games MODIFY COLUMN game_type ENUM('tic_tac_toe') NOT NULL DEFAULT 'tic_tac_toe'");
    }
};
