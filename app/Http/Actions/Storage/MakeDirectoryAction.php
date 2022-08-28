<?php
declare(strict_types=1);

namespace App\Http\Actions\Storage;

use App\Dictionaries\CommonDictionary;
use App\Http\SubActions\CreateDirectorySubAction;
use App\Http\Validators\MakeDirectoryValidator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class MakeDirectoryAction
{
    public function __construct(
        public MakeDirectoryValidator $validator,
        public CreateDirectorySubAction $sub_action
    ) {
    }

    /**
     * @throws ValidationException|Throwable
     */
    public function execute(Request $request): array
    {
        $validated = $this->validator->validate($request->all());

        $path = CommonDictionary::USER_STORAGE_PREFIX . $request->user()->id . '/' . $validated['directory_name'];

        return $this->sub_action->execute($path);
    }
}
