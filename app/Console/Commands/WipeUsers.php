<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
class WipeUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:wipe-users {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all users from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if($this->option('force'))
        if ($this->confirm('Are you sure to delete ALL users?')) {
            if ($this->confirm('Are you completely sure to delete ALL users with all the data?')) {
                User::truncate();
            }
        }
    

    }
}
