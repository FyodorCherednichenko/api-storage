<?php
declare(strict_types=1);

namespace App\Http\Actions;

use App\Dictionaries\CommonDictionary;
use App\Exceptions\StorageException;
use App\Models\UserFile;
use App\Models\UserStorage;
use Illuminate\Support\Facades\Storage;
use Throwable;

abstract class AbstractAction
{
    public function success(mixed $message, int $code = null): array
    {
        $response = [
            'status' => 'success',
            'message' => $message
        ];

        if ($code) {
            $response = array_merge($response, ['code' => $code]);
        }

        return $response;
    }

    public function error(mixed $message, int $code = null): array
    {
        $response = [
            'status' => 'fail',
            'message' => $message
        ];

        if ($code) {
            $response = array_merge($response, ['code' => $code]);
        }

        return $response;
    }

    /**
     * @param string $path
     * @param string $message
     * @return void
     * @throws Throwable
     */
    public function exists(string $path, string $message): void
    {
        throw_if(Storage::disk(CommonDictionary::STORAGE)->exists($path), new StorageException($message));
    }

    /**
     * @param string $path
     * @param string $message
     * @return void
     * @throws Throwable
     */
    public function notExists(string $path, string $message): void
    {
        throw_if(!Storage::disk(CommonDictionary::STORAGE)->exists($path), new StorageException($message));
    }

    public function preparePath(string|null $file_path, string $user_storage, string $file_original_name = ''): array
    {
        if ($file_path) {
            $file_info = explode('/', $file_path);

            if (count($file_info) > 1) {
                return [
                    'dir_name' => $file_info[0],
                    'file_name' => $file_info[1],
                    'path'      => $file_path,
                    'path_with_storage' => $user_storage . '/' . $file_path
                ];
            }

            if ($this->isDir($user_storage, $file_info[0])) {
                return [
                    'dir_name' => $file_info[0],
                    'file_name' => $file_original_name,
                    'path'      => $file_info[0] . '/' . $file_original_name,
                    'path_with_storage' => $user_storage . '/' . $file_info[0] . '/' . $file_original_name
                ];
            }

            return [
                'dir_name' => '',
                'file_name' => $file_info[0],
                'path'      => $file_info[0],
                'path_with_storage' => $user_storage . '/' . $file_info[0]
            ];
        }

        return [

            'dir_name' => '',
            'file_name' => $file_original_name,
            'path'      => $file_original_name,
            'path_with_storage' => $user_storage . '/' . $file_original_name
        ];
    }

    /**
     * @param $user_storage
     * @param $dir_name
     * @return void
     * @throws Throwable
     */
    public function isNotEmptyDir($user_storage, $dir_name): void
    {
        if ($this->isDir($user_storage, $dir_name)) {
            throw_if(
                Storage::disk(CommonDictionary::STORAGE)->files($user_storage . '/' . $dir_name),
                new StorageException("{$dir_name} - is directory, and it is not empty")
            );
        }
    }

    /**
     * @param string $user_storage
     * @param string $dir_name
     * @return bool
     */
    public function isDir(string $user_storage, string $dir_name): bool
    {
        return in_array($user_storage . '/' . $dir_name,
            Storage::disk(CommonDictionary::STORAGE)->directories($user_storage));
    }
}
