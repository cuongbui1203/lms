<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Image extends Model
{
    use HasFactory;

    protected $appends = ['link'];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'img_id');
    }

    public function link(): Attribute
    {
        return Attribute::make(
            get: fn() => route('api.image.show', ['image' => $this->attributes['id']]),
        );
    }
}
