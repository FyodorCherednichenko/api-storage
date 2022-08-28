<?php
declare(strict_types=1);

namespace App\Http\Actions\Storage;

use App\Http\Actions\AbstractAction;
use App\Http\SubActions\DeleteFileSubAction;
use App\Http\Validators\FileNameValidator;
use App\Models\UserStorage;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class DeleteFileAction extends AbstractAction
{
    public function __construct(
        public FileNameValidator $validator,
        public DeleteFileSubAction $sub_action
    ) {
    }

    /**
     * @throws ValidationException
     * @throws Throwable
     */
    public function execute(Request $request): array
    {
        $validated =  $this->validator->validate($request->all());

        /** @var UserStorage $storage */
        $storage = $request->user()->userStorage()->first();

        $file_info = $this->preparePath($validated['path_to_file'], $storage->root_path);

        $this->sub_action->execute($file_info, $storage);

        return $this->success('File deleted', 200);
    }
}
