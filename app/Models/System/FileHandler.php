<?php


namespace App\Models\System;


use App\Models\Web\UserFolder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileHandler
{

    public function upload($request)
    {
        $fileArr = [];
        $userFolder = UserFolder::where('user_id', Auth::user()->id)->first();
        $userFolderArr = [
            'folder_name' => md5(Str::random(10)),
            'user_id' => Auth::user()->id
        ];
        $folder_id = '';
        if (!is_null($userFolder)) {
            $fileArr['user_folder'] = Crypt::decryptString($userFolder->folder_name);
            $folder_id = $userFolder->id;
        } else {

            $createFile = CRUD::create(RequestEncrypt::encrypt($userFolderArr), config('system_config.models.user_folder'), 'user_folder');
            if ($createFile['success']) {
                $fileArr['user_folder'] = Crypt::decryptString($createFile['data']->folder_name);
                $folder_id = $createFile['data']->id;
            }
        }
        $file = $request->file('file');

        if ($request->file('file')->isValid()) {
            $fileArr['ext'] = trim($file->getClientOriginalExtension());
            $fileArr['original_file_name'] = str_replace("." . $fileArr['ext'], "", $file->getClientOriginalName());
            $fileArr['filename'] = md5(Str::random(10));
            $fileArr['user_id'] = Auth::user()->id;
            $fileArr['folder_id'] = $folder_id;
        }

        if ($fileArr['ext'] !== 'pdf') {
            return ActionResponse::error('Could not upload file', [], false);
        } else {
            if ($request->file('file')->isValid()) {
                $path = public_path() .'/720526039c7ddee22606ee5a8cb2a1b2/' . $fileArr['user_folder'] ;
                $file->move($path, $fileArr['filename'].'.'.$fileArr['ext']);
                return self::save($fileArr,$path);
            }
        }

        return ActionResponse::error('Could not upload file', [], false);
    }

    public function save($fileArr,$path){

        $fileSave= CRUD::create(RequestEncrypt::encrypt($fileArr), config('system_config.models.mpesa'), 'mpesa_statements');
        if($fileSave){
            $realPath= $path.'/'.$fileArr['filename'].'.'.$fileArr['ext'];
            return ActionResponse::success('File uploaded successfully', ['real_path'=>$realPath,'file'=>$fileSave,'filename'=>$fileArr['filename'].'.'.$fileArr['ext']], true);
        }else{
            return ActionResponse::error('Could not save file', [], false);
        }

    }
}
