<?php
declare(strict_types=1);

namespace App\Http\SubActions;

use App\Exceptions\StorageException;
use App\Models\User;
use App\Models\UserStorage;
use Throwable;

class UserStorageSubAction
{
    /**
     * @param User $user
     * @return int
     */
    public function getFreeSpace(User $user): int
    {
        /** @var UserStorage $storage */
        $storage = $user->userStorage()->first();

        return $storage->storage_size;
    }

    /**
     * @param UserStorage $storage
     * @param int $file_size
     * @throws Throwable
     */
    public function updateFreeSpace(UserStorage $storage, int $file_size): void
    {
        $new_size = $storage->storage_size - $file_size;
        throw_if($new_size < 0, new StorageException('Not enough free space'));

        $storage->storage_size = $new_size;

        $storage->save();
    }

}
