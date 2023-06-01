<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function upload()
    {
        if (request()->hasFile('csv')) {
            $file = request()->file('csv');
            if ($file->getSize() > 0) {
                $path = $file->storeAs('uploads', $file->getClientOriginalName());
                return response()->json(['message' => 'success', 'path' => $path]);
            } else {
                return response()->json(['error' => 'File is empty']);
            }
        } else {
            return response()->json(['error' => 'No file uploaded']);
        }
    }

    public function parse()
    {
        $uploadResponse = $this->upload()->getData(true);
        return $uploadResponse['path'];
    }

    public function uploadCSV()
    {
        $file = fopen(Storage::path((new FileController)->parse()), 'r');
        while (!feof($file)) {
            $content[] = fgetcsv($file);
        }

        fclose($file);
        array_shift($content);

        return $content;
    }
}
