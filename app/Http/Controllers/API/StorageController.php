<?php
declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Dictionaries\CommonDictionary;
use App\Http\Actions\Storage\DeleteFileAction;
use App\Http\Actions\Storage\DownLoadFileAction;
use App\Http\Actions\Storage\MakeDirectoryAction;
use App\Http\Actions\Storage\PrintStorageAction;
use App\Http\Actions\Storage\RenameAction;
use App\Http\Actions\Storage\UploadFileAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class StorageController
{
    public function index(Request $request, PrintStorageAction $action): JsonResponse
    {
        return response()->json($action->execute($request->user()),200,[],JSON_UNESCAPED_SLASHES);
    }

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function uploadFile(Request $request, UploadFileAction $action): JsonResponse
    {
        return response()->json($action->execute($request));

    }

    /**
     * @param Request $request
     * @param DownLoadFileAction $action
     * @return BinaryFileResponse
     * @throws Throwable
     * @throws ValidationException
     */
    public function downloadFile(Request $request, DownloadFileAction $action): BinaryFileResponse
    {
        return response()->download(storage_path('user_directories') . '/' . $action->execute($request));
    }

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function renameFile(Request $request, RenameAction $action): JsonResponse
    {
        return response()->json($action->execute($request));
    }

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function deleteFile(Request $request, DeleteFileAction $action): JsonResponse
    {
        return response()->json($action->execute($request));
    }

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function makeDirectory(Request $request, MakeDirectoryAction $action): JsonResponse
    {
        return response()->json($action->execute($request));
    }
}
