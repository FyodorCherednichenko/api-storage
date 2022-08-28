<?php
declare(strict_types=1);

namespace App\Http\Validators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RenameFileValidator
{
    public function __construct(
        public FileNameValidator $file_name_validator
    ) {
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return array_merge([
            'new_file_name' => ['string', 'required', 'regex:/^[^(\|\]\/~`!%^&*=};:?><’)]*$/'],
        ],
            $this->file_name_validator->getRules()
        );
    }

    /**
     * @param array $data
     * @return array
     * @throws ValidationException
     */
    public function validate(array $data): array
    {
        return Validator::make($data, $this->getRules())->validate();
    }
}
