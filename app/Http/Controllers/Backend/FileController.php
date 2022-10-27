<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\FileService;

class FileController extends Controller
{
    protected $file;

    public function __construct(FileService $file)
    {
        $this->file = $file;
    }

    public function uploadImageNews(Request $request)
    {
        $response = $this->file->uploadImage($request->inputFiles);
        echo json_encode($response);
        exit;
    }
}
