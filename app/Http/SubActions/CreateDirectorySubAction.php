<?php
declare(strict_types=1);

namespace App\Http\SubActions;

use App\Dictionaries\CommonDictionary;
use App\Exceptions\StorageException;
use App\Http\Actions\AbstractAction;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CreateDirectorySubAction extends AbstractAction
{
    /**
     * @param string $path
     * @return array
     * @throws Throwable
     */
    public function execute(string $path): array
    {
        $this->exists($path, 'directory already exists');

        throw_if(!Storage::disk(CommonDictionary::STORAGE)->makeDirectory($path), new StorageException('directory was not created'));

        return [
            'status'  => 'success',
            'message' => 'directory has been created'
        ];
    }
}
