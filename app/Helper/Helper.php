<?php

use App\Models\Image;

if(!function_exists('storeImage')) {
    function storeImage($path,$file)
    {
        $pathImage = Storage::put($path, $file);
            $image = new Image();
            $image->url = $pathImage;
            $image->save();
            return $image->id;
    }
}
