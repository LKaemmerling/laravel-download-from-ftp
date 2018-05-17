<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class FTPController extends Controller
{

    public function list_folders()
    {
        return view('list_folders');
    }

    public function api_list_folders()
    {
        if (Cache::has('files')) {
            $resp = Cache::get('files');
        } else {
            $resp = Storage::disk('ftp')->allFiles();
            Cache::put('files', $resp, 5);
        }
        return response()->json($resp);
    }

    public function download_file($file)
    {
        $files = Cache::get('files');
        return Storage::disk('ftp')->download($files[$file]);
    }
}