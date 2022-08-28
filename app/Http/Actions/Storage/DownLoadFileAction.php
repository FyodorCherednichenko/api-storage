<?php
declare(strict_types=1);

namespace App\Http\Actions\Storage;

use App\Dictionaries\CommonDictionary;
use App\Http\Actions\AbstractAction;
use App\Http\Validators\FileNameValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class DownLoadFileAction extends AbstractAction
{
    public function __construct(
        public FileNameValidator $validator
    ) {

    }

    /**
     * @throws ValidationException
     * @throws \Throwable
     */
    public function execute(Request $request): string
    {
        $validated = $this->validator->validate($request->all());
        $file_info = $this->preparePath($validated['path_to_file'], $request->user()->userStorage->root_path);
        $this->notExists($file_info['path_with_storage'], 'File not Found');

        return $file_info['path_with_storage'];
    }
}
