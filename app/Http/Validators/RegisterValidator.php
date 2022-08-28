<?php
declare(strict_types=1);

namespace App\Http\Validators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class RegisterValidator
{
    public function getRules(): array
    {
        return [
            'email'      => 'email|required|unique:users,email',
            'name'       => 'string|required',
            'password'   => ['required','confirmed', Password::min(3)],
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
