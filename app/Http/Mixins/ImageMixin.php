<?php

namespace App\Http\Mixins;

use App\Models\Image;
use Storage;

class ImageMixin
{
    public function storeImage()
    {
        return function ($path,$file) {
            $pathImage = Storage::put($path, $file);
            $image = new Image();
            $image->url = $pathImage;
            $image->save();
            return $image->id;
        };
    }
}
