<?php

use App\Models\Image;

if (!function_exists('storeImage')) {
    function storeImage($path, $file)
    {
        $pathImage = Storage::put($path, $file);
        $image = new Image();
        $image->url = $pathImage;
        $image->save();
        return $image->id;
    }
}

if (!function_exists('deleteImage')) {
    function deleteImage($id)
    {
        $image = Image::find($id);
        $path = $image->url;
        Storage::delete($path);
        $image->delete();
    }
}
