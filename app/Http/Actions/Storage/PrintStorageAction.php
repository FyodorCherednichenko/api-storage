<?php
declare(strict_types=1);

namespace App\Http\Actions\Storage;

use App\Dictionaries\CommonDictionary;
use App\Models\User;
use App\Models\UserStorage;
use Illuminate\Support\Facades\Storage;

class PrintStorageAction
{
    /**
     * @param User $user
     * @return array
     */
    public function execute(User $user): array
    {
        /** @var UserStorage $storage */
        $storage = $user->userStorage()->first();

        return [
          'user_name' => $user->name,
          'Storage ' . $storage['root_path'] => [
              'directories' => $this->removeStorageName(Storage::disk(CommonDictionary::STORAGE)->directories($storage->root_path), $storage->root_path),
              'files' => $this->removeStorageName(Storage::disk(CommonDictionary::STORAGE)->allfiles($storage->root_path), $storage->root_path)
          ]
        ];
    }

    private function removeStorageName(array $paths, string $storage_name): array
    {
        $clean_paths = [];

        foreach ($paths as $path) {
            $clean_paths[] = (substr($path, strlen($storage_name)+1, strlen($path)));
        }

        return $clean_paths;
    }
}
