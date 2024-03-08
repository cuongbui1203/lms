<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderDetail extends Model
{
    use HasFactory;

    public function type():BelongsTo
    {
        return $this->belongsTo(Type::class);
    }
    public function order():BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
    public function image():BelongsTo
    {
        return $this->belongsTo(Image::class);
    }

    protected $cast = [
        'created_at'=>'timestamp',
        'updated_at'=>'timestamp'
    ];
}