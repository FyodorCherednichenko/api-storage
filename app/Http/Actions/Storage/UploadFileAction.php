<?php
declare(strict_types=1);

namespace App\Http\Actions\Storage;

use App\Dictionaries\CommonDictionary;
use App\Exceptions\StorageException;
use App\Http\Actions\AbstractAction;
use App\Http\SubActions\UserStorageSubAction;
use App\Http\Validators\UploadFileValidator;
use App\Jobs\DeleteFileByDelay;
use App\Models\UserFile;
use App\Models\UserStorage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;

class UploadFileAction extends AbstractAction
{
    public function __construct(
        public UploadFileValidator $validator,
        public UserStorageSubAction $user_storage
    ) {
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     * @throws Throwable
     */
    public function execute(Request $request): array
    {
        $validated = $this->validator->validate($request->all());

        $validated['path_to_file'] = $validated['path_to_file'] ?? null;

        $validated['time_to_live'] = $validated['time_to_live'] ?? null;

        $this->saveFile($validated['file'], $request->user()->userStorage()->first(), $validated['path_to_file'], (int) $validated['time_to_live']);

        return $this->success('File uploaded', 200);
    }

    /**
     * @param UploadedFile $file
     * @param UserStorage $storage
     * @param string|null $file_path
     * @return void
     * @throws Throwable
     */
    private function saveFile(UploadedFile $file, userStorage $storage, string|null $file_path, int $time_to_live = null): void
    {
        $file_storage_info = $this->preparePath($file_path, $storage->root_path, $file->getClientOriginalName());

        if (!$this->isDir($storage->root_path, $file_storage_info['dir_name'])) {
            $this->notExists($storage->root_path . '/' . $file_storage_info['dir_name'], "Directory {$file_storage_info['dir_name']} doesn't exists");

            $this->exists($storage->root_path . '/' . $file_storage_info['file_name'], 'file already exists');
        } else {
            $this->exists($file_storage_info['path_with_storage'], 'file already exists');
        }

        throw_if(
            !Storage::disk(
                CommonDictionary::STORAGE)
                ->putFileAs(
                    $storage->root_path . '/' . $file_storage_info['dir_name'],
                    $file,
                    $file_storage_info['file_name']
                ),
            new StorageException('didn\'t save')
        );

        $this->user_storage->updateFreeSpace($storage, $file->getSize());

        $file_db = new UserFile();
        $file_db->file_size = $file->getSize();
        $file_db->storage_id = $storage->id;
        $file_db->path = $file_storage_info['path'];

        $file_db->save();

        $this->deleteByTimer($file_storage_info, $time_to_live, $storage);
    }

    private function deleteByTimer($file_storage_info, int $time_to_live, UserStorage $storage)
    {
        if (!$time_to_live) {
            return;
        }

        DeleteFileByDelay::dispatch($storage, $file_storage_info)->delay(now()->addMinutes($time_to_live));
    }
}
