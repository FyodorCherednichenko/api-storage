<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CreateStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-storage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Storage if not exists';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if(!Storage::exists('storage/user_directories')) {
            Storage::makeDirectory('storage/user_directories');
        } else {
            echo 'Directories already exists' . PHP_EOL;
        }

        return 0;
    }
}
