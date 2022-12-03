<?php

namespace App\Http\Controllers\Frontend\Storage;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\UploadController;
use Exception;
use Storage;
use Str;

class LocalController extends Controller
{
    public static function upload($file, $path, $storageProvider)
    {
        try {
            $originalFileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $filePath = "app/public/" . $path;
            if (file_exists(storage_path($filePath . $originalFileName))) {
                $randomString = Str::random(5);
                $fileType = $file->getclientoriginalextension();
                $fileName = pathinfo($originalFileName, PATHINFO_FILENAME);
                $originalFileName = $fileName . "_" . $randomString . "." . $fileType;
            }
            $upload = $file->move(storage_path($filePath), $originalFileName);
            if ($upload) {
                $createNewUploadFile = UploadController::createNewUploadFile(
                    $originalFileName,
                    $fileSize,
                    $path . $originalFileName,
                    $storageProvider->id
                );
                $data = json_decode($createNewUploadFile);
                return json_encode([
                    'status' => 'success',
                    'id' => $data->id,
                ]);
            }
        } catch (Exception $e) {
            return json_encode([
                'status' => 'error',
                'msg' => lang('Storage provider error', 'upload zone'),
            ]);
        }
    }

    public static function download($filePath)
    {
        try {
            $disk = Storage::disk('public');
            if ($disk->has($filePath)) {
                $fileInfo = pathinfo($disk->path($filePath));
                if ($fileInfo['extension'] == "apk") {
                    $headers = [
                        'Content-Type' => 'application/vnd.android.package-archive',
                        'Content-Disposition' => 'attachment; filename="' . $fileInfo['basename'] . '"',
                    ];
                    return response()->download($disk->path($filePath), $fileInfo['basename'], $headers);
                } else {
                    return response()->download($disk->path($filePath));
                }
            } else {
                return json_encode(['status' => 'error', 'msg' => lang('Requested file not exists', 'download')]);
            }
        } catch (Exception $e) {
            return json_encode(['status' => 'error', 'msg' => lang('Storage provider error', 'upload zone')]);
        }
    }

    public static function delete($filePath)
    {
        $disk = Storage::disk('public');
        if ($disk->has($filePath)) {
            $disk->delete($filePath);
        }
        return true;
    }

}
