<?php

namespace App\Jobs;

use App\Dictionaries\CommonDictionary;
use App\Http\SubActions\DeleteFileSubAction;
use App\Models\UserStorage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Throwable;

class DeleteFileByDelay implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public UserStorage $user_storage,
        public array $file_info
    ) {
    }

    /**
     * Execute the job.
     * @param DeleteFileSubAction $sub_action
     * @return void
     * @throws Throwable
     */
    public function handle(DeleteFileSubAction $sub_action): void
    {
        $sub_action->execute($this->file_info, $this->user_storage);
    }
}
