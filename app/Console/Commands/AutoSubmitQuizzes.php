<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutoSubmitQuizzes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quiz:auto-submit {--quiz= : Specific quiz ID to check}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically submit in-progress quizzes whose time or deadline has expired';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $quizId = $this->option('quiz');

        $query = DB::table('tblclassstudentquiz')
            ->join('tblclassquiz', 'tblclassstudentquiz.quizid', '=', 'tblclassquiz.id')
            ->where('tblclassstudentquiz.status', 1)
            ->where(function ($q) {
                $q->where(function ($sub) {
                    $sub->whereNotNull('tblclassstudentquiz.endtime')
                        ->where('tblclassstudentquiz.endtime', '<', DB::raw('NOW()'));
                })->orWhere(function ($sub) {
                    $sub->whereNotNull('tblclassquiz.date_to')
                        ->where('tblclassquiz.date_to', '<', DB::raw('NOW()'));
                });
            });

        if (!empty($quizId)) {
            $query->where('tblclassstudentquiz.quizid', $quizId);
        }

        $expired = $query->select(
            'tblclassstudentquiz.id',
            'tblclassstudentquiz.content as student_content',
            'tblclassstudentquiz.endtime',
            'tblclassquiz.content as default_content'
        )->get();

        $count = 0;
        foreach ($expired as $item) {
            $updateData = [
                'status' => 2,
                'submittime' => $item->endtime ?? DB::raw('NOW()')
            ];

            if (empty($item->student_content)) {
                $updateData['content'] = $item->default_content;
            }

            DB::table('tblclassstudentquiz')
                ->where('id', $item->id)
                ->update($updateData);

            $count++;
        }

        $this->info("Successfully auto-submitted {$count} expired quiz attempt(s).");

        return Command::SUCCESS;
    }
}
