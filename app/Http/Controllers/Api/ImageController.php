<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Exception;

class ImageController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Image $image)
    {
        try {
            return response()->download($image->url, 'image');
        } catch (Exception $e) {
            abort(404);
        }
    }
}
