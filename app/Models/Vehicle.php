<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    use HasFactory;

    public function type():BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    protected $cast = [
        'created_at'=>'timestamp',
        'updated_at'=>'timestamp'
    ];
}
