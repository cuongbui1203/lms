<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Image\ImageUploadRequest;
use App\Models\Image;
use Exception;
use Storage;

class ImageController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Image $image)
    {
        // dd($image);
        try {
            return Storage::download($image->url);
            // return response()->download(Storage::get($image->url), 'image');
        } catch (Exception $e) {
            abort(404);
        }
    }
    public function store(ImageUploadRequest $request)
    {
        return response()->json([
            'link' => route('api.image.show', [
                'image' => storeImage('image', $request->file('image')),
            ]),
        ]);
    }
}
