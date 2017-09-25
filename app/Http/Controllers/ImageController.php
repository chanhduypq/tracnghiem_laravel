<?php

namespace JP_COMMUNITY\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image as Image;
use Illuminate\Support\Facades\File as File;
use JP_COMMUNITY\Models\FileMaster;

class ImageController extends BaseController
{
    /**
     * Upload image
     * @param $file
     * @param $uploadSettings
     * @param $folderUpload
     * @return string
     */
    public static function upload($file,$uploadSettings,$folderUpload ){
        $destinationPath = base_path('htdocs') .$folderUpload;
        $extension = $file->getClientOriginalExtension();
        $name = $file->getClientOriginalName();
        $filename        = strtolower(preg_replace("/[^\w]+/", "-",str_replace('.'.$extension,'',$name))). '_'.uniqid().'.'.$extension;

        $uploadSuccess   = $file->move($destinationPath, $filename);

        list($width, $height) = getimagesize($destinationPath.$filename);
        $radio = $width/$height;

        if (!empty($uploadSettings)) {
            foreach ($uploadSettings as $key => $upload) {
                $img = Image::make($destinationPath.$filename);
                if (($upload['width']/$upload['height']) > $radio) {
                    $img->resize($upload['width'], null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }else{
                    $img->resize(null, $upload['height'], function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }

                //	Create folder if it 's not exist
                if (!File::isDirectory(base_path('htdocs') .$upload['path'])) {
                    File::makeDirectory(base_path('htdocs') .$upload['path'], 0777, true);
                }
                $img->save(base_path('htdocs') .$upload['path'].$filename);
            }
        }

        return $filename;
    }

    /**
     * User full for upload image in CKEditor
     * @param Request $request
     * @return view render
     */
    public function uploadImageCkEditor(Request $request) {
        $file = $request->file('upload');
        $rootPath = base_path('htdocs');
        $basePath = '';
        if ($request->is('upload_image_editor_news')) {
            $basePath = '/uploads/users/news/'. Auth::id() .'/';
        } else {
            $basePath = '/uploads/users/'. Auth::id() .'/';
        }

        $fullPath = $rootPath.$basePath;
        $extension = $file->getClientOriginalExtension();
        $name = $file->getClientOriginalName();
        $filename = strtolower(preg_replace("/[^\w]+/", "-",str_replace('.'.$extension,'',$name))). '_'.uniqid().'.'.$extension;

        $file->move($fullPath, $filename);

        return view('CKEditors.uploaded', [
            'CKEditorFuncNum' => $request->CKEditorFuncNum,
            'data' => [
                'url' => asset($basePath.$filename),
                'message' => 'upload successful',
            ],
        ]);

    }

    /**
     * Upload and save to file_master table
     */
    public static function uploadMaster($file,$uploadSettings,$folderUpload) {

        $destinationPath = base_path('htdocs') .$folderUpload;
        $extension = $file->getClientOriginalExtension();
        $name = $file->getClientOriginalName();
        $filename        = strtolower(preg_replace("/[^\w]+/", "-",str_replace('.'.$extension,'',$name))). '_'.uniqid().'.'.$extension;

        $uploadSuccess   = $file->move($destinationPath, $filename);

        list($width, $height) = getimagesize($destinationPath.$filename);
        $radio = $width/$height;

        if (!empty($uploadSettings)) {
            foreach ($uploadSettings as $key => $upload) {
                $img = Image::make($destinationPath.$filename);
                if (($upload['width']/$upload['height']) > $radio) {
                    $img->resize($upload['width'], null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }else{
                    $img->resize(null, $upload['height'], function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }

                //	Create folder if it 's not exist
                if (!File::isDirectory(base_path('htdocs') .$upload['path'])) {
                    File::makeDirectory(base_path('htdocs') .$upload['path'], 0777, true);
                }
                $img->save(base_path('htdocs') .$upload['path'].$filename);
            }
        }

        $resImage = FileMaster::create([
            'user_id' => Auth::id(),
            'type' => 'image',
            'name' => $filename,
            'path' => $destinationPath,
            'extension' => $extension,
            'upload_by' => Auth::id()
        ]);

        return !empty($resImage) ? $resImage->file_id : null;
    }
}
