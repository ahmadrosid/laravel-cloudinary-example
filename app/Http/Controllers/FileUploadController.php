<?php

namespace App\Http\Controllers;

use App\Models\UploadedFile;
use Illuminate\Http\Request;
use Cloudinary\Cloudinary;

class FileUploadController extends Controller
{
    public function index()
    {
        $uploadedFiles = UploadedFile::orderBy('id','desc')->get();
        return view('welcome', compact('uploadedFiles'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);


        $cloudinary = new Cloudinary();
        $uploadApi = $cloudinary->uploadApi();
        $result = $uploadApi->upload($request->file('file')->getRealPath());

        $publicId = $result['public_id'];
        $url = $result['url'];

        $fileName = $request->file('file')->getClientOriginalName();
        UploadedFile::create([
            'file_name' => $fileName,
            'public_id' => $publicId,
            'url' => $url,
        ]);

        return redirect()->back()->with('success', 'File uploaded successfully!');
    }
}
