<?php
declare(strict_types=1);

namespace App\Http\Actions;

use App\Http\Validators\LoginValidator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;

class LoginAction extends AbstractAction
{
    public function __construct(
        public LoginValidator $validator,
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function execute(array $register_data): array|MessageBag
    {
        $validated =  $this->validator->validate($register_data);

        if (!Auth::attempt($validated)) {
            return $this->error('Credentials not match', 401);
        }

        return $this->success([
            'message' => 'logged in',
            'token' => auth()->user()->createToken('API Token')->plainTextToken
        ], 200);
    }
}
