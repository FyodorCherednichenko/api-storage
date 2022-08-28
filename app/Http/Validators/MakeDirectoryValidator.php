<?php
declare(strict_types=1);

namespace App\Http\Validators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MakeDirectoryValidator
{
    private function getRules(): array
    {
        return [
            'directory_name' => ['required', 'regex:/^[^(\|\]~`!%^&*=};:?><’)]*$/', 'not_regex:/^\w+[\/]\w+$/']
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
