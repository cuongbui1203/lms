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

if (!function_exists('api_path')) {
    /**
     * get path of api directory
     *
     * @param string $path
     * @return string
     */
    function api_path($path = '')
    {
        return base_path('/routes/api/' . $path);
    }
}
