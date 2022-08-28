<?php
declare(strict_types=1);

namespace App\Http\Actions;

use App\Http\SubActions\CreateDirectorySubAction;
use App\Http\Validators\RegisterValidator;
use App\Models\User;
use App\Models\UserStorage;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;

class RegisterAction
{
    public function __construct(
        public RegisterValidator $validator,
        public CreateDirectorySubAction $create_directory
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function execute(array $register_data): array|MessageBag
    {
        $validated =  $this->validator->validate($register_data);

        $user = User::create([
            'name' => $validated['name'],
            'password' => bcrypt($validated['password']),
            'email' => $validated['email']
        ]);

        $user_storage = new UserStorage();
        $user_storage->user_id = $user->id;
        $user_storage->root_path = 'user' . $user->id;
        $user_storage->save();

        $this->create_directory->execute($user_storage->root_path);

        return [
            'status' => 'success',
            'message' => 'user successfully created',
            'token' => $user->createToken('tokens')->plainTextToken
        ];
    }
}
