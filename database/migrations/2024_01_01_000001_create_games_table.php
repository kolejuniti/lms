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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('game_type', 50); // tictactoe, etc.
            $table->string('player1_ic', 20);
            $table->string('player2_ic', 20);
            $table->string('current_turn', 20)->nullable();
            $table->json('game_data')->nullable();
            $table->enum('status', ['waiting', 'active', 'completed', 'cancelled'])->default('waiting');
            $table->string('winner_ic', 20)->nullable();
            $table->timestamps();

            $table->foreign('player1_ic')->references('ic')->on('students')->onDelete('cascade');
            $table->foreign('player2_ic')->references('ic')->on('students')->onDelete('cascade');
            
            $table->index(['player1_ic', 'player2_ic']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}; 