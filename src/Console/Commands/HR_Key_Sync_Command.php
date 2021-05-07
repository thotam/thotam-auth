<?php

namespace Thotam\ThotamAuth\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class HR_Key_Sync_Command extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'thotam-auth:hr-key-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $members = User::whereNull('hr_key')->whereNotNull('info_mnv')->limit(100)->get();

        foreach ($members as $member) {
            $member->update([
                'hr_key' => $member->info_mnv,
            ]);
        }

        $betas = User::whereNull('info_mnv')->whereNotNull('hr_key')->limit(100)->get();

        foreach ($betas as $beta) {
            $beta->update([
                'info_mnv' => $beta->hr_key,
            ]);
        }

        return 0;
    }
}
