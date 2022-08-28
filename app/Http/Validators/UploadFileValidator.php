<?php
declare(strict_types=1);

namespace App\Http\Validators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UploadFileValidator
{
    public function __construct(
        public FileNameValidator $file_name_validator
    ) {
    }

    /**
     * @return array
     */
    private function getRules(): array
    {
        return array_merge([
            'file'           => ['file', 'required', 'max:20480',
                function ($attribute, $value, $fail) {
                    if ($value->clientExtension() === 'php') {
                        $fail('php files is not allowed');
                    }
                }],
            'time_to_live' => ['int', 'max:525600']
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
