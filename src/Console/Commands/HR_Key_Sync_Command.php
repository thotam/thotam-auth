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
        dd(User::first());
        return 0;
    }
}
