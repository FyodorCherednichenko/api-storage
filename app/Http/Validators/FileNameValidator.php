<?php
declare(strict_types=1);

namespace App\Http\Validators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class FileNameValidator
{
    public function getRules(): array
    {
        return [
            'path_to_file' => ['string','required_without:file', 'regex:/^[^(\|\]~`!%^&*=};:?><’)]*$/', 'regex:/^\w+[\/]*\w+$/']
        ];
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
