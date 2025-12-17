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
            // Add reply_to_message_id column to support WhatsApp-style message replies
            // References tblmessage_dtl.id of the quoted/replied message (nullable)
            $table->unsignedBigInteger('reply_to_message_id')->nullable()->after('status');
            $table->index('reply_to_message_id', 'idx_reply_to_message_id');
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
            $table->dropIndex('idx_reply_to_message_id');
            $table->dropColumn('reply_to_message_id');
        });
    }
};
