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
        // Add indexes for tblstudent_warning table
        Schema::table('tblstudent_warning', function (Blueprint $table) {
            $table->index('created_at', 'idx_student_warning_created_at');
            $table->index('student_ic', 'idx_student_warning_student_ic');
            $table->index(['groupid', 'groupname'], 'idx_student_warning_group');
        });

        // Add indexes for student_subjek table
        Schema::table('student_subjek', function (Blueprint $table) {
            $table->index(['group_id', 'group_name'], 'idx_student_subjek_group');
            $table->index('courseid', 'idx_student_subjek_courseid');
            $table->index('sessionid', 'idx_student_subjek_sessionid');
            $table->index('student_ic', 'idx_student_subjek_student_ic');
            $table->index('created_at', 'idx_student_subjek_created_at');
        });

        // Add indexes for students table
        Schema::table('students', function (Blueprint $table) {
            $table->index('ic', 'idx_students_ic');
            $table->index(['semester', 'session'], 'idx_students_semester_session');
            $table->index(['semester', 'session', 'status'], 'idx_students_semester_session_status');
        });

        // Add indexes for sessions table
        Schema::table('sessions', function (Blueprint $table) {
            $table->index('Status', 'idx_sessions_status');
            $table->index('SessionID', 'idx_sessions_sessionid');
        });

        // Add indexes for subjek table
        Schema::table('subjek', function (Blueprint $table) {
            $table->index('sub_id', 'idx_subjek_sub_id');
        });

        // Add indexes for assessment tables
        Schema::table('tblclassquiz', function (Blueprint $table) {
            $table->index('status', 'idx_classquiz_status');
            $table->index(['created_at', 'status'], 'idx_classquiz_created_status');
        });

        Schema::table('tblclasstest', function (Blueprint $table) {
            $table->index('status', 'idx_classtest_status');
            $table->index(['created_at', 'status'], 'idx_classtest_created_status');
        });

        Schema::table('tblclassassign', function (Blueprint $table) {
            $table->index('status', 'idx_classassign_status');
            $table->index(['created_at', 'status'], 'idx_classassign_created_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop indexes for tblstudent_warning table
        Schema::table('tblstudent_warning', function (Blueprint $table) {
            $table->dropIndex('idx_student_warning_created_at');
            $table->dropIndex('idx_student_warning_student_ic');
            $table->dropIndex('idx_student_warning_group');
        });

        // Drop indexes for student_subjek table
        Schema::table('student_subjek', function (Blueprint $table) {
            $table->dropIndex('idx_student_subjek_group');
            $table->dropIndex('idx_student_subjek_courseid');
            $table->dropIndex('idx_student_subjek_sessionid');
            $table->dropIndex('idx_student_subjek_student_ic');
            $table->dropIndex('idx_student_subjek_created_at');
        });

        // Drop indexes for students table
        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('idx_students_ic');
            $table->dropIndex('idx_students_semester_session');
            $table->dropIndex('idx_students_semester_session_status');
        });

        // Drop indexes for sessions table
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropIndex('idx_sessions_status');
            $table->dropIndex('idx_sessions_sessionid');
        });

        // Drop indexes for subjek table
        Schema::table('subjek', function (Blueprint $table) {
            $table->dropIndex('idx_subjek_sub_id');
        });

        // Drop indexes for assessment tables
        Schema::table('tblclassquiz', function (Blueprint $table) {
            $table->dropIndex('idx_classquiz_status');
            $table->dropIndex('idx_classquiz_created_status');
        });

        Schema::table('tblclasstest', function (Blueprint $table) {
            $table->dropIndex('idx_classtest_status');
            $table->dropIndex('idx_classtest_created_status');
        });

        Schema::table('tblclassassign', function (Blueprint $table) {
            $table->dropIndex('idx_classassign_status');
            $table->dropIndex('idx_classassign_created_status');
        });
    }
};
