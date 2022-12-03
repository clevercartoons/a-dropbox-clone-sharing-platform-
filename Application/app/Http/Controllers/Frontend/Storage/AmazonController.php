<?php

namespace App\Http\Controllers\Frontend\Storage;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\UploadController;
use Exception;
use Storage;
use Str;

class AmazonController extends Controller
{
    public static function setCredentials($data)
    {
        setEnv('AWS_ACCESS_KEY_ID', $data->credentials->access_key_id);
        setEnv('AWS_SECRET_ACCESS_KEY', $data->credentials->secret_access_key);
        setEnv('AWS_DEFAULT_REGION', $data->credentials->default_region);
        setEnv('AWS_BUCKET', $data->credentials->bucket);
        setEnv('AWS_URL', $data->credentials->url);
    }

    public static function upload($file, $path, $storageProvider)
    {
        try {
            $originalFileName = $file->getClientOriginalName();
            $fileType = $file->getclientoriginalextension();
            $fileSize = $file->getSize();
            $filePath = $path;
            $tempPath = storage_path("app/temp/");
            $tempFileName = time() . '_' . Str::random(16) . '.' . $fileType;
            $uploadTempFile = $file->move($tempPath, $tempFileName);
            if ($uploadTempFile) {
                $disk = Storage::disk('s3');
                if ($disk->has($filePath . $originalFileName)) {
                    $randomString = Str::random(5);
                    $fileName = pathinfo($originalFileName, PATHINFO_FILENAME);
                    $originalFileName = $fileName . "_" . $randomString . "." . $fileType;
                }
                $moveFileToStorageProvider = $disk->put($filePath . $originalFileName, fopen($tempPath . $tempFileName, 'r+'));
                if ($moveFileToStorageProvider) {
                    removeFile($tempPath . $tempFileName);
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
            $disk = Storage::disk('s3');
            if ($disk->has($filePath)) {
                $downloadLink = $disk->temporaryUrl($filePath, now()->addHour(), ['ResponseContentDisposition' => 'attachment']);
                return json_encode(['status' => 'success', 'link' => $downloadLink]);
            } else {
                return json_encode(['status' => 'error', 'msg' => lang('Requested file not exists', 'download')]);
            }
        } catch (Exception $e) {
            return json_encode(['status' => 'error', 'msg' => lang('Storage provider error', 'upload zone')]);
        }
    }

    public static function delete($filePath)
    {
        $disk = Storage::disk('s3');
        if ($disk->has($filePath)) {
            $disk->delete($filePath);
        }
        return true;
    }

}
