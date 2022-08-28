<?php
declare(strict_types=1);

namespace App\Http\SubActions;

use App\Dictionaries\CommonDictionary;
use App\Exceptions\StorageException;
use App\Http\Actions\AbstractAction;
use App\Models\UserFile;
use App\Models\UserStorage;
use App\QueryBuilders\UserFileQueryBuilder;
use Illuminate\Support\Facades\Storage;
use Throwable;

class DeleteFileSubAction extends AbstractAction
{
    public function __construct(
        public UserFileQueryBuilder $query
    ) {
    }

    /**
     * @throws Throwable
     */
    public function execute(array $file_info, UserStorage $storage)
    {
        $this->notExists($file_info['path_with_storage'], 'file doesn\'t exist');

        if ($file_info['file_name'] === "") {
            $this->rmDir($storage->root_path, $file_info['dir_name']);
        } else {
            /** @var UserFile $file_record */
            $file_record = $this->query->clone()->where('path', $file_info['path'])->first();

            throw_if(
                !Storage::disk(CommonDictionary::STORAGE)->delete($file_info['path_with_storage']),
                new StorageException('File wasn\'t delete')
            );

            $storage->storage_size += $file_record->file_size;

            $storage->save();

            $file_record->delete();
        }
    }

    /**
     * @param string $storage_path
     * @param $dir_name
     * @throws Throwable
     */
    private function rmDir(string $storage_path, $dir_name): void
    {
        if ($this->isDir($storage_path, $dir_name)) {
            $this->isNotEmptyDir($storage_path, $dir_name);
            Storage::disk(CommonDictionary::STORAGE)->deleteDirectory($storage_path . '/' . $dir_name);
        }
    }
}
