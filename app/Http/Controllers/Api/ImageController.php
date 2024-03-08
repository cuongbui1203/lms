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
        // dd($image);
        try{
            return response()->download($image->url, 'image');
        }catch(Exception $e){
            dd($e);
            return response()->noContent(404);
        }
    }

}
