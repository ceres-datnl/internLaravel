<?php

namespace App\Services;

use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\File;
use App\Helpers\ImageUtils;

class FileService
{
    protected $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function uploadImage($image)
    {
        $pathSaveImage = public_path('uploads/images/' . date("Y/m"));

        $file         = new File();
        $originalName = $image->getClientOriginalName();
        $imageName    = time() . "-" . md5($originalName . time()) . "-" . $originalName;
        $image->move($pathSaveImage, $imageName);
        ImageUtils::saveOriginalImage($pathSaveImage, $imageName);

        $path = 'uploads/images/' . date("Y/m");
        $data = [
            "original_name" => $originalName,
            "name"          => $imageName,
            "path"          => $path,
            "created_at"    => Now(),
            "updated_at"    => Now()
        ];
        try {
            $idFile = $file->insertGetId($data);
        } catch (Exception $exception) {
            return $exception;
        }

        return $idFile;
    }

}
