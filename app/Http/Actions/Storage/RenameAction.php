<?php
declare(strict_types=1);

namespace App\Http\Actions\Storage;

use App\Dictionaries\CommonDictionary;
use App\Http\Actions\AbstractAction;
use App\Http\Validators\RenameFileValidator;
use App\Models\UserFile;
use App\Models\UserStorage;
use App\QueryBuilders\UserFileQueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class RenameAction extends AbstractAction
{
    public function __construct(
        public RenameFileValidator  $validator,
        public UserFileQueryBuilder $query
    ) {
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function execute(Request $request): array
    {
        $validated =  $this->validator->validate($request->all());

        /** @var UserStorage $storage */
        $storage = $request->user()->userStorage()->first();

        $file_info = $this->preparePath($validated['path_to_file'], $storage->root_path);

        $this->renameFile($file_info, $storage, $validated['new_file_name']);

        return $this->success('File renamed', 200);
    }

    private function renameFile(array $file_info, UserStorage $storage, string $new_file_name)
    {
        $this->notExists($file_info['path_with_storage'], 'Source file not found');

        $future_path_with_storage = substr(
            $file_info['path_with_storage'],
            0,
            strlen($file_info['path_with_storage']) - strlen($file_info['file_name'])
        ) . $new_file_name;

        $future_path = substr(
                $file_info['path'],
                0,
                strlen($file_info['path']) - strlen($file_info['file_name'])
            ) . $new_file_name;

        $this->exists($future_path_with_storage, "File with name {$new_file_name} already exist");

        /** @var UserFile $file_record */
        $file_record = $this->query->clone()->where('path', $file_info['path'])->first();

        throw_if(
            !Storage::disk(CommonDictionary::STORAGE)->move($file_info['path_with_storage'], $future_path_with_storage),
            'ERROR'
        );

        $file_record->path = $future_path;
        $file_record->save();
    }
}
