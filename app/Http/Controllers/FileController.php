<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use App\Http\Requests\NameUpdateRequest;
use App\Models\File;
use App\Models\FilesUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function addFile(FileRequest $request): array{
        if($request->hasFile('file')){
            $files = $request->file('file');
            foreach ($files as $file) {
                $file->store('files');
                $created = File::create(['file_uniq' => $file->hashName(),'path' => 'files/' . $file->hashName(), 'name' => $file->getClientOriginalName()]);
//                dd(File::select('select id from users where file_uniq = :file_uniq', ['file_uniq' => $file->hashName()]));
                FilesUser::create(['file_id' => $created->id, 'user_id' => $request->user()->id, 'permission' => 'Author']);
            }
            return [
                'success' => true,
                'message' => 'File successfully uploaded'
            ];
        } else {
            return ['success' => false,
                'message' => 'File not uploaded'];
        }
    }
    public function renameFile(NameUpdateRequest $request) {
        $file_id = $request->id;
        if(File::query()->where('id', $file_id)->exists()){
            if(FilesUser::query()->where('id', $file_id)->get('permission') === 'Author' || 'Co-Author'){
                File::query()->where('id', $file_id)->update(['name'=>$request->name]);
                return ['success' => true,
                    'message' => 'File successfully renamed'];
            }
            return ['success' => false,
                'message' => 'Forbidden for you'];
        }
        return ['success' => false,
            'message' => 'File not found'];
    }

    public function accesses(Request $request)
    {
        $id = $request->id;
        if(File::query()->where('id', $id)->exists()){

            if(FilesUser::query()->where('file_id', $id)->where('user_id', $request->user()->id)->where('permission', 'Author')->exists()){
                if(User::query()->where('email', $request->email)->exists()){

                    $user = User::query()->where('email', $request->email)->get('id');
                    FilesUser::create(['user_id' => $user[0]->id, 'file_id' => $id , 'permission' => 'Co-Author']);
                    $temp=FilesUser::query()->where('file_id', $id)->get()->toArray();
                    $accesses = [];
                    foreach ($temp as $t) {
                        $t_name = User::query()->where('id', $t['user_id'])->get('name');
                        $t_email = User::query()->where('id', $t['user_id'])->get('email');
                        $accesses[] = array(
                            "fullname" => $t_name[0]->name,
                            "email" => $t_email[0]->email,
                            "type" => $t['permission'],
                            "code" => 200
                        );

                    }
                    return $accesses;

                }

            }
        }
    }

    public function fileDelete(Request $request): array
    {
        if(File::query()->where('id', $request->id)->exists()){
            if (FilesUser::query()->where('file_id', $request->id)->where('user_id', $request->user()->id)->where('permission', 'Author')->exists()) {
                $file = File::query()->where('id', $request->id)->get('path');
                Storage::delete($file);
                return [
                    'success' => true,
                    'code'=> 200,
                    'message' => 'File deleted'
                ];
            } else {
                return [
                    'success' => false,
                    'code'=> 403,
                    'message' => 'Forbidden for you'
                ];
            }

        } else {
            return [
                'success' => false,
                'code'=> 404,
                'message' => 'File not found'
            ];
        }

    }

    public function getAllFiles(Request $request): array
    {
        //dd($request->user());
        $users = FilesUser::query()->where("user_id", auth()->id())->get();
        foreach ($users as $user) {
            $file = File::query()->where('id', $user->file_id)->get();
            $files[] = $file;
        }
        return [
            'success' => true,
            'code' => 200,
            'data' => $files,
        ];
    }
    public function downloadFile(Request $request)
    {
        $url=File::query()->where('id', $request->id)->get('path');
        if(Storage::download($url[0]->path)){
            return[
                'success' => true,
                'code' => 200,
                'message' => 'File successfully downloaded'
            ];
        }
    }
}
