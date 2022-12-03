<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\StorageProvider;
use App\Models\Upload;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $uploadedFileName = $request->file('file')->getClientOriginalName() . '.' . $request->file('file')->getclientoriginalextension();
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));
        try {
            $unacceptedTypesArr = explode(',', settings('unaccepted_file_types'));
            $fileExt = $request->file('file')->getclientoriginalextension();
            if (in_array($fileExt, $unacceptedTypesArr)) {
                return response()->json(['type' => 'error', 'msg' => lang('You cannot upload files of this type.', 'upload zone')]);
            }
            if (!subscription()->is_subscribed or subscription()->is_expired) {
                return response()->json(['type' => 'error', 'msg' => lang('You have no subscription or your subscription has been expired', 'alerts')]);
            }
            if (subscription()->is_canceled) {
                return response()->json(['type' => 'error', 'msg' => lang('Your subscription has been canceled, please contact us for more information', 'alerts')]);
            }
            if (!is_null(subscription()->storage->remaining_space_number)) {
                if ($request->total_size > subscription()->storage->remaining_space_number) {
                    return response()->json(['type' => 'error', 'msg' => lang('Insufficient storage space, please check your space or upgrade your plan', 'alerts')]);
                }
            }
            if (!is_null(subscription()->plan->transfer_size_number)) {
                if ($request->total_size > subscription()->plan->transfer_size_number) {
                    return response()->json(['type' => 'error', 'msg' =>
                        str_replace('{maxTransferSize}', subscription()->plan->transfer_size, lang('Max size per transfer : {maxTransferSize}.', 'upload zone'))]);
                }
            }
            $storageProvider = StorageProvider::where([['symbol', env('FILESYSTEM_DRIVER')], ['status', 1]])->first();
            if (is_null($storageProvider)) {
                return response()->json(['type' => 'error', 'msg' => lang('Unavailable storage provider', 'upload zone')]);
            }
            if ($receiver->isUploaded() === false) {
                throw new UploadMissingFileException();
            }
            $save = $receiver->receive();
            if ($save->isFinished()) {
                $file = $save->getFile();
                $path = (Auth::user()) ? "users/" . hashid(userAuthInfo()->id) . "/" : "anonymous/" . vIpInfo()->ip . "/";
                $handler = $storageProvider->handler;
                $upload = $handler::upload($file, $path, $storageProvider);
                $upload = json_decode($upload);
                if ($upload->status == "error") {
                    return response()->json(['type' => 'error', 'msg' => $upload->msg]);
                }
                return response()->json(['type' => 'success', 'id' => hashid($upload->id)]);
            }
        } catch (Exception $e) {
            return response()->json(['type' => 'error', 'msg' => lang('Failed to upload', 'upload zone') . ' (' . $uploadedFileName . ')']);
        }
    }

    public static function createNewUploadFile($fileName, $fileSize, $filePath, $storageProviderId)
    {
        $userId = (Auth::user()) ? Auth::user()->id : null;
        $createUpload = Upload::create([
            'user_id' => $userId,
            'storage_provider_id' => $storageProviderId,
            'ip' => vIpInfo()->ip,
            'name' => $fileName,
            'size' => $fileSize,
            'path' => $filePath,
        ]);
        if ($createUpload) {
            return json_encode(['id' => $createUpload->id]);
        }
    }

    public function destroy(Request $request)
    {
        if (Auth::user()) {
            $file = Upload::where([['id', unhashid($request->id)], ['user_id', userAuthInfo()->id]])->with('storageProvider')->first();
        } else {
            $file = Upload::where([['id', unhashid($request->id)], ['ip', vIpInfo()->ip], ['user_id', null]])->with('storageProvider')->first();
        }
        if (is_null($file)) {
            return response()->json(['error' => lang('File not exists', 'upload zone')]);
        }
        $handler = $file->storageProvider->handler;
        $deleteFile = $handler::delete($file->path);
        if ($deleteFile) {
            $file->delete();
        }
    }
}
